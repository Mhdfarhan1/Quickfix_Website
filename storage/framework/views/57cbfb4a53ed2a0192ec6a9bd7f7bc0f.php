<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'QuickFix | Admin Dashboard'); ?></title>

    <!-- CSS Files -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom.css')); ?>">

    <!--alpinejs-->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!--swertalert2-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="icon" href="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>?v=2" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>?v=2" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>?v=2">




    <style>
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-h-screen w-full md:w-auto">
        <!-- Top Navbar -->
        <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Content -->
        <section class="flex-1 p-3 sm:p-4 md:p-6 fade-in-up">
            <?php echo $__env->yieldContent('content'); ?>
        </section>
    </main>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div>


    <!-- 1. jQuery (HARUS PALING PERTAMA) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- 2. Chart.js (bisa di sini) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 3. DataTables JS (setelah jQuery) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- 4. SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Script: Mobile Sidebar Toggle + DataTables -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileCloseBtn = document.getElementById('mobile-close-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        function closeMobileMenu() {
            mobileSidebar?.classList.add('-translate-x-full');
            mobileOverlay?.classList.add('hidden');
        }

        mobileMenuBtn?.addEventListener('click', () => {
            mobileSidebar?.classList.remove('-translate-x-full');
            mobileOverlay?.classList.remove('hidden');
        });
        mobileCloseBtn?.addEventListener('click', closeMobileMenu);
        mobileOverlay?.addEventListener('click', closeMobileMenu);
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) closeMobileMenu();
        });

        // Initialize all DataTables with responsive + Tailwind defaults
        $(document).ready(function () {
            if ($.fn.DataTable) {
                $.extend(true, $.fn.dataTable.defaults, {
                    responsive: true,
                    autoWidth: false,
                    language: {
                        lengthMenu: "_MENU_",
                        search: "_INPUT_",
                        searchPlaceholder: "Cari...",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "›",
                            previous: "‹"
                        },
                        emptyTable: "Tidak ada data tersedia"
                    }
                });
            }
        });

        // cdn sweetalert2
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Sidebar link aktif
            function setActiveLink() {
                const links = document.querySelectorAll('.nav-link');

                links.forEach(link => {
                    link.addEventListener('click', function () {
                        // Hapus class active dari semua link
                        links.forEach(l => l.classList.remove('active'));

                        // Tambahkan class active ke link yang diklik
                        this.classList.add('active');

                        // Jika mobile, tutup sidebar setelah klik
                        const mobileSidebar = document.getElementById('mobile-sidebar');
                        const mobileOverlay = document.getElementById('mobile-overlay');
                        if (mobileSidebar && mobileOverlay && window.innerWidth < 768) {
                            mobileSidebar.classList.add('-translate-x-full');
                            mobileOverlay.classList.add('hidden');
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', setActiveLink);
        </script>

        
    <?php $__env->stopPush(); ?>

</body>

</html><?php /**PATH D:\Quickfix_Website\resources\views/layouts/app.blade.php ENDPATH**/ ?>