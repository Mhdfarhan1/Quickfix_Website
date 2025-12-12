@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-1 flex items-center gap-2">
            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
            Pemesanan Selesai
        </h1>

        <p class="text-gray-600 text-xs tracking-wide mb-4">
            Riwayat transaksi yang status pekerjaannya telah dinyatakan selesai (Menunggu Verifikasi, Terkonfirmasi &
            Selesai).
        </p>

        {{-- FILTER BAR --}}
        <form method="GET" class="mb-4 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <select name="entries" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-xl bg-white text-sm px-4 py-2 pr-10
                                               shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring focus:ring-blue-200/40
                                               transition-all cursor-pointer font-medium">
                        <option value="5" {{ request('entries', $entries ?? 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('entries', $entries ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('entries', $entries ?? 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('entries', $entries ?? 10) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">▼</div>
                </div>

                {{-- Dropdown Status --}}
                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-xl bg-white text-sm px-4 py-2 pr-10
                                                   shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring focus:ring-blue-200/40
                                                   transition-all cursor-pointer font-medium">
                        <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="selesai_confirmed" {{ request('status') == 'selesai_confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                        <option value="selesai_pending_verifikasi" {{ request('status') == 'selesai_pending_verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">▼</div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari kode / pelanggan / teknisi..."
                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 w-[220px]">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Cari
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead
                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Teknisi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Harga</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pemesanan as $index => $item)
                            <tr class="hover:bg-blue-50/50 transition duration-200 ease-in-out">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $pemesanan->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $item->kode_pemesanan }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-800">{{ $item->nama_pelanggan }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->alamat_lengkap ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $item->nama_teknisi ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->nama_keahlian }}</span>
                                    </div>
                                </td>

                                {{-- LOGIKA STATUS LABEL FULL --}}
                                <td class="px-6 py-4">
                                    @if($item->status == 'selesai_confirmed')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 border border-green-200">
                                            <i class="fas fa-check-double text-[10px]"></i> Terkonfirmasi
                                        </span>

                                    @elseif($item->status == 'selesai')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-check text-[10px]"></i> Selesai
                                        </span>

                                    @elseif($item->status == 'selesai_pending_verifikasi')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 border border-yellow-200">
                                            <i class="fas fa-clock text-[10px]"></i> Verifikasi
                                        </span>

                                    @elseif($item->status == 'batal')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 border border-red-200">
                                            <i class="fas fa-times-circle text-[10px]"></i> Batal
                                        </span>

                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-col">
                                        <span>{{ $item->tanggal_booking }}</span>
                                        <span class="text-xs text-gray-400">{{ $item->jam_booking }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>

                                {{-- KOLOM AKSI (REFUND) --}}
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'batal')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-medium border border-red-100">
                                            <i class="fas fa-ban"></i> Sudah Dibatalkan
                                        </span>

                                    @elseif($item->status == 'selesai')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-100">
                                            <i class="fas fa-check"></i> Sudah Selesai
                                        </span>

                                    @else
                                        <form action="{{ route('admin.pemesanan.refund') }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin melakukan refund untuk pesanan ini?');">
                                            @csrf
                                            <input type="hidden" name="id_pemesanan" value="{{ $item->id_pemesanan }}">

                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 border border-red-200 rounded-lg text-xs font-medium transition-colors">
                                                <i class="fas fa-undo-alt"></i> Refund
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500 bg-gray-50">
                                    Tidak ada pemesanan selesai.
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
                    Menampilkan <span class="font-semibold">{{ $pemesanan->firstItem() }}</span>
                    sampai <span class="font-semibold">{{ $pemesanan->lastItem() }}</span>
                    dari <span class="font-semibold">{{ $pemesanan->total() }}</span> pemesanan selesai
                @else
                    Tidak ada entri yang ditampilkan
                @endif
            </div>

            {{-- Custom Prev / Next --}}
            <div class="flex items-center gap-2">
                @php
                    $paginator = $pemesanan->appends(request()->except('page'));
                @endphp

                <a href="{{ $paginator->previousPageUrl() ?: '#' }}"
                    class="px-3 py-1 rounded-lg border text-xs
                                          {{ $paginator->onFirstPage() ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-100' }}">
                    Previous
                </a>

                <span class="text-xs">
                    Halaman <span class="font-semibold">{{ $paginator->currentPage() }}</span> / <span
                        class="font-semibold">{{ $paginator->lastPage() }}</span>
                </span>

                <a href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}"
                    class="px-3 py-1 rounded-lg border text-xs
                                          {{ $paginator->hasMorePages() ? 'bg-white hover:bg-gray-100' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                    Next
                </a>

                {{-- Jump-to-page dropdown (preserve current query params) --}}
                <form id="jump-to-page" method="GET" action="{{ url()->current() }}" class="inline-block ml-2">
                    @foreach(request()->except('page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <label for="jump_page" class="sr-only">Pilih Halaman</label>
                    <select id="jump_page" name="page"
                        onchange="this.form.submit()"
                        class="ml-2 px-3 py-1 rounded-lg border text-sm bg-white">
                        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                            <option value="{{ $i }}" {{ $paginator->currentPage() == $i ? 'selected' : '' }}>
                                Halaman {{ $i }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>
        </div>
    </div>
@endsection
