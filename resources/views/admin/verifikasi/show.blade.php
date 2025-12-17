@extends('layouts.app')

@section('content')

    @php
        // 1. Ambil Nama dari Tabel USERS
        $namaApp = strtolower(trim($data->teknisi->user->nama ?? ''));

        // 2. Ambil Nama Legal (Inputan User)
        $namaLegal = strtolower(trim($data->account_name_verified ?? ''));

        // 3. Hitung persentase kecocokan nama
        $percent = 0;
        if (!empty($namaApp) && !empty($namaLegal)) {
            similar_text($namaApp, $namaLegal, $percent);
        }
        $percent = round($percent);

        // 4. Tentukan Status Kecocokan Nama (DIFOKUSKAN KE KTP)
        if ($percent >= 90) {
            $matchStatus = 'Nama Identitas VALID';
            $matchColor = 'text-emerald-700 bg-emerald-50 border-emerald-200';
            $matchIcon = 'fa-check-double';
            $matchDesc = 'Nama akun aplikasi SAMA PERSIS dengan nama di KTP.';
        } elseif ($percent >= 50) {
            $matchStatus = 'Nama Identitas MIRIP';
            $matchColor = 'text-blue-700 bg-blue-50 border-blue-200';
            $matchIcon = 'fa-check';
            $matchDesc = 'Ada sedikit perbedaan penulisan, tapi kemungkinan orang yang sama.';
        } else {
            $matchStatus = 'Nama BERBEDA JAUH';
            $matchColor = 'text-amber-700 bg-amber-50 border-amber-200';
            $matchIcon = 'fa-exclamation-triangle';
            $matchDesc = 'Nama akun berbeda dengan KTP. Waspada akun joki/palsu.';
        }

        // 5. LOGIKA EXPIRED SKCK (TAMBAHAN BARU)
        $isSkckExpired = false;
        $skckStatusText = 'Belum Ada Data Tanggal';
        $skckColor = 'text-gray-500 bg-gray-100';

        if (!empty($data->skck_expired)) {
            $expireDate = \Carbon\Carbon::parse($data->skck_expired);
            $today = \Carbon\Carbon::now();
            
            if ($expireDate->isPast()) {
                $isSkckExpired = true;
                $skckStatusText = 'SUDAH KADALUARSA';
                $skckColor = 'text-rose-700 bg-rose-50 border-rose-200';
                $skckIcon = 'fa-times-circle';
            } else {
                // Cek jika akan expired dalam 30 hari (Warning)
                if ($expireDate->diffInDays($today) <= 30) {
                     $skckStatusText = 'Segera Berakhir';
                     $skckColor = 'text-amber-700 bg-amber-50 border-amber-200';
                     $skckIcon = 'fa-exclamation-circle';
                } else {
                     $skckStatusText = 'Masih Berlaku';
                     $skckColor = 'text-emerald-700 bg-emerald-50 border-emerald-200';
                     $skckIcon = 'fa-check-circle';
                }
            }
        }
    @endphp

    <div class="p-6 space-y-6">

        {{-- HEADER & BACK BUTTON --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.verifikasi.index') }}"
                    class="p-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Verifikasi</h1>
                    <p class="text-xs text-gray-500">
                        ID Pengajuan: #{{ $data->id }} &bull; {{ $data->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            {{-- STATUS BADGE UTAMA --}}
            <div>
                @if($data->status == 'pending')
                    <span
                        class="px-4 py-2 rounded-lg bg-amber-100 text-amber-700 font-bold text-sm border border-amber-200 shadow-sm flex items-center gap-2">
                        <i class="fas fa-clock"></i> Menunggu Konfirmasi
                    </span>
                @elseif($data->status == 'disetujui')
                    <span
                        class="px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-sm border border-emerald-200 shadow-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Disetujui
                    </span>
                @else
                    <span
                        class="px-4 py-2 rounded-lg bg-rose-100 text-rose-700 font-bold text-sm border border-rose-200 shadow-sm flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> Ditolak
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI: DATA DIRI & ANALISA (1/3 Layar) --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- CARD 1: Identitas & Pengecekan Nama --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-id-card text-blue-500"></i>
                        <h3 class="font-bold text-gray-700">Validasi Identitas</h3>
                    </div>
                    <div class="p-6 space-y-5">

                        {{-- Nama Akun App --}}
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Nama Akun (Aplikasi)</p>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-mobile-alt text-gray-400"></i>
                                <p class="text-gray-800 font-bold text-lg">
                                    {{ $data->teknisi->user->nama ?? 'User Tidak Dikenal' }}
                                </p>
                            </div>
                        </div>

                        {{-- Nama Sesuai KTP --}}
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Nama Sesuai KTP</p>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-address-card text-gray-400"></i>
                                <p class="text-gray-800 font-bold text-lg">
                                    {{ $data->account_name_verified }}
                                </p>
                            </div>
                        </div>

                        {{-- BOX ANALISA NAMA --}}
                        <div class="p-4 rounded-xl border {{ $matchColor }} relative overflow-hidden">
                            <div class="flex items-start gap-3 relative z-10">
                                <div class="p-2 bg-white/50 rounded-full">
                                    <i class="fas {{ $matchIcon }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase opacity-80 mb-1">Analisa Kesesuaian Nama</p>
                                    <p class="text-sm font-bold leading-tight">
                                        {{ $matchStatus }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-xs font-mono bg-white/60 px-2 py-0.5 rounded">
                                            {{ $percent }}% Mirip
                                        </span>
                                    </div>
                                    <p class="text-[11px] mt-2 opacity-90 leading-snug">
                                        {{ $matchDesc }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- NIK --}}
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">NIK (Nomor Induk Kependudukan)</p>
                            <p class="text-gray-800 font-mono bg-gray-100 inline-block px-3 py-1 rounded text-sm mt-1">
                                {{ $data->nik }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: Bank & Alamat --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-university text-indigo-500"></i>
                        <h3 class="font-bold text-gray-700">Detail Bank & Domisili</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-semibold">Nama Pemilik Rekening</p>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-tag text-gray-300"></i>
                                <p class="text-gray-800 font-bold text-lg">
                                    {{ $data->account_name_verified }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 border-t border-gray-100 pt-3">
                            <div class="flex-1">
                                <p class="text-xs text-gray-400 uppercase font-semibold">Bank</p>
                                <p class="text-gray-800 font-bold uppercase">{{ $data->bank }}</p>
                                @if(!empty($data->bank_code))
                                    <span class="text-[10px] text-gray-500 bg-gray-100 px-1 rounded">Kode: {{ $data->bank_code }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-400 uppercase font-semibold">No. Rekening</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-800 font-mono text-lg font-bold">{{ $data->rekening }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-3">
                            <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Alamat Domisili</p>
                            <div class="flex items-start gap-2 text-gray-600 text-sm">
                                <i class="fas fa-map-marker-alt mt-1 text-red-400"></i>
                                <p>
                                    Kec. {{ $data->kecamatan }},<br>
                                    Kab. {{ $data->kabupaten }},<br>
                                    Prov. {{ $data->provinsi }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 3: AKSI ADMIN --}}
                @if($data->status == 'pending')
                    <div class="rounded-xl border border-blue-200 bg-blue-50/50 p-6 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2">Keputusan Verifikasi</h3>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                            Pastikan hasil analisa rekening valid dan foto dokumen jelas.
                            @if($isSkckExpired)
                                <br><strong class="text-red-600">PERHATIAN: SKCK SUDAH KADALUARSA!</strong>
                            @endif
                        </p>

                        <form action="{{ route('admin.verifikasi.update', $data->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <button type="submit" name="action" value="terima"
                                onclick="return confirm('Yakin ingin MENERIMA pengajuan ini?')"
                                class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm hover:shadow-md">
                                <i class="fas fa-check-circle"></i> SETUJUI & AKTIFKAN
                            </button>

                            <button type="submit" name="action" value="tolak"
                                onclick="return confirm('Yakin ingin MENOLAK?')"
                                class="w-full flex items-center justify-center gap-2 bg-white border-2 border-rose-200 text-rose-600 hover:bg-rose-50 font-bold py-3 px-4 rounded-xl transition">
                                <i class="fas fa-times-circle"></i> TOLAK PENGAJUAN
                            </button>
                        </form>
                    </div>
                @endif

            </div>

            {{-- KOLOM KANAN: DOKUMEN (2/3 Layar) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- KTP SECTION --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-image text-gray-500"></i>
                            <h3 class="font-bold text-gray-700">Foto KTP</h3>
                        </div>
                        @if($data->foto_ktp)
                            <a href="{{ asset('storage/' . $data->foto_ktp) }}" target="_blank"
                                class="text-xs text-blue-600 hover:underline">Lihat Ukuran Asli <i class="fas fa-external-link-alt ml-1"></i></a>
                        @endif
                    </div>
                    <div class="p-6 bg-gray-100/50 min-h-[300px] flex items-center justify-center">
                        @if($data->foto_ktp)
                            <img src="{{ asset('storage/' . $data->foto_ktp) }}"
                                class="max-h-[500px] max-w-full rounded-lg shadow-md border border-gray-200 hover:scale-[1.02] transition-transform duration-300"
                                alt="Foto KTP">
                        @else
                            <div class="text-center text-gray-400">
                                <i class="fas fa-image-slash text-4xl mb-2"></i>
                                <p>Tidak ada file KTP</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SKCK SECTION --}}
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-contract text-gray-500"></i>
                            <h3 class="font-bold text-gray-700">Foto SKCK</h3>
                        </div>

                        {{-- TAMPILAN STATUS EXPIRED (BARU) --}}
                        @if(!empty($data->skck_expired))
                            <div class="flex items-center gap-2 px-3 py-1 rounded-full border {{ $skckColor }}">
                                <i class="fas {{ $skckIcon }} text-xs"></i>
                                <span class="text-xs font-bold uppercase">{{ $skckStatusText }}</span>
                                <span class="text-xs text-gray-600 font-mono border-l border-gray-300 pl-2 ml-1">
                                    {{ \Carbon\Carbon::parse($data->skck_expired)->format('d M Y') }}
                                </span>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">Tanpa Tanggal Expired</span>
                        @endif
                    </div>

                    <div class="p-6 bg-gray-100/50 min-h-[300px] flex items-center justify-center">
                        @if($data->foto_skck)
                            <img src="{{ asset('storage/' . $data->foto_skck) }}"
                                class="max-h-[500px] max-w-full rounded-lg shadow-md border border-gray-200 hover:scale-[1.02] transition-transform duration-300"
                                alt="Foto SKCK">
                        @else
                            <div class="text-center text-gray-400">
                                <i class="fas fa-image-slash text-4xl mb-2"></i>
                                <p>Tidak ada file SKCK</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($data->foto_skck)
                        <div class="bg-gray-50 px-6 py-2 border-t border-gray-100 text-right">
                             <a href="{{ asset('storage/' . $data->foto_skck) }}" target="_blank"
                                class="text-xs text-blue-600 hover:underline">
                                Lihat Ukuran Asli <i class="fas fa-external-link-alt ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection