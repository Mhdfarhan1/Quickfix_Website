<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    
    <link rel="icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2" type="image/png">
    <link rel="shortcut icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2">


    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Lato', sans-serif;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 min-h-screen flex flex-col items-center justify-center">

    <!-- Logo -->
    <div class="mb-6 flex flex-col items-center">
        <img src="{{ asset('assets/img/Logo_quickfix.png') }}" alt="Logo website"
            class="w-24 h-24 mb-3 rounded-full shadow-md border-2 border-blue-200">
        <h1 class="text-2xl font-bold text-blue-800 tracking-wide">QuickFix Admin Panel</h1>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-md border border-blue-100">
        <h2 class="text-3xl font-bold text-center text-blue-700 mb-8">Login Admin</h2>

        <!-- Pesan error -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form id="loginForm" action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <div class="relative">
                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 w-5 h-5"></i>

                    <input type="email" name="email" id="email" placeholder="Masukkan email" class="w-full pl-12 pr-4 py-3 border border-blue-200 rounded-2xl 
                   focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all"
                        value="{{ old('email') }}">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>

                <div class="relative">
                    <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 w-5 h-5"></i>

                    <input type="password" name="password" id="password" placeholder="Masukkan password" class="w-full pl-12 pr-12 py-3 border border-blue-200 rounded-2xl 
                   focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all">

                    <button type="button" id="togglePassword"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-500 hover:text-blue-700 transition">
                        <i data-lucide="eye"></i>
                    </button>
                </div>
            </div>

            <!-- Tombol Login -->
            <button id="loginBtn" type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-semibold shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span id="loginText">Masuk</span>
                <span id="loginLoader" class="hidden">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                        <path class="opacity-75" fill="white" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                        </path>
                    </svg>
                </span>
            </button>

        </form>
    </div>

    <script>
        lucide.createIcons();

        // Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;

            togglePassword.innerHTML =
                type === 'password'
                    ? '<i data-lucide="eye"></i>'
                    : '<i data-lucide="eye-off"></i>';

            lucide.createIcons();
        });

        // Loader Button
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const loginText = document.getElementById('loginText');
        const loginLoader = document.getElementById('loginLoader');

        loginForm.addEventListener('submit', () => {
            loginBtn.classList.add("cursor-not-allowed", "opacity-70");
            loginText.classList.add("hidden");
            loginLoader.classList.remove("hidden");
        });
    </script>

</body>

</html>