@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4 lg:px-0 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-image text-blue-600"></i>
                    Kelola Banner Promosi
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan dan kelola banner promosi untuk aplikasi QuickFix.
                </p>
            </div>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- FORM TAMBAH BANNER --}}
        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6 md:p-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-plus text-blue-600"></i>
                Tambah Banner Baru
            </h2>

            <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-3 gap-6">

                    {{-- INPUT --}}
                    <div class="md:col-span-2 space-y-4">

                        {{-- Judul --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Banner *</label>
                            <input type="text" name="judul"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
                                value="{{ old('judul') }}" required placeholder="Contoh: Promo Akhir Tahun">
                        </div>

                        {{-- Link --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Link (opsional)</label>
                            <input type="url" name="link"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
                                value="{{ old('link') }}" placeholder="https://quickfix.com/promo">
                            <p class="text-[11px] text-gray-400 mt-1">Jika diisi, banner bisa diklik.</p>
                        </div>

                        {{-- File Gambar --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner *</label>
                            <input type="file" name="gambar" id="bannerInput" accept="image/*"
                                class="w-full border rounded-lg px-3 py-2 text-sm cursor-pointer focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
                                required>
                            <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 5MB.</p>
                        </div>
                    </div>

                    {{-- PREVIEW --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Preview</label>

                        <div class="w-full h-32 md:h-40 rounded-xl border border-dashed border-gray-300 bg-gray-50 
                                        flex items-center justify-center overflow-hidden" id="bannerPreviewWrapper">
                            <span id="bannerPreviewText" class="text-xs text-gray-400 text-center px-4">
                                Pilih gambar untuk melihat preview banner di sini.
                            </span>
                            <img id="bannerPreviewImage" src="#" alt="Preview"
                                class="hidden w-full h-full object-cover rounded-xl">
                        </div>
                    </div>

                </div>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm">
                    Simpan Banner
                </button>
            </form>
        </div>


        {{-- LIST BANNER --}}
        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6 md:p-8">

            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-images text-blue-600"></i>
                Daftar Banner
            </h2>

            @if($banners->isEmpty())
                <div class="border border-dashed border-gray-300 rounded-xl py-10 text-center">
                    <p class="text-sm text-gray-500 mb-1">Belum ada banner.</p>
                    <p class="text-xs text-gray-400">Tambah banner pertama Anda di atas.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($banners as $banner)
                        <div class="border rounded-xl overflow-hidden shadow-sm bg-white group hover:shadow-lg transition">

                            {{-- Thumbnail --}}
                            <div class="relative h-32 bg-gray-100">
                                <img src="{{ asset('storage/' . $banner->gambar) }}" class="w-full h-full object-cover"
                                    alt="banner">

                                {{-- Status --}}
                                <div class="absolute top-2 left-2">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[11px] font-medium 
                                                            {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                        {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-3 flex flex-col gap-1">
                                <div class="font-semibold text-gray-800 text-sm line-clamp-2">
                                    {{ $banner->judul }}
                                </div>

                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank"
                                        class="text-[11px] text-blue-600 hover:underline break-all inline-flex items-center gap-1">
                                        <i class="fa-solid fa-link text-[10px]"></i>
                                        {{ $banner->link }}
                                    </a>
                                @else
                                    <span class="text-[11px] text-gray-400">Tanpa link</span>
                                @endif

                                {{-- Footer --}}
                                <div class="flex items-center justify-between mt-2 pt-1 border-t border-gray-100">
                                    <span class="text-[10px] text-gray-400">
                                        {{ $banner->created_at->format('d M Y') }}
                                    </span>

                                    <div class="flex items-center gap-2">

                                        {{-- Tombol Aktif / Nonaktif --}}
                                        <form action="{{ route('admin.banner.toggle', $banner) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="px-3 py-1 text-[11px] rounded-lg
                            {{ $banner->is_active
                        ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100'
                        : 'bg-green-50 text-green-700 hover:bg-green-100' }}">

                                                <i
                                                    class="fa-solid {{ $banner->is_active ? 'fa-ban' : 'fa-check' }} text-[10px]"></i>

                                                {{ $banner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.banner.destroy', $banner) }}" method="POST"
                                            onsubmit="return confirm('Hapus banner ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 text-[11px] rounded-lg bg-red-50 text-red-600 hover:bg-red-100 inline-flex items-center gap-1">
                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </div>


                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('bannerInput');
            const previewImg = document.getElementById('bannerPreviewImage');
            const previewText = document.getElementById('bannerPreviewText');

            input.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) {
                    previewImg.classList.add('hidden');
                    previewText.classList.remove('hidden');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    previewText.classList.add('hidden');
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush