<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    /**
     * Ambil admin yang sedang login.
     * Sudah di-protect oleh middleware admin.auth (cek session('admin_id')).
     */
    protected function currentAdmin(): Admin
    {
        $adminId = session('admin_id');

        if (!$adminId) {
            abort(403, 'Admin belum login.');
        }

        // Kalau primary key model Admin = id_admin, ini tetap aman
        // karena Admin::find($adminId) akan pakai primary key itu.
        $admin = Admin::find($adminId);

        if (!$admin) {
            abort(403, 'Admin tidak ditemukan.');
        }

        return $admin;
    }

    /**
     * Tampilkan profil admin.
     */
    public function show()
    {
        $admin = $this->currentAdmin();

        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Tampilkan form edit profil.
     */
    public function edit()
    {
        $admin = $this->currentAdmin();

        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update profil (nama, email, foto).
     */
    public function update(Request $request)
    {
        $admin = $this->currentAdmin();

        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('admin_users', 'email')->ignore($admin->getKey(), $admin->getKeyName()),
            ],
            'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $admin->nama = $request->input('nama');
        $admin->email = $request->input('email');

        // handle upload foto
        if ($request->hasFile('foto_profile')) {
            $file = $request->file('foto_profile');
            $path = $file->store('admin_profiles', 'public');

            // hapus foto lama kalau ada
            if (!empty($admin->foto_profile) && Storage::disk('public')->exists($admin->foto_profile)) {
                Storage::disk('public')->delete($admin->foto_profile);
            }

            $admin->foto_profile = $path;
        }

        $admin->save();

        // ✅ JANGAN ubah admin_id di session. Cukup update data tampilannya saja.
        session()->put('admin_nama', $admin->nama);
        session()->put('admin_email', $admin->email);
        session()->put('admin_foto', $admin->foto_profile);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password admin.
     */
    public function updatePassword(Request $request)
    {
        $admin = $this->currentAdmin();

        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!$admin->password || !Hash::check($request->input('current_password'), $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak cocok.',
            ]);
        }

        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        // Refresh data non-ID di session (admin_id tetap sama)
        session()->put('admin_nama', $admin->nama);
        session()->put('admin_email', $admin->email);
        session()->put('admin_foto', $admin->foto_profile);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success_password', 'Password berhasil diubah.');
    }
}
