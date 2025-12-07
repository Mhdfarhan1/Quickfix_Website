@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-4">

        {{-- HEADER + BACK --}}
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold mb-1 flex items-center gap-2">
                    <i class="fas fa-circle-info text-blue-600 text-xl"></i>
                    Detail Keluhan Pengguna
                </h1>

                <p class="text-gray-600 text-xs tracking-wide">
                    Laporan dari pengguna terkait pesanan, pembayaran, aplikasi, atau akun.
                </p>
            </div>

            <a href="{{ route('admin.complaints.index') }}"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border text-xs text-gray-600 hover:bg-gray-100">
                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                Kembali
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- GRID: DETAIL (KIRI) + TINDAKAN ADMIN (KANAN) --}}
        <div class="grid md:grid-cols-3 gap-4">

            {{-- KIRI: DETAIL LAPORAN --}}
            <div class="md:col-span-2 space-y-4">

                {{-- CARD UTAMA --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-md p-5 space-y-4">

                    {{-- HEADER CARD --}}
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Laporan #{{ $complaint->id }}
                            </h2>
                            <p class="text-xs text-gray-500">
                                Dibuat pada {{ $complaint->created_at?->format('d M Y H:i') ?? '-' }}
                            </p>
                        </div>

                        @php
                            $statusColor = [
                                'baru' => 'bg-blue-100 text-blue-700',
                                'diproses' => 'bg-amber-100 text-amber-700',
                                'selesai' => 'bg-green-100 text-green-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                            ][$complaint->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp

                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold {{ $statusColor }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </div>

                    {{-- INFO USER & KATEGORI --}}
                    <div class="grid sm:grid-cols-2 gap-3 text-sm text-gray-700">
                        <div class="space-y-1">
                            <p>
                                <span class="font-semibold">User:</span>
                                {{ $complaint->user->nama ?? '-' }}
                            </p>
                            @if($complaint->user?->email)
                                <p>
                                    <span class="font-semibold">Email:</span>
                                    {{ $complaint->user->email }}
                                </p>
                            @endif
                            <p>
                                <span class="font-semibold">ID User:</span>
                                {{ $complaint->user_id }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p>
                                <span class="font-semibold">Kategori:</span>
                                {{ ucfirst($complaint->kategori) }}
                            </p>
                            <p>
                                <span class="font-semibold">Jenis Masalah:</span>
                                {{ $complaint->jenis_masalah }}
                            </p>
                            @if($complaint->updated_at)
                                <p>
                                    <span class="font-semibold">Terakhir diperbarui:</span>
                                    {{ $complaint->updated_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- INFORMASI PESANAN (JIKA ADA) --}}
                    @if($complaint->nomor_pesanan || $complaint->pemesanan)
                        <div class="border-t border-gray-100 pt-3 text-sm text-gray-700 space-y-1">
                            <h3 class="font-semibold mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-box-open text-blue-600 text-xs"></i>
                                Informasi Pesanan
                            </h3>
                            @if($complaint->nomor_pesanan)
                                <p>
                                    <span class="font-semibold">Nomor / Kode Pesanan:</span>
                                    {{ $complaint->nomor_pesanan }}
                                </p>
                            @endif
                            @if($complaint->pemesanan)
                                <p>
                                    <span class="font-semibold">ID Pemesanan:</span>
                                    {{ $complaint->pemesanan->id_pemesanan ?? $complaint->pemesanan->id ?? '-' }}
                                </p>
                                @if(isset($complaint->pemesanan->status))
                                    <p>
                                        <span class="font-semibold">Status Pesanan:</span>
                                        {{ $complaint->pemesanan->status }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- INFORMASI PEMBAYARAN (JIKA KATEGORI PEMBAYARAN) --}}
                    @if($complaint->kategori === 'pembayaran'
                        && ($complaint->metode_pembayaran || $complaint->pembayaran || $complaint->nominal_id || $complaint->nomor_tujuan || $complaint->nama_tujuan))
                        <div class="border-t border-gray-100 pt-3 text-sm text-gray-700 space-y-1">
                            <h3 class="font-semibold mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-credit-card text-amber-600 text-xs"></i>
                                Informasi Pembayaran
                            </h3>

                            @if($complaint->metode_pembayaran)
                                <p>
                                    <span class="font-semibold">Metode:</span>
                                    {{ $complaint->metode_pembayaran }}
                                </p>
                            @endif

                            {{-- ⭐ Tambahan: Nomor & Nama Tujuan --}}
                            @if($complaint->nomor_tujuan)
                                <p>
                                    <span class="font-semibold">Nomor Tujuan:</span>
                                    {{ $complaint->nomor_tujuan }}
                                </p>
                            @endif

                            @if($complaint->nama_tujuan)
                                <p>
                                    <span class="font-semibold">Nama Pemilik:</span>
                                    {{ $complaint->nama_tujuan }}
                                </p>
                            @endif

                            {{-- ⭐ Tambahan: Nominal / ID Pembayaran --}}
                            @if($complaint->nominal_id)
                                <p>
                                    <span class="font-semibold">Nominal / ID Pembayaran:</span>
                                    {{ $complaint->nominal_id }}
                                </p>
                            @endif

                            @if($complaint->pembayaran)
                                <p>
                                    <span class="font-semibold">ID Pembayaran:</span>
                                    {{ $complaint->pembayaran->id_pembayaran ?? $complaint->pembayaran->id ?? '-' }}
                                </p>
                                @if(isset($complaint->pembayaran->status))
                                    <p>
                                        <span class="font-semibold">Status Pembayaran:</span>
                                        {{ $complaint->pembayaran->status }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- DESKRIPSI MASALAH --}}
                    <div class="border-t border-gray-100 pt-3 text-sm">
                        <h3 class="font-semibold mb-1">Deskripsi Masalah</h3>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ $complaint->deskripsi }}
                        </p>
                    </div>

                    {{-- LAMPIRAN --}}
                    @if($complaint->lampiran)
                        <div class="border-t border-gray-100 pt-3 text-sm">
                            <h3 class="font-semibold mb-1">Lampiran Bukti</h3>
                            <a href="{{ asset('storage/' . $complaint->lampiran) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-xs text-gray-700">
                                <i class="fa-solid fa-paperclip text-[11px]"></i>
                                Lihat Lampiran
                            </a>
                        </div>
                    @endif

                    {{-- BALASAN ADMIN TERKINI --}}
                    @if($complaint->balasan_admin)
                        <div class="border-t border-gray-100 pt-3 text-sm">
                            <h3 class="font-semibold mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-reply text-emerald-600 text-xs"></i>
                                Balasan Admin
                            </h3>
                            <p
                                class="bg-emerald-50 border border-emerald-100 rounded-xl px-3 py-2 text-[13px] text-gray-800 whitespace-pre-line leading-relaxed">
                                {{ $complaint->balasan_admin }}
                            </p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- KANAN: TINDAKAN ADMIN --}}
            <div class="space-y-3">
                <div class="rounded-xl border border-gray-200 bg-white shadow-md p-5 space-y-3">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-toolbox text-blue-600 text-sm"></i>
                        Tindakan Admin
                    </h2>

                    <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')

                        {{-- STATUS --}}
                        <div class="space-y-1 text-sm">
                            <label class="font-semibold text-gray-700">
                                Status Laporan
                            </label>
                            <select name="status"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-200 focus:border-blue-500">
                                @foreach (['baru', 'diproses', 'selesai', 'ditolak'] as $status)
                                    <option value="{{ $status }}" @selected($complaint->status === $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BALASAN --}}
                        <div class="space-y-1 text-sm">
                            <label class="font-semibold text-gray-700">
                                Balasan untuk pengguna
                                <span class="text-[10px] text-gray-400">(akan tampil di aplikasi)</span>
                            </label>
                            <textarea name="balasan_admin" rows="6"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-200 focus:border-blue-500 resize-none"
                                      placeholder="Tuliskan penjelasan atau solusi yang akan dikirim ke pengguna...">{{ old('balasan_admin', $complaint->balasan_admin) }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-sm">
                            <i class="fa-solid fa-floppy-disk text-[11px]"></i>
                            Simpan Perubahan
                        </button>
                    </form>

                    @if($complaint->admin)
                        <p class="text-[11px] text-gray-500 mt-2 leading-snug">
                            Terakhir diubah oleh
                            <span
                                class="font-semibold text-gray-700">{{ $complaint->admin->nama ?? $complaint->admin->name ?? '-' }}</span><br>
                            pada {{ $complaint->updated_at?->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
