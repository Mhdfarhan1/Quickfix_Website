@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            <i class="fas fa-users-cog text-blue-500 text-xl"></i>
            Daftar Teknisi
        </h1>

        <p class="text-gray-600 text-xs tracking-wide mb-8">
            Manajemen data teknisi yang terdaftar dalam sistem.
        </p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTER --}}
        <form method="GET" class="mb-4 flex items-center justify-between gap-3 flex-wrap">
            <div class="relative">
                <select name="entries" onchange="this.form.submit()"
                    class="appearance-none border rounded-xl px-4 py-2 text-sm pr-10">
                    @foreach([5, 10, 25, 50] as $val)
                        <option value="{{ $val }}" {{ request('entries', $entries) == $val ? 'selected' : '' }}>
                            {{ $val }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">▼</div>
            </div>

            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                    class="border rounded-lg px-3 py-2 text-sm w-[180px]">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Cari
                </button>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="overflow-hidden rounded-xl border bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b text-xs uppercase font-bold text-gray-500">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Profil Teknisi</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($teknisis as $i => $teknisi)

                            @php
                                $status = $teknisi->verifikasi->status ?? 'pending';
                            @endphp

                            <tr class="hover:bg-blue-50/50">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $teknisis->firstItem() + $i }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">
                                        {{ $teknisi->user->nama }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $teknisi->user->email }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $teknisi->user->no_hp ?? '-' }}
                                </td>

                                {{-- STATUS DARI verifikasi_teknisi --}}
                                <td class="px-6 py-4 text-center">
                                    @if($status === 'disetujui')
                                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-600">
                                            Disetujui
                                        </span>
                                    @elseif($status === 'ditolak')
                                        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-600">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-600">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.teknisi.destroy', $teknisi->id_teknisi) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus teknisi ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-4 py-2
                                                   text-xs font-medium text-white hover:bg-red-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">
                                    Belum ada data teknisi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION INFO + PREV/NEXT ================================= --}}
<div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">

    {{-- INFO JUMLAH DATA --}}
    <div>
        @if($teknisis->total() > 0)
            Menampilkan
            <span class="font-semibold">{{ $teknisis->firstItem() }}</span>
            sampai
            <span class="font-semibold">{{ $teknisis->lastItem() }}</span>
            dari
            <span class="font-semibold">{{ $teknisis->total() }}</span>
            teknisi
        @else
            Tidak ada entri yang ditampilkan
        @endif
    </div>

    {{-- PREV / NEXT --}}
    <div class="flex items-center gap-2">
        @php
            $paginator = $teknisis->appends(request()->except('page'));
        @endphp

        {{-- Previous --}}
        <a href="{{ $paginator->previousPageUrl() ?: '#' }}"
           class="px-3 py-1 rounded-lg border text-xs
           {{ $paginator->onFirstPage()
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                : 'bg-white hover:bg-gray-100' }}">
            Previous
        </a>

        {{-- Page info --}}
        <span class="text-xs">
            Halaman
            <span class="font-semibold">{{ $paginator->currentPage() }}</span>
            /
            <span class="font-semibold">{{ $paginator->lastPage() }}</span>
        </span>

        {{-- Next --}}
        <a href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}"
           class="px-3 py-1 rounded-lg border text-xs
           {{ $paginator->hasMorePages()
                ? 'bg-white hover:bg-gray-100'
                : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
            Next
        </a>
    </div>

</div>

@endsection