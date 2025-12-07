@extends('layouts.app')

@section('content')
    <main class="flex-1 p-4 md:p-6 overflow-y-auto">

        @if(session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            </script>
        @endif



        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">

            <!-- Card 1: Total Teknisi -->
            <div
                class="fade-in-up bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Teknisi</p>
                        <h3 class="text-3xl font-bold mb-2">{{ $jumlahTeknisi }}</h3>
                        <div class="flex items-center gap-1 text-sm">
                            <i class="fas fa-user-cog"></i>
                            <span class="text-blue-100">Akun terdaftar</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card 2: Komentar / Complain -->
            <div
                class="fade-in-up bg-gradient-to-br from-red-600 to-red-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium mb-1">Complain Masuk</p>
                        <h3 class="text-3xl font-bold mb-2">8</h3>
                        <div class="flex items-center gap-1 text-sm">
                            <i class="fas fa-comments"></i>
                            <span class="text-red-100">Menunggu respon</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-comment-dots text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Pemasukan -->
            <div
                class="fade-in-up bg-gradient-to-br from-green-600 to-green-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Total Pemasukan</p>
                        <h3 class="text-3xl font-bold mb-2">Rp {{ number_format($totalPendapatanAdmin, 0, ',', '.') }}</h3>
                        <div class="flex items-center gap-1 text-sm">
                            <i class="fas fa-arrow-up"></i>
                            <span class="text-green-100">Bulan ini</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Card 4: History Pesanan -->
            <div
                class="fade-in-up bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium mb-1">Pesanan Selesai</p>
                        <h3 class="text-3xl font-bold mb-2">{{ $jumlahPemesananSelesai }}</h3>
                        <div class="flex items-center gap-1 text-sm">
                            <i class="fas fa-history"></i>
                            <span class="text-yellow-100">Total transaksi</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-box-open text-2xl"></i>
                    </div>
                </div>
            </div>

        </div>
        {{-- 2 CHART: TEKNISI & PENGGUNA --}}
        <div class="mt-8 space-y-4">

            {{-- TITLE + FILTER --}}
            <div class="flex justify-between items-center">
                {{-- Judul kiri --}}
                <h2 class="text-xl font-bold text-gray-800">
                    Statistik Pendaftaran (Teknisi & Pengguna)
                </h2>

                {{-- Filter kanan --}}
                <form method="GET" id="filterBulanForm" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Filter Tahun</label>

                    <select name="year" id="selectYear"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-500">
                        @foreach(range(now()->year - 2, now()->year) as $y)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- GRID 2 CHART --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Chart Teknisi --}}
                <div class="bg-white rounded-2xl shadow-md p-4">
                    <h2 class="text-lg font-semibold mb-3">Teknisi Terdaftar</h2>
                    <canvas id="teknisiChart" class="w-full h-64"></canvas>
                </div>

                {{-- Chart Pengguna --}}
                <div class="bg-white rounded-2xl shadow-md p-4">
                    <h2 class="text-lg font-semibold mb-3">Pengguna Terdaftar</h2>
                    <canvas id="penggunaChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>

        {{-- TARUH INI DI BAWAH (atau di @section('scripts') --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('filterBulanForm');
    const selectMonth = document.getElementById('selectMonth');
    const selectYear = document.getElementById('selectYear');

    if (selectMonth) selectMonth.addEventListener('change', () => form.submit());
    if (selectYear)  selectYear.addEventListener('change', () => form.submit());

    // DATA DARI CONTROLLER
    let labels = {!! json_encode($labels ?? []) !!} || [];
    let teknisiData = {!! json_encode($teknisiPerMonth ?? []) !!} || [];
    let penggunaData = {!! json_encode($penggunaPerMonth ?? []) !!} || [];

    // 🔥 FORMAT LABEL → NAMA BULAN SAJA
    labels = labels.map(lbl => {
        const d = new Date(lbl);
        if (!isNaN(d)) {
            return d.toLocaleString('id-ID', { month: 'long' });
        }
        return lbl;
    });

    // Fallback jika kosong
    if (labels.length === 0) {
        labels = ["Tidak ada data"];
        teknisiData = [0];
        penggunaData = [0];
    }

    // TEKNISI CHART
    const teknisiCanvas = document.getElementById('teknisiChart');
    if (teknisiCanvas) {
        new Chart(teknisiCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Teknisi Baru',
                    data: teknisiData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    // PENGGUNA CHART
    const penggunaCanvas = document.getElementById('penggunaChart');
    if (penggunaCanvas) {
        new Chart(penggunaCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pengguna Baru',
                    data: penggunaData,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }
});
</script>




    </main>
@endsection