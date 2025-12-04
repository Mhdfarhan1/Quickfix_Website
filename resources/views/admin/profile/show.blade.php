@extends('layouts.app')

@section('content')
<div class="flex justify-center items-start pt-10 pb-20">

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 w-full max-w-2xl">

        {{-- TITLE --}}
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">
            Profil Admin
        </h1>

        {{-- PROFILE HEADER --}}
        <div class="flex flex-col items-center">

            {{-- FOTO PROFIL --}}
            <div class="relative">
                <img src="{{ $admin->foto_profile ? asset('storage/'.$admin->foto_profile) : 'https://ui-avatars.com/api/?name='.urlencode($admin->nama).'&background=0D8ABC&color=fff' }}"
                     class="w-32 h-32 rounded-full object-cover shadow-lg border border-gray-100">

                {{-- STATUS --}}
                @if($admin->is_active)
                    <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full shadow"></span>
                @endif
            </div>

            {{-- NAMA & EMAIL --}}
            <h2 class="mt-4 text-2xl font-semibold text-gray-800">{{ $admin->nama }}</h2>
            <p class="text-gray-500 text-sm">{{ $admin->email }}</p>
        </div>

        <hr class="my-8">

        {{-- DETAIL INFORMASI --}}
        <div class="space-y-6">

            <div class="flex flex-col">
                <span class="text-gray-500 text-sm">Nama Lengkap</span>
                <span class="text-gray-800 font-medium text-lg">{{ $admin->nama }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-500 text-sm">Email</span>
                <span class="text-gray-800 font-medium text-lg">{{ $admin->email }}</span>
            </div>

            <div class="flex flex-col">
                <span class="text-gray-500 text-sm">Status Akun</span>
                @if($admin->is_active)
                    <span class="inline-block mt-1 px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium w-fit">
                        Aktif
                    </span>
                @else
                    <span class="inline-block mt-1 px-3 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium w-fit">
                        Tidak Aktif
                    </span>
                @endif
            </div>

        </div>

        {{-- BUTTON EDIT --}}
        <div class="mt-10 text-center space-y-3">

            {{-- Tombol Edit Profil --}}
            <a href="{{ route('admin.profile.edit') }}"
               class="px-6 py-2.5 rounded-xl bg-blue-600 text-white shadow-md hover:bg-blue-700 active:scale-95 transition font-medium inline-flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-sm"></i>
                Edit Profil
            </a>

            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 active:scale-95 transition font-medium inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-sm"></i>
                Kembali
            </a>

        </div>

    </div>

</div>
@endsection
