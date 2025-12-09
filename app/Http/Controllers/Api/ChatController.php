<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;

class ChatController extends Controller
{
    // start or get existing chat
    public function start(Request $request) 
    {
        $auth = $request->user();

        $id_user = null;
        $id_teknisi = null;

        // Jika role pelanggan → user ini adalah pelanggan, teknisi dikirim dari request
        if ($auth->role === 'pelanggan') {
            $id_user = $auth->id_user;
            $id_teknisi = $request->input('id_teknisi');
        }

        // Jika role teknisi → user ini adalah teknisi, pelanggan dikirim dari request
        if ($auth->role === 'teknisi') {
            $id_user = $request->input('id_user');        // harus dikirim dari flutter
            $id_teknisi = $auth->teknisi->id_teknisi;
        }

        if (!$id_user || !$id_teknisi) {
            return response()->json([
                'status' => false,
                'message' => 'id_user atau id_teknisi tidak valid'
            ], 422);
        }

        // Cari chat EXISTING antara user dan teknisi
        $chat = Chat::firstOrCreate(
            [
                'id_user' => $id_user,
                'id_teknisi' => $id_teknisi
            ],
            [
                'last_message' => null,
                'last_message_at' => null
            ]
        );

        return response()->json([
            'status' => true,
            'chat' => $chat
        ]);
    }



    // list chats for authenticated user (as pelanggan or teknisi)
    // ganti nama method dari list() → listChats()
    public function listChats(Request $request)
    {
        $user = $request->user();

        $query = Chat::with([
            'user',
            'teknisi.user',
            'messages' => function($q){
                $q->latest()->limit(1);
            }
        ]);

        if ($user->role === 'pelanggan') {
            $query->where('id_user', $user->id_user);
        }

        if ($user->role === 'teknisi' && $user->teknisi) {
            $query->where('id_teknisi', $user->teknisi->id_teknisi);
        }

        $chats = $query->orderBy('last_message_at','desc')->get();

        return response()->json([
            'status' => true,
            'data' => $chats
        ]);
    }



    // get messages
    public function messages($id_chat)
    {
        $messages = Message::with('attachments')->where('id_chat',$id_chat)->orderBy('created_at','asc')->get();
        return response()->json(['status'=>true,'messages'=>$messages]);
    }

    // send message (text or file)
    public function send(Request $request)
    {
        $request->validate([
            'id_chat' => 'required|exists:chats,id_chat',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:30720', // 30MB
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            $chat = Chat::findOrFail($request->id_chat);

            $message = new Message();
            $message->id_chat = $chat->id_chat;

            if ($user->id_user) {
                $message->sender_user_id = $user->id_user;
            }

            if (isset($user->teknisi) && $user->teknisi->id_teknisi) {
                $message->sender_teknisi_id = $user->teknisi->id_teknisi;
            }

            // Kalau ada file
            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $mime = $file->getMimeType();

                // Cek jenis file
                if (str_contains($mime, 'image')) {
                    $type = 'image';
                    $folder = 'chat_images';
                } elseif (str_contains($mime, 'video')) {
                    $type = 'video';
                    $folder = 'chat_videos';
                } else {
                    $type = 'file';
                    $folder = 'chat_files';
                }

                $path = $file->store($folder, 'public');

                $message->message = $file->getClientOriginalName();
                $message->type = $type;
                $message->save();

                $attachment = MessageAttachment::create([
                    'id_message' => $message->id_message,
                    'filename' => $file->getClientOriginalName(),
                    'path' => asset('storage/'.$path), // tambahkan ini
                    'mime' => $mime,
                    'size' => $file->getSize(),
                ]);


                // Jika video -> generate thumbnail (opsional, kalau nanti mau)
                if ($type === 'video') {
                    // Kalau mau auto-generate nanti bisa pakai ffmpeg
                    // Untuk sekarang biarkan null, android cukup preview icon + play
                    $attachment->thumbnail = null;
                    $attachment->save();
                }

            } else {
                // CHAT TEXT
                $message->message = $request->message;
                $message->type = 'text';
                $message->save();
            }

            // Update last message preview
            $chat->last_message = 
                $message->type === 'video' ? '[Video]' :
                ($message->type === 'image' ? '[Gambar]' : $message->message);

            $chat->last_message_at = now();
            $chat->save();

            DB::commit();

            broadcast(new MessageSent($message->load('attachments')))->toOthers();

            return response()->json([
                'status' => true,
                'message' => $message->load('attachments'),
                'chat' => $chat
            ]);

            Log::info('REQUEST', $request->all());
            Log::info('USER', ['user'=>$request->user()]);


        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function getPhone($id)
    {
        $user = User::where('id_user', $id)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'no_hp' => $user->no_hp
        ]);

        
    }

    public function detail($id_chat)
    {
        $user = auth()->user();

        $chat = Chat::with(['user', 'teknisi'])
                    ->where('id_chat', $id_chat)
                    ->first();

        if (!$chat) {
            return response()->json([
                'status' => false,
                'message' => 'Chat tidak ditemukan'
            ]);
        }

        // tentukan lawan chat
        if ($chat->id_user == $user->id_user) {
            $other = $chat->teknisi?->user; // ✅ ambil user dari teknisi
        } else {
            $other = $chat->user;          // ✅ langsung user
        }

        if (!$other) {
            return response()->json([
                'status' => false,
                'message' => 'Lawan chat tidak ditemukan',
                'debug' => $chat
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'other_id'    => $other->id_user,
                'other_name'  => $other->nama,
                'other_phone' => $other->no_hp,
                'role'        => $other->role, // ✅ role lawan chat
            ]
        ]);

    }

    public function destroy($id)
    {
        $msg = Message::find($id);

        if (!$msg) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $msg->delete();

        return response()->json([
            'status' => true,
            'message' => 'deleted'
        ]);
    }



    // mark messages as read
    public function markRead(Request $request, $id_chat)
    {
        $user = $request->user();
        Message::where('id_chat',$id_chat)
            ->whereNull('is_read') // or check sender
            ->update(['is_read'=>true]);
        return response()->json(['status'=>true]);
    }
}
