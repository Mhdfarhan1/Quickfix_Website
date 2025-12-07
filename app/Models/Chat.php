<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
    protected $primaryKey = 'id_chat';
    protected $fillable = ['id_user','id_teknisi','last_message','last_message_at'];

    public function messages() {
        return $this->hasMany(Message::class, 'id_chat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }
}
