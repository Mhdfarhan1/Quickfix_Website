<?php $__env->startSection('content'); ?>
    <main class="flex-1 p-4 md:p-6 overflow-y-auto">

        <?php if(session('success')): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "<?php echo e(session('success')); ?>",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            </script>
        <?php endif; ?>



        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">

            <!-- Card 1: Total Teknisi -->
            <div
                class="fade-in-up bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Teknisi</p>
                        <h3 class="text-3xl font-bold mb-2"><?php echo e($jumlahTeknisi); ?></h3>
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
                        <h3 class="text-3xl font-bold mb-2">Rp 12.500.000</h3>
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
                        <h3 class="text-3xl font-bold mb-2">45</h3>
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

        <!-- Main Content -->
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4">Statistik Teknisi</h2>
            <canvas id="teknisiChart" class="w-full h-64 bg-white rounded-xl shadow-md p-4"></canvas>
        </div>

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('teknisiChart').getContext('2d');
            const teknisiChart = new Chart(ctx, {
                type: 'bar', // jenis chart: bar, line, pie, etc.
                data: {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei'],
                    datasets: [{
                        label: 'Total Teknisi',
                        data: [5, 8, 12, 9, 14],
                        backgroundColor: 'rgba(59, 130, 246, 0.7)', // biru
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Teknisi Per Bulan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Quickfix_Website\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>