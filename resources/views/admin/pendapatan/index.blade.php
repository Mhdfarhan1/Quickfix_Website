@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        <h1 class="text-2xl font-bold mb-0 flex items-center gap-2">
            <i class="fas fa-wallet text-blue-500 text-xl"></i>
            Pendapatan Admin
        </h1>

        <p class="text-gray-600 text-xs tracking-wide mb-2">
            Ringkasan fee admin dari setiap transaksi yang telah selesai.
        </p>


        {{-- RINGKASAN PENDAPATAN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Total Pendapatan Admin --}}
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-emerald-700">
                    Total Pendapatan Admin ({{ $adminFeePercent }}%)
                </p>
                <p class="mt-2 text-2xl font-bold text-emerald-900">
                    Rp {{ number_format($totalPendapatanAdmin, 0, ',', '.') }}
                </p>
            </div>

            {{-- Pendapatan Bulan Ini --}}
            <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-indigo-700">
                    Pendapatan Admin Bulan Ini
                </p>
                <p class="mt-2 text-2xl font-bold text-indigo-900">
                    Rp {{ number_format($pendapatanAdminBulanIni, 0, ',', '.') }}
                </p>
            </div>

            {{-- Pendapatan Hari Ini --}}
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-amber-700">
                    Pendapatan Admin Hari Ini
                </p>
                <p class="mt-2 text-2xl font-bold text-amber-900">
                    Rp {{ number_format($pendapatanAdminHariIni, 0, ',', '.') }}
                </p>
            </div>

        </div>

        {{-- FILTER BAR (ENTRIES + SEARCH) --}}
        <form method="GET" class="mt-6 mb-4 flex items-center justify-between gap-3 flex-wrap">
            <div class="relative">
                <select name="entries" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-xl bg-white text-sm px-4 py-2 pr-10
                                       shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring focus:ring-blue-200/40
                                       transition-all cursor-pointer font-medium">
                    <option value="5" {{ request('entries', $entries) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries', $entries) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries', $entries) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('entries', $entries) == 50 ? 'selected' : '' }}>50</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                    ▼
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode / pelanggan / layanan..."
                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 w-[220px]">
                {{-- Tombol ini opsional, tetap ada jika ingin manual klik --}}
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Cari
                </button>
            </div>
        </form>

        {{-- TABEL DETAIL PENDAPATAN --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead
                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Layanan</th>
                            <th class="px-6 py-4 text-right">Harga</th>
                            <th class="px-6 py-4 text-right">Fee Admin ({{ $adminFeePercent }}%)</th>
                            <th class="px-6 py-4 text-right">Untuk Teknisi</th>
                            <th class="px-6 py-4">Tanggal Update</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pemesanan as $index => $item)
                            <tr class="hover:bg-blue-50/50 transition duration-200 ease-in-out">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $pemesanan->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $item->kode_pemesanan }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ $item->pelanggan->nama ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $item->keahlian->nama_keahlian ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-emerald-700">
                                    Rp {{ number_format($item->admin_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right text-gray-700">
                                    Rp {{ number_format($item->teknisi_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $item->updated_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500 bg-gray-50">
                                    Belum ada pendapatan admin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination info + Prev/Next --}}
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">

            {{-- Info jumlah entri --}}
            <div>
                @if($pemesanan->total() > 0)
                    Menampilkan
                    <span class="font-semibold">{{ $pemesanan->firstItem() }}</span>
                    sampai
                    <span class="font-semibold">{{ $pemesanan->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $pemesanan->total() }}</span>
                    transaksi
                @else
                    Tidak ada entri yang ditampilkan
                @endif
            </div>

            {{-- Custom Prev / Next --}}
            <div class="flex items-center gap-2">
                @php
                    $paginator = $pemesanan->appends(request()->except('page'));
                @endphp

                <a href="{{ $paginator->previousPageUrl() ?: '#' }}" class="px-3 py-1 rounded-lg border text-xs
                                      {{ $paginator->onFirstPage()
        ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
        : 'bg-white hover:bg-gray-100' }}">
                    Previous
                </a>

                <span class="text-xs">
                    Halaman
                    <span class="font-semibold">{{ $paginator->currentPage() }}</span>
                    /
                    <span class="font-semibold">{{ $paginator->lastPage() }}</span>
                </span>

                <a href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}" class="px-3 py-1 rounded-lg border text-xs
                                      {{ $paginator->hasMorePages()
        ? 'bg-white hover:bg-gray-100'
        : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                    Next
                </a>
            </div>
        </div>

        {{-- Pagination default (opsional) --}}
        <div class="mt-2">
            {{ $pemesanan->links() }}
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector("input[name='search']");
            let typingDelay;

            if (searchInput) {
                searchInput.addEventListener("keyup", function () {
                    clearTimeout(typingDelay);
                    typingDelay = setTimeout(() => {
                        this.form.submit(); // auto reload dengan kata kunci baru
                    }, 400); // jeda 400ms setelah berhenti mengetik
                });
            }
        });
    </script>
@endsection