<!-- Desktop Sidebar -->
<aside class="flex-col bg-white text-gray-800 shadow-2xl w-64 md:flex hidden sidebar-animate">
    <!-- Logo -->
    <div class="flex items-center justify-center h-20 border-b border-gray-200 bg-white">
        <div class="w-16 h-16 rounded-xl flex items-center justify-center ring-4 ring-white shadow-lg">
            <img src="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>" alt="Logo"
                class="w-14 h-14 object-contain rounded-full">
        </div>
        <span
            class="ml-3 text-2xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">QuickFix</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="<?php echo e(route('admin.dashboard')); ?>"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300
           <?php echo e(request()->routeIs('admin.dashboard') ? 'active bg-blue-100/50 text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'); ?>">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- [PEMBATAS DENGAN TEKS] -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Manajemen</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <div
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-user-shield w-5"></i>
            <span>Akun Admin</span>
        </div>


        <div
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-user-cog w-5"></i>
            <span>Akun Teknisi</span>
        </div>


        <!-- [PEMBATAS DENGAN TEKS] -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Lainnya</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Kelompok Fitur Lomba -->
        <a href="#"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-comments w-5"></i>
            <span>Complain Masuk</span>
        </a>

        <a href="#"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-wallet w-5"></i>
            <span>Total Pemasukan</span>
        </a>

        <a href="#"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-box-open w-5"></i>
            <span>Pesanan Selesai</span>
        </a>
    </nav>

    <!-- User Profile & Logout -->

</aside>

<!-- Mobile Sidebar & Overlay -->
<aside id="mobile-sidebar"
    class="fixed top-0 left-0 w-64 h-full bg-white text-gray-800 shadow-2xl transform -translate-x-full transition-transform duration-300 z-50 md:hidden">

    <!-- Logo -->
    <div class="flex items-center justify-between h-20 px-4 border-b border-gray-200">
        <div class="flex items-center gap-2">
            <div
                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                <img src="<?php echo e(asset('assets/images/logo_educhamp.png')); ?>" alt="Logo"
                    class="w-10 h-10 object-contain rounded-full">
            </div>
            <span
                class="text-xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">EduChamp</span>
        </div>
        <button id="mobile-close-btn" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="<?php echo e(route('admin.dashboard')); ?>"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300
            <?php echo e(request()->routeIs('admin.dashboard') ? 'active bg-blue-100/50 text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50'); ?>">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- [PEMBATAS DENGAN TEKS] -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Manajemen</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Kelompok Manajemen -->


        <!-- [PEMBATAS DENGAN TEKS] -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Lainnya</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Kelompok Fitur Lomba -->
        <a href="#"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-calendar-alt w-5"></i>
            <span>Jadwal Lomba</span>
        </a>
        <a href="#"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
            <i class="fas fa-award w-5"></i> <!-- Icon diganti menjadi award -->
            <span>Pemenang</span>
        </a>
    </nav>
</aside>

<div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div><?php /**PATH D:\Quickfix_Website\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>