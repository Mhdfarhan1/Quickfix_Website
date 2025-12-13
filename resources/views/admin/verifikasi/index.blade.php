@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <h1 class="text-2xl font-bold mb-0 flex items-center gap-2">
            <i class="fas fa-user-check text-blue-500 text-xl"></i>
            Verifikasi Teknisi
        </h1>

        <p class="text-gray-600 text-xs tracking-wide mb-2">
            Daftar pengajuan verifikasi data diri dan dokumen dari teknisi.
        </p>

        {{-- RINGKASAN STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-amber-700 uppercase">
                    Menunggu Verifikasi (Pending)
                </p>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-2xl font-bold text-amber-900">{{ $pendingCount }}</p>
                    <i class="fas fa-clock text-amber-300 text-2xl"></i>
                </div>
            </div>

            <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-indigo-700 uppercase">
                    Pengajuan Hari Ini
                </p>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-2xl font-bold text-indigo-900">{{ $todayCount }}</p>
                    <i class="fas fa-calendar-day text-indigo-300 text-2xl"></i>
                </div>
            </div>

            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-emerald-700 uppercase">
                    Total Data Masuk
                </p>
                <div class="mt-2 flex items-center justify-between">
                    <p class="text-2xl font-bold text-emerald-900">{{ $totalPengajuan }}</p>
                    <i class="fas fa-folder-open text-emerald-300 text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- ALERT SUKSES / ERROR --}}
        @if(session('success'))
            <div
                class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-600"></i>
                {{ session('error') }}
            </div>
        @endif


        {{-- FILTER BAR --}}
        <form method="GET" class="mt-6 mb-4 flex items-center justify-between gap-3 flex-wrap">
            <div class="relative">
                <select name="entries" onchange="this.form.submit()"
                    class="appearance-none border border-gray-300 rounded-xl bg-white text-sm px-4 py-2 pr-10 shadow-sm">
                    <option value="5" {{ request('entries', $entries) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries', $entries) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries', $entries) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('entries', $entries) == 50 ? 'selected' : '' }}>50</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">▼</div>
            </div>

            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIK..."
                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 w-[220px]">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                    Cari
                </button>
            </div>
        </form>

        {{-- TABEL --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Teknisi</th>
                            <th class="px-6 py-4">Info Bank</th>
                            <th class="px-6 py-4">Masa Aktif SKCK</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($pengajuan as $index => $item)
                            @php
                                $expDate = $item->skck_expired ? \Carbon\Carbon::parse($item->skck_expired) : null;
                                $today = \Carbon\Carbon::now();
                                $diff = $expDate ? $today->diffInDays($expDate, false) : null;
                                $isExpired = $expDate ? $expDate->isPast() : false;
                            @endphp

                            <tr class="hover:bg-blue-50/50">
                                <td class="px-6 py-4">{{ $pengajuan->firstItem() + $index }}</td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold">{{ $item->account_name_verified }}</div>
                                    <div class="text-xs text-gray-500">NIK: {{ $item->nik }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    <div>{{ $item->bank }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->rekening }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($expDate)
                                        <div class="text-xs font-mono">{{ $expDate->format('d M Y') }}</div>
                                        @if($isExpired)
                                            <span class="text-red-600 text-[10px] font-bold">EXPIRED</span>
                                        @elseif($diff <= 30)
                                            <span class="text-amber-600 text-[10px] font-bold">&lt; 30 Hari</span>
                                        @else
                                            <span class="text-green-600 text-[10px] font-bold">Aktif</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'pending')
                                        <span class="text-yellow-600">Pending</span>
                                    @elseif($item->status == 'disetujui')
                                        <span class="text-green-600">Disetujui</span>
                                    @else
                                        <span class="text-red-600">Ditolak</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.verifikasi.show', $item->id) }}"
                                            class="bg-blue-500 text-white px-3 py-2 rounded text-xs">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- 🔔 NOTIFIKASI SKCK (H-30 ATAU EXPIRED) --}}
                                        @if($item->status == 'disetujui' && $expDate && $diff <= 30)
                                            <form method="POST" action="{{ route('admin.verifikasi.notifySkck', $item->id) }}"
                                                onsubmit="return confirm('Kirim notifikasi SKCK ke teknisi ini?')">
                                                @csrf
                                                <button class="bg-amber-500 text-white px-3 py-2 rounded text-xs">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.verifikasi.destroy', $item->id) }}"
                                            onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button class="bg-red-500 text-white px-3 py-2 rounded text-xs">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500">
                                    Belum ada pengajuan verifikasi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION INFO + PREV/NEXT --}}
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">

            {{-- INFO JUMLAH ENTRI --}}
            <div>
                @if($pengajuan->total() > 0)
                    Menampilkan
                    <span class="font-semibold">{{ $pengajuan->firstItem() }}</span>
                    sampai
                    <span class="font-semibold">{{ $pengajuan->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $pengajuan->total() }}</span>
                    pengajuan
                @else
                    Tidak ada entri yang ditampilkan
                @endif
            </div>

            {{-- CUSTOM PREV / NEXT --}}
            <div class="flex items-center gap-2">
                @php
                    $paginator = $pengajuan->appends(request()->except('page'));
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
                        this.form.submit();
                    }, 400);
                });
            }
        });
    </script>
@endsection