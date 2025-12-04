<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderByDesc('created_at')->get();

        return view('admin.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'link'  => ['nullable', 'url'],
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // simpan file ke storage/app/public/banner
        $path = $request->file('gambar')->store('banner', 'public');

        Banner::create([
            'judul'     => $data['judul'],
            'link'      => $data['link'] ?? null,
            'gambar'    => $path,
            'is_active' => true,
        ]);

        return back()->with('success', 'Banner berhasil ditambahkan.');
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'link'  => ['nullable', 'url'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $banner->judul = $data['judul'];
        $banner->link  = $data['link'] ?? null;
        $banner->is_active = $request->boolean('is_active');

        if ($request->hasFile('gambar')) {
            // hapus file lama
            if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
                Storage::disk('public')->delete($banner->gambar);
            }

            $banner->gambar = $request->file('gambar')->store('banner', 'public');
        }

        $banner->save();

        return back()->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
            Storage::disk('public')->delete($banner->gambar);
        }

        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }
    public function toggle(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return back()->with('success', 'Status banner berhasil diperbarui.');
    }
}
