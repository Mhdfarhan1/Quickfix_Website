@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- TITLE --}}
    <h1 class="text-2xl font-bold mb-1 flex items-center gap-2">
        <i class="fas fa-circle-info text-blue-600 text-xl"></i>
        Keluhan & Laporan Pengguna
    </h1>

    <p class="text-gray-600 text-xs tracking-wide mb-4">
        Daftar laporan yang dikirim pengguna dari aplikasi mobile.
    </p>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 mb-3">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- FILTER BAR ================================================ --}}
    <form method="GET" class="mb-4 flex items-center justify-between gap-3 flex-wrap">

        {{-- ENTRIES --}}
        <div class="relative">
            <select name="entries" onchange="this.form.submit()"
                class="appearance-none border border-gray-300 rounded-xl bg-white text-sm px-4 py-2 pr-10
                       shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring focus:ring-blue-200/40
                       transition-all cursor-pointer font-medium">
                <option value="5"  {{ request('entries', $perPage ?? 10) == 5  ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('entries', $perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('entries', $perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('entries', $perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                ▼
            </div>
        </div>

        {{-- SEARCH --}}
        <div class="flex items-center gap-2">
            <input type="text" 
                name="search"
                id="searchInput"
                value="{{ request('search') }}"
                placeholder="Cari user / kategori / masalah..."
                class="border border-gray-300 rounded-lg text-sm px-3 py-2 w-[220px] focus:ring-blue-300 focus:border-blue-500">

            <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                Cari
            </button>
        </div>
    </form>

    {{-- TABLE WRAPPER =============================================== --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Jenis Masalah</th>
                        <th class="px-6 py-4 text-right">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                @forelse ($complaints as $index => $c)
                    <tr class="hover:bg-blue-50/50 transition duration-200 ease-in-out">

                        {{-- NO --}}
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $complaints->firstItem() + $index }}
                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-6 py-4">
                            {{ $c->created_at?->format('d M Y H:i') ?? '-' }}
                        </td>

                        {{-- USER --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $c->user->nama ?? '-' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $c->user->email ?? '-' }}
                                </span>
                            </div>
                        </td>

                        {{-- KATEGORI --}}
                        <td class="px-6 py-4 capitalize">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @class([
                                    'bg-blue-100 text-blue-700' => $c->kategori === 'pesanan',
                                    'bg-amber-100 text-amber-700' => $c->kategori === 'pembayaran',
                                    'bg-purple-100 text-purple-700' => $c->kategori === 'aplikasi',
                                    'bg-rose-100 text-rose-700' => $c->kategori === 'akun'
                                ])">
                                {{ $c->kategori }}
                            </span>
                        </td>

                        {{-- JENIS MASALAH --}}
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <span class="font-semibold text-gray-800">{{ $c->jenis_masalah }}</span>
                                <p class="text-xs text-gray-500 line-clamp-1">{{ $c->deskripsi }}</p>
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4 text-right">
                            @php
                                $color = [
                                    'baru' => 'bg-blue-100 text-blue-700',
                                    'diproses' => 'bg-amber-100 text-amber-700',
                                    'selesai' => 'bg-green-100 text-green-700',
                                    'ditolak' => 'bg-red-100 text-red-700'
                                ][$c->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>

                        {{-- AKSI --}}
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.complaints.show', $c->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                Detail
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500 bg-gray-50">
                            Belum ada laporan yang masuk.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- PAGINATION INFO + PREV/NEXT ================================= --}}
    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">

        {{-- INFO --}}
        <div>
            @if($complaints->total() > 0)
                Menampilkan
                <span class="font-semibold">{{ $complaints->firstItem() }}</span>
                sampai
                <span class="font-semibold">{{ $complaints->lastItem() }}</span>
                dari
                <span class="font-semibold">{{ $complaints->total() }}</span>
                laporan
            @else
                Tidak ada entri yang ditampilkan
            @endif
        </div>

        {{-- PREV/NEXT --}}
        @php $paginator = $complaints->appends(request()->except('page')); @endphp

        <div class="flex items-center gap-2">

            {{-- PREVIOUS --}}
            <a href="{{ $paginator->previousPageUrl() ?: '#' }}"
                class="px-3 py-1 rounded-lg border text-xs 
                {{ $paginator->onFirstPage()
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-white hover:bg-gray-100' }}">
                Previous
            </a>

            {{-- CURRENT PAGE --}}
            <span class="text-xs">
                Halaman <span class="font-semibold">{{ $paginator->currentPage() }}</span>
                / <span class="font-semibold">{{ $paginator->lastPage() }}</span>
            </span>

            {{-- NEXT --}}
            <a href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}"
                class="px-3 py-1 rounded-lg border text-xs
                {{ $paginator->hasMorePages()
                    ? 'bg-white hover:bg-gray-100'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                Next
            </a>
        </div>
    </div>

</div>

{{-- AUTO SEARCH --}}
<script>
    let typingTimer;
    const input = document.getElementById("searchInput");

    input.addEventListener("input", function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            this.form.submit();
        }, 350);
    });
</script>
@endsection
