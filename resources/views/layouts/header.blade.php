<header class="glass-effect shadow-lg px-6 h-20 flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-4">
        <button id="mobile-menu-btn" class="md:hidden p-2 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-bars text-gray-700 text-xl"></i>
        </button>

        <div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Dashboard
            </h1>

            @if(session('admin_nama'))
                <p class="text-xs text-gray-500">
                    Welcome back, {{ session('admin_nama') }}
                </p>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-4">
        

        <!-- User Dropdown -->
        <div class="relative">
            @if(session('admin_nama'))

                    <button id="profile-btn"
                        class="flex items-center gap-3 bg-gray-100 px-3 py-2 rounded-xl hover:bg-gray-200 transition">

                        <img src="{{ session('admin_foto')
                ? asset('storage/' . session('admin_foto'))
                : 'https://ui-avatars.com/api/?name=' . urlencode(session('admin_nama')) . '&background=667eea&color=fff' }}"
                            class="w-8 h-8 rounded-full object-cover" alt="Avatar">

                        <span class="text-sm font-medium text-gray-700">
                            {{ session('admin_nama') }}
                        </span>

                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <!-- Dropdown -->
                    <div id="profile-dropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-xl py-2 border border-gray-100 z-20">

                        <a href="{{ route('admin.profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profil
                        </a>

                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Pengaturan
                        </a>

                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>

                    </div>

            @else
                <a href="{{ route('admin.login') }}" class="text-sm text-blue-600 font-semibold">Login</a>
            @endif
        </div>

    </div>
</header>

<script>
    const btn = document.getElementById('profile-btn');
    const dropdown = document.getElementById('profile-dropdown');

    btn?.addEventListener('click', () => {
        dropdown.classList.toggle('hidden');
    });

    // Klik di luar dropdown = tutup
    document.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>