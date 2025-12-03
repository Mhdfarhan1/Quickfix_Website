<!-- Desktop Sidebar -->
<aside class="flex-col bg-white text-gray-800 shadow-2xl w-64 md:flex hidden sidebar-animate">
    <!-- Logo -->
    <div class="flex items-center justify-center h-20 border-b border-gray-200 bg-white">
        <div class="w-16 h-16 rounded-xl flex items-center justify-center ring-4 ring-white shadow-lg">
            <img src="{{ asset('assets/img/Logo_quickfix.png') }}" alt="Logo"
                class="w-14 h-14 object-contain rounded-full">
        </div>
        <span
            class="ml-3 text-2xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">QuickFix</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300
           {{ request()->routeIs('admin.dashboard') ? 'active bg-blue-100/50 text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- Pembatas dengan teks -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Manajemen</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Dropdown Manajemen -->
        <div x-data="{ open: false }" class="px-0">
            <button @click="open = !open"
                class="flex items-center gap-3 w-full rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-4 py-3 transition-all duration-300">
                <i class="fas fa-cogs w-5"></i>
                <span>Manajemen</span>
                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-auto w-3"></i>
            </button>

            <!-- Dropdown fitur -->
            <div x-show="open" x-transition class="mt-1 space-y-1 pl-7">
                <a href="{{ route('admin.teknisi.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300
                    {{ request()->routeIs('admin.teknisi.index') ? 'bg-blue-100/50 text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <i class="fas fa-user-cog w-5"></i>
                    <span>Akun Teknisi</span>
                </a>

                <a href="{{ route('admin.user.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl
                    {{ request()->routeIs('admin.user.index') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }} transition-all duration-300">
                    <i class="fas fa-users w-5"></i>
                    <span>Pengguna</span>
                </a>

            </div>
        </div>

        <!-- Lainnya -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Lainnya</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

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
</aside>

<!-- Mobile Sidebar & Overlay -->
<aside id="mobile-sidebar"
    class="fixed top-0 left-0 w-64 h-full bg-white text-gray-800 shadow-2xl transform -translate-x-full transition-transform duration-300 z-50 md:hidden"
    x-data="{ open: false }">

    <!-- Logo -->
    <div class="flex items-center justify-between h-20 px-4 border-b border-gray-200">
        <div class="flex items-center gap-2">
            <div
                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                <img src="{{ asset('assets/img/Logo_quickfix.png') }}" alt="Logo"
                    class="w-10 h-10 object-contain rounded-full">
            </div>
            <span
                class="text-xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">QuickFix</span>
        </div>
        <button id="mobile-close-btn" class="p-2 hover:bg-gray-100 rounded-lg"
            @click="$el.closest('#mobile-sidebar').classList.add('-translate-x-full'); document.getElementById('mobile-overlay').classList.add('hidden')">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="px-4 py-6 space-y-2">
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300
           {{ request()->routeIs('admin.dashboard') ? 'active bg-blue-100/50 text-blue-600 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- Dropdown Manajemen Mobile -->
        <div x-data="{ open: false }" class="px-0">
            <button @click="open = !open"
                class="flex items-center gap-3 w-full rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-4 py-3 transition-all duration-300">
                <i class="fas fa-cogs w-5"></i>
                <span>Manajemen</span>
                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ml-auto w-3"></i>
            </button>

            <div x-show="open" x-transition class="mt-1 space-y-1 pl-7">
                <a href="#akun-admin"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
                    <i class="fas fa-user-shield w-5"></i>
                    <span>Akun Admin</span>
                </a>
                <a href="{{ route('admin.teknisi.index') }}"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
                    <i class="fas fa-user-cog w-5"></i>
                    <span>Akun Teknisi</span>
                </a>

                <a href="#pengguna"
                    class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all duration-300">
                    <i class="fas fa-users w-5"></i>
                    <span>Pengguna</span>
                </a>
            </div>
        </div>

        <!-- Lainnya -->
        <div class="px-4 pt-4 pb-2 flex items-center">
            <span class="flex-shrink-0 text-xs font-semibold text-gray-400 uppercase pr-3">Lainnya</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

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
</aside>

<!-- Mobile Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"
    @click="$el.classList.add('hidden'); document.getElementById('mobile-sidebar').classList.add('-translate-x-full')">
</div>