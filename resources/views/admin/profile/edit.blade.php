@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-start pt-10 pb-20">
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg border border-gray-200 p-8">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil</h1>

            {{-- flash messages --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('success_password'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-100 text-green-700 rounded-lg">
                    {{ session('success_password') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tabs --}}
            <div class="mb-6">
                <nav class="inline-flex rounded-lg bg-gray-100 p-1 gap-1" role="tablist">
                    <button id="tab-profile" class="px-4 py-2 rounded-lg text-sm font-medium bg-white shadow-sm"
                        type="button">Profil</button>
                    <button id="tab-password"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-800"
                        type="button">Ubah Password</button>
                </nav>
            </div>

            {{-- Tab content --}}
            <div id="tabContents">

                {{-- PROFILE FORM --}}
                <div id="content-profile">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT') {{-- ⬅️ WAJIB, karena route-nya PUT /admin/profile --}}

                        <div class="flex items-center gap-6">
                            <div class="relative">
                                <img id="previewAvatar"
                                    src="{{ $admin->foto_profile ? asset('storage/' . $admin->foto_profile) : 'https://ui-avatars.com/api/?name=' . urlencode($admin->nama) . '&background=0D8ABC&color=fff' }}"
                                    alt="avatar" class="w-24 h-24 rounded-full object-cover shadow">
                                @if($admin->is_active)
                                    <span
                                        class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                                @endif
                            </div>

                            <div class="flex-1">
                                <label class="block text-sm text-gray-600">Upload Foto Profil (jpg, png, webp)</label>
                                <div class="mt-2 flex items-center gap-3">
                                    <label
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-white border rounded-lg shadow-sm text-sm cursor-pointer hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m10 12V4M3 20h18" />
                                        </svg>
                                        <span class="text-sm text-gray-700">Pilih Foto</span>
                                        <input id="fotoInput" type="file" name="foto_profile" accept="image/*"
                                            class="hidden">
                                    </label>

                                    <button id="removePreviewBtn" type="button"
                                        class="px-3 py-2 text-sm text-gray-500 border rounded-lg hover:bg-gray-50">Reset</button>
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Max 2MB. Jika tidak diganti, foto lama tetap
                                    digunakan.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- NAMA LENGKAP --}}
                            <div class="space-y-1 relative">
                                <span class="absolute -top-2 left-4 bg-white px-2 text-xs font-medium text-gray-500 z-10">
                                    Nama Lengkap
                                </span>

                                <div class="relative">
                                    <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}" class="w-full px-3 py-3 pl-4 border rounded-xl bg-gray-50
                                  focus:bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition">
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="space-y-1 relative">
                                <span class="absolute -top-2 left-4 bg-white px-2 text-xs font-medium text-gray-500 z-10">
                                    Email
                                </span>

                                <div class="relative">
                                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full px-3 py-3 pl-4 border rounded-xl bg-gray-50
                                  focus:bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition">
                                </div>
                            </div>

                        </div>
                        <div class="flex justify-end gap-3">

                            {{-- Tombol Batal --}}
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-4 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition">
                                <i class="fa-solid fa-xmark text-gray-500"></i>
                                Batal
                            </a>

                            {{-- Tombol Simpan --}}
                            <button type="submit"
                                class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 flex items-center gap-2 transition">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Perubahan
                            </button>

                        </div>
                    </form>
                </div>

                {{-- PASSWORD FORM --}}
                <div id="content-password" class="hidden">
                    <form action="{{ route('admin.profile.password.update') }}" method="POST"
                        class="space-y-6 bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                        @csrf
                        @method('PUT') {{-- ⬅️ route-nya PUT /admin/profile/password --}}

                        {{-- HEADER --}}
                        <div
                            class="pb-4 border-b border-gray-200 flex items-center gap-4 bg-gradient-to-r from-blue-50 to-transparent p-3 rounded-lg">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-600/10 backdrop-blur-sm flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-lock text-blue-600 text-xl"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Ubah Password</h2>
                                <p class="text-sm text-gray-500">Perbarui keamanan akun Anda</p>
                            </div>
                        </div>

                        {{-- PASSWORD SAAT INI --}}
                        <div class="space-y-1 relative">
                            <span class="absolute -top-2 left-4 bg-white px-2 text-xs font-medium text-gray-500 z-10">
                                Password Saat Ini
                            </span>

                            <div class="relative">
                                <input type="password" name="current_password" autocomplete="current-password" class="peer w-full px-3 py-3 pl-10 border rounded-xl bg-gray-50
                                   focus:bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition">
                                <i class="fa-solid fa-key absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        {{-- PASSWORD BARU --}}
                        <div class="space-y-1 relative">
                            <span class="absolute -top-2 left-4 bg-white px-2 text-xs font-medium text-gray-500 z-10">
                                Password Baru
                            </span>

                            <div class="relative">
                                <input type="password" name="password" autocomplete="new-password" class="peer w-full px-3 py-3 pl-10 border rounded-xl bg-gray-50
                                   focus:bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition">
                                <i class="fa-solid fa-key absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        {{-- KONFIRMASI PASSWORD --}}
                        <div class="space-y-1 relative">
                            <span class="absolute -top-2 left-4 bg-white px-2 text-xs font-medium text-gray-500 z-10">
                                Konfirmasi Password
                            </span>

                            <div class="relative">
                                <input type="password" name="password_confirmation" autocomplete="new-password" class="peer w-full px-3 py-3 pl-10 border rounded-xl bg-gray-50
                                   focus:bg-white focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition">
                                <i class="fa-solid fa-shield-halved absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>


                        {{-- BUTTON ACTION --}}
                        <div class="flex justify-end gap-3 pt-2">

                            {{-- BACK BUTTON --}}
                            <a href="{{ route('admin.profile.show') }}" class="px-4 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50
                                           flex items-center gap-2 transition">
                                <i class="fa-solid fa-arrow-left text-gray-500 text-sm"></i>
                                Kembali
                            </a>

                            {{-- SAVE BUTTON --}}
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700
                                           flex items-center gap-2 transition shadow">
                                <i class="fa-solid fa-key text-white text-sm"></i>
                                Ubah Password
                            </button>

                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tabs
            const tabProfileBtn = document.getElementById('tab-profile');
            const tabPasswordBtn = document.getElementById('tab-password');
            const contentProfile = document.getElementById('content-profile');
            const contentPassword = document.getElementById('content-password');

            function activateProfileTab() {
                tabProfileBtn.classList.add('bg-white', 'shadow-sm');
                tabProfileBtn.classList.remove('text-gray-600');
                tabPasswordBtn.classList.remove('bg-white', 'shadow-sm');
                tabPasswordBtn.classList.add('text-gray-600');
                contentProfile.classList.remove('hidden');
                contentPassword.classList.add('hidden');
            }
            function activatePasswordTab() {
                tabPasswordBtn.classList.add('bg-white', 'shadow-sm');
                tabPasswordBtn.classList.remove('text-gray-600');
                tabProfileBtn.classList.remove('bg-white', 'shadow-sm');
                tabProfileBtn.classList.add('text-gray-600');
                contentPassword.classList.remove('hidden');
                contentProfile.classList.add('hidden');
            }

            tabProfileBtn.addEventListener('click', activateProfileTab);
            tabPasswordBtn.addEventListener('click', activatePasswordTab);

            // default tab
            activateProfileTab();

            // Image preview logic
            const fotoInput = document.getElementById('fotoInput');
            const previewAvatar = document.getElementById('previewAvatar');
            const removePreviewBtn = document.getElementById('removePreviewBtn');
            const originalSrc = previewAvatar.src;

            fotoInput?.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) {
                    previewAvatar.src = originalSrc;
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (ev) {
                    previewAvatar.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });

            removePreviewBtn?.addEventListener('click', function () {
                if (fotoInput) {
                    fotoInput.value = '';
                }
                previewAvatar.src = originalSrc;
            });
        });
    </script>
@endpush