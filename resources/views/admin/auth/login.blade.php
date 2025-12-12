<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickFix | Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2" type="image/png">
    <link rel="shortcut icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
        }

        /* Animasi masuk halus */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-animate {
            animation: fadeUp 0.7s ease-out forwards;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 min-h-screen flex flex-col items-center justify-center px-4">

    <!-- Dekorasi background lembut -->
    <div class="pointer-events-none fixed -top-24 -left-16 w-64 h-64 bg-blue-300/40 rounded-full blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-24 -right-10 w-72 h-72 bg-blue-400/40 rounded-full blur-3xl"></div>

    <!-- Logo + judul -->
    <div class="mb-6 flex flex-col items-center relative z-10">
        <div
            class="w-24 h-24 mb-3 rounded-full shadow-lg border-2 border-white/70 bg-white/80 flex items-center justify-center relative">
            <div class="absolute inset-0 rounded-full bg-blue-500/10 blur-sm"></div>
            <img src="{{ asset('assets/img/Logo_quickfix.png') }}" alt="Logo website"
                 class="w-20 h-20 rounded-full object-cover relative">
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold text-blue-900 tracking-wide text-center">
            QuickFix Admin Panel
        </h1>

        <!-- Badge kecil -->
        <div
            class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/70 border border-blue-100 text-[11px] text-blue-700 shadow-sm">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            <span>Akses khusus admin terotorisasi</span>
        </div>
    </div>

    <!-- Card -->
    <div
        class="relative bg-white/95 rounded-3xl shadow-2xl w-full max-w-md border border-blue-100/70 card-animate z-10 overflow-hidden">

        <!-- Top accent -->
        <div class="h-1.5 w-24 mx-auto mt-4 rounded-full bg-gradient-to-r from-blue-500 via-sky-400 to-cyan-400"></div>

        <div class="px-8 pt-6 pb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-blue-800 mb-2">
                Login Admin
            </h2>
            <p class="text-xs md:text-sm text-center text-slate-500 mb-6">
                Masuk menggunakan akun admin yang telah terdaftar.
            </p>

            <!-- Pesan error -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form id="loginForm" action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 tracking-wide">
                        Email
                    </label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="admin@quickfix.id"
                            class="w-full pl-11 pr-4 py-2.5 border border-blue-200/80 rounded-2xl text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-400/70 focus:border-blue-500
                                   bg-slate-50/70 placeholder:text-slate-400"
                            value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password"
                               class="block text-xs font-semibold text-slate-700 tracking-wide">
                            Password
                        </label>
                        <span class="text-[11px] text-slate-400 italic">
                            Jangan bagikan akses admin.
                        </span>
                    </div>

                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            class="w-full pl-11 pr-11 py-2.5 border border-blue-200/80 rounded-2xl text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-400/70 focus:border-blue-500
                                   bg-slate-50/70 placeholder:text-slate-400">

                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-500/80 hover:text-blue-700 transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Ingat saya -->
                <div class="flex items-center justify-between text-[11px] text-slate-500">
                    <label for="remember" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-3.5 h-3.5 rounded-sm border-slate-300 text-blue-600 focus:ring-blue-400 focus:ring-1">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                    {{-- <a href="#" class="text-blue-600 hover:underline">Lupa password?</a> --}}
                </div>

                <!-- Tombol Login -->
                <button
                    id="loginBtn"
                    type="submit"
                    class="w-full mt-1 bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 hover:from-blue-700 hover:via-blue-600 hover:to-blue-700
                           text-white py-2.5 rounded-2xl font-semibold shadow-md hover:shadow-lg
                           transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                    <span id="loginText">Masuk ke Admin Panel</span>
                    <span id="loginLoader" class="hidden items-center gap-1 text-[12px]">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                            <path class="opacity-75" fill="white"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                            </path>
                        </svg>
                        <span>Sedang masuk...</span>
                    </span>
                </button>
            </form>
        </div>

        <!-- Footer kecil -->
        <div class="px-6 pb-4 flex items-center justify-between text-[11px] text-slate-400">
            <span>© {{ date('Y') }} QuickFix</span>
            <span class="hidden sm:inline">By PBL-304</span>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;

            // Ganti ikon
            togglePassword.innerHTML =
                type === 'password'
                    ? '<i data-lucide="eye" class="w-4 h-4"></i>'
                    : '<i data-lucide="eye-off" class="w-4 h-4"></i>';

            lucide.createIcons();
        });

        // Loader Button
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const loginText = document.getElementById('loginText');
        const loginLoader = document.getElementById('loginLoader');

        loginForm.addEventListener('submit', () => {
            // Cegah submit double
            if (loginBtn.disabled) return;

            loginBtn.classList.add("cursor-not-allowed", "opacity-70");
            loginBtn.disabled = true;

            // Ubah teks jadi "Sedang masuk..."
            loginText.classList.add("hidden");
            loginLoader.classList.remove("hidden");
            loginLoader.classList.add("flex");
        });
    </script>

</body>

</html>
