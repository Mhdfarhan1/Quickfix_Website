<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickFix - Teknisi On Demand Terpercaya</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2" type="image/png">
    <link rel="shortcut icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/Logo_quickfix.png') }}?v=2">

    <style>
        :root {
            --primary: #2563eb;
            --primary-soft: #dbeafe;
            --cyan: #06b6d4;
            --purple: #8b5cf6;
            --pink: #ec4899;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "SF Pro Text", sans-serif;
        }

        /* Animasi background gradient */
        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #3b82f6, #06b6d4, #8b5cf6, #ec4899);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
        }

        /* Animasi float */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-14px);
            }
        }

        .animate-float-soft {
            animation: float 7s ease-in-out infinite;
        }

        /* Fade in up */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.9s ease-out forwards;
            opacity: 0;
        }

        /* Slide in left/right */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.9s ease-out forwards;
            opacity: 0;
        }

        .animate-slideInRight {
            animation: slideInRight 0.9s ease-out forwards;
            opacity: 0;
        }

        /* Card hover */
        .service-card,
        .feature-card,
        .testimonial-card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .service-card:hover,
        .feature-card:hover,
        .testimonial-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 18px 40px rgba(37, 99, 235, 0.15);
        }

        /* Navbar blur + border subtle */
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        }

        /* Floating tech */
        @keyframes spinSlow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulseSlow {

            0%,
            100% {
                opacity: 0.6;
                transform: scale(1);
            }

            50% {
                opacity: 0.95;
                transform: scale(1.04);
            }
        }

        .animate-spin-slow {
            animation: spinSlow 24s linear infinite;
        }

        .animate-pulse-slow {
            animation: pulseSlow 7s ease-in-out infinite;
        }

        .floating-tech {
            animation: float 7s ease-in-out infinite;
        }

        /* Section divider */
        .section-divider {
            width: 80px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(to right, var(--primary), var(--cyan));
            margin: 0.75rem auto 0;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 text-gray-900">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <a href="#home" class="flex items-center gap-3 cursor-pointer">
                    <div
                        class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-white shadow-lg flex items-center justify-center border border-blue-100">
                        <img src="{{ asset('assets/img/Logo_quickfix.png') }}" alt="Logo QuickFix"
                            class="w-10 h-10 object-contain rounded-full">
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span
                            class="text-xl md:text-2xl font-extrabold bg-gradient-to-r from-blue-600 via-cyan-500 to-purple-600 bg-clip-text text-transparent">
                            QuickFix
                        </span>
                        <span class="text-[11px] md:text-xs text-slate-500 tracking-wide">
                            Teknisi On Demand • IT & Elektronik
                        </span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium">
                    <a href="#home" class="flex items-center gap-2 text-slate-700 hover:text-blue-600 transition">
                        <i data-lucide="home" class="w-4 h-4"></i> Beranda
                    </a>
                    <a href="#services" class="flex items-center gap-2 text-slate-700 hover:text-blue-600 transition">
                        <i data-lucide="wrench" class="w-4 h-4"></i> Layanan
                    </a>
                    <a href="#features" class="flex items-center gap-2 text-slate-700 hover:text-blue-600 transition">
                        <i data-lucide="sparkles" class="w-4 h-4"></i> Fitur
                    </a>
                    <a href="#testimonials"
                        class="flex items-center gap-2 text-slate-700 hover:text-blue-600 transition">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Testimoni
                    </a>
                    <a href="#contact" class="flex items-center gap-2 text-slate-700 hover:text-blue-600 transition">
                        <i data-lucide="phone-call" class="w-4 h-4"></i> Kontak
                    </a>

                    <a href="{{ route('admin.login') }}"
                        class="ml-4 flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-500 text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 hover:scale-[1.02] transition text-sm">
                        <i data-lucide="log-in" class="w-4 h-4"></i> Login Admin
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn"
                    class="md:hidden p-2 rounded-lg hover:bg-slate-100 border border-slate-200">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-200">
            <div class="px-4 py-3 space-y-2 text-sm">
                <a href="#home" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                    <i data-lucide="home" class="w-4 h-4"></i> Beranda
                </a>
                <a href="#services" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                    <i data-lucide="wrench" class="w-4 h-4"></i> Layanan
                </a>
                <a href="#features" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                    <i data-lucide="sparkles" class="w-4 h-4"></i> Fitur
                </a>
                <a href="#testimonials" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                    <i data-lucide="message-circle" class="w-4 h-4"></i> Testimoni
                </a>
                <a href="#contact" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-50">
                    <i data-lucide="phone-call" class="w-4 h-4"></i> Kontak
                </a>
                <a href="{{ route('admin.login') }}"
                    class="mt-2 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-lg">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Login Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-24 md:pt-28 overflow-hidden bg-white">
        <!-- Animated Background -->
        <div class="absolute inset-0 gradient-bg opacity-10"></div>
        <div class="absolute top-24 left-[-40px] w-72 h-72 bg-blue-400/25 rounded-full blur-3xl animate-float-soft">
        </div>
        <div class="absolute bottom-10 right-[-40px] w-96 h-96 bg-cyan-400/25 rounded-full blur-3xl animate-float-soft"
            style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid md:grid-cols-2 gap-10 lg:gap-16 items-center">

                <!-- Text Content -->
                <div class="animate-slideInLeft">
                    <!-- Label atas -->
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-100 rounded-full mb-4">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[11px] md:text-xs font-semibold text-blue-700 tracking-wide">
                            Tersedia 24/7 • Teknisi Terverifikasi
                        </span>
                    </div>

                    <!-- Judul -->
                    <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold mb-4 leading-tight">
                        <span
                            class="bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-600 bg-clip-text text-transparent">
                            QuickFix
                        </span>
                        <br>
                        <span class="text-slate-800">Teknisi On Demand<br class="hidden lg:block" /></span>
                    </h1>

                    <!-- Deskripsi -->
                    <p class="text-sm md:text-base lg:text-lg text-slate-600 mb-6 leading-relaxed max-w-md">
                        Layanan perbaikan & instalasi perangkat teknologi dengan teknisi bersertifikat. Cepat datang,
                        pengerjaan rapi, dan harga transparan tanpa biaya tersembunyi.
                    </p>

                    <!-- Statistik -->
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <!-- Teknisi -->
                        <div
                            class="bg-white/80 border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xl md:text-2xl font-extrabold text-blue-600">
                                    {{ $jumlahTeknisi }}
                                </div>
                                <div class="text-[11px] md:text-xs text-slate-500">Teknisi Terverifikasi</div>
                            </div>
                        </div>

                        <!-- Pelanggan -->
                        <div
                            class="bg-white/80 border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-cyan-600">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xl md:text-2xl font-extrabold text-blue-600">
                                    {{ $jumlahPengguna }}
                                </div>
                                <div class="text-[11px] md:text-xs text-slate-500">Pelanggan Terdaftar</div>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div
                            class="bg-white/80 border border-slate-100 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                                <i data-lucide="star" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xl md:text-2xl font-extrabold text-purple-600">4.9★</div>
                                <div class="text-[11px] md:text-xs text-slate-500">Rating Kepuasan</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image with Animation -->
                <div class="relative animate-slideInRight" style="animation-delay: 0.2s;">
                    <div class="relative max-w-md mx-auto">
                        <!-- Decorative gradient blob -->
                        <div class="absolute inset-0 flex justify-center items-center -z-10">
                            <div
                                class="w-[420px] h-[420px] bg-gradient-to-br from-blue-500/30 via-cyan-400/20 to-purple-400/30 rounded-[40%] blur-3xl animate-pulse-slow">
                            </div>
                        </div>

                        <!-- Glowing ring -->
                        <div class="absolute inset-0 flex justify-center items-center -z-10">
                            <div class="w-[360px] h-[360px] rounded-full border border-cyan-400/40 animate-spin-slow">
                            </div>
                        </div>

                        <!-- Inner glow -->
                        <div
                            class="absolute inset-10 rounded-[2rem] border border-white/60 shadow-[0_0_40px_rgba(59,130,246,0.35)] -z-10">
                        </div>

                        <!-- Technician Image -->
                        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl floating-tech">
                            <img src="{{ asset('assets/img/teknisi.jpg') }}" alt="Teknisi QuickFix"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Floating badge -->
                        <div
                            class="absolute -bottom-6 -right-4 bg-white/95 border border-blue-100 rounded-2xl px-4 py-3 shadow-xl flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-800">Garansi Layanan</p>
                                <p class="text-[11px] text-slate-500">Hingga 30 hari setelah servis</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Services Section (Mockup App Mobile) -->
    <section id="services" class="py-20 md:py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-fadeInUp">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-3">
                    Mockup
                    <span
                        class="bg-gradient-to-r from-blue-600 via-cyan-500 to-purple-600 bg-clip-text text-transparent">
                        Aplikasi Mobile
                    </span>
                </h2>
                <p class="text-base md:text-lg text-slate-600 max-w-2xl mx-auto">
                    Tampilan aplikasi QuickFix versi mobile yang modern, intuitif, dan dirancang untuk memudahkan
                    pengguna dalam memesan teknisi kapan saja.
                </p>
                <div class="section-divider"></div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-10 lg:gap-16">
                <!-- Mockup Phone -->
                <div class="relative animate-fadeInUp service-card" style="animation-delay:0.1s;">
                    <div
                        class="w-72 sm:w-80 h-[500px] sm:h-[520px] bg-gradient-to-br from-blue-100 via-cyan-100 to-purple-100 rounded-[2.5rem] shadow-[0_20px_45px_rgba(15,23,42,0.18)] border-[6px] border-white flex flex-col items-center overflow-hidden">
                        <!-- Status Bar -->
                        <div
                            class="w-full flex justify-between items-center px-6 py-3 bg-white/90 border-b border-slate-100">
                            <span class="text-xs font-semibold text-slate-600">09:41</span>
                            <div class="flex gap-1.5 items-center text-slate-400">
                                <i data-lucide="wifi" class="w-4 h-4"></i>
                                <i data-lucide="battery" class="w-4 h-4"></i>
                            </div>
                        </div>

                        <!-- App Header -->
                        <div class="w-full px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-xl flex items-center justify-center shadow-md">
                                    <i data-lucide="wrench" class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span
                                        class="text-lg font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">
                                        QuickFix
                                    </span>
                                    <span class="text-[11px] text-slate-500">Teknisi On Demand</span>
                                </div>
                            </div>
                            <div
                                class="px-2.5 py-1 rounded-full bg-emerald-50 text-[10px] font-semibold text-emerald-600 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 w-full px-6 pt-1 pb-3 overflow-y-auto">
                            <h3 class="text-sm font-semibold text-slate-800 mb-2">Layanan Favorit</h3>
                            <div class="grid grid-cols-2 gap-3 mb-5 text-xs">
                                <div class="bg-white rounded-xl p-3 shadow-sm flex flex-col items-center gap-1.5">
                                    <i data-lucide="laptop" class="w-6 h-6 text-blue-500"></i>
                                    <span class="font-medium text-slate-800">Laptop</span>
                                    <span class="text-[10px] text-slate-400">Instal & Service</span>
                                </div>
                                <div class="bg-white rounded-xl p-3 shadow-sm flex flex-col items-center gap-1.5">
                                    <i data-lucide="smartphone" class="w-6 h-6 text-emerald-500"></i>
                                    <span class="font-medium text-slate-800">HP/Tablet</span>
                                    <span class="text-[10px] text-slate-400">Perbaikan Cepat</span>
                                </div>
                                <div class="bg-white rounded-xl p-3 shadow-sm flex flex-col items-center gap-1.5">
                                    <i data-lucide="wifi" class="w-6 h-6 text-purple-500"></i>
                                    <span class="font-medium text-slate-800">Jaringan</span>
                                    <span class="text-[10px] text-slate-400">Setup & Troubleshoot</span>
                                </div>
                                <div class="bg-white rounded-xl p-3 shadow-sm flex flex-col items-center gap-1.5">
                                    <i data-lucide="camera" class="w-6 h-6 text-rose-500"></i>
                                    <span class="font-medium text-slate-800">CCTV</span>
                                    <span class="text-[10px] text-slate-400">Pasang & Cek</span>
                                </div>
                            </div>

                            <h3 class="text-sm font-semibold text-slate-800 mb-2">Pesanan Terbaru</h3>
                            <div class="space-y-2.5 text-xs">
                                <div class="bg-white rounded-xl p-3 shadow-sm flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-[13px]">Perbaikan Laptop</div>
                                        <div class="text-[11px] text-slate-500">Selesai • 2 jam lalu</div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-3 shadow-sm flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                                        <i data-lucide="clock" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-[13px]">Instalasi Jaringan</div>
                                        <div class="text-[11px] text-slate-500">Menunggu teknisi</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Navigation -->
                        <div
                            class="w-full px-8 py-3.5 bg-white/95 border-t border-slate-100 flex justify-between items-center text-[11px]">
                            <button class="flex flex-col items-center text-blue-600">
                                <i data-lucide="home" class="w-5 h-5 mb-0.5"></i>
                                <span>Beranda</span>
                            </button>
                            <button class="flex flex-col items-center text-slate-400">
                                <i data-lucide="search" class="w-5 h-5 mb-0.5"></i>
                                <span>Cari</span>
                            </button>
                            <button class="flex flex-col items-center text-slate-400">
                                <i data-lucide="user" class="w-5 h-5 mb-0.5"></i>
                                <span>Akun</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Mockup -->
                <div class="max-w-lg animate-fadeInUp" style="animation-delay:0.25s;">
                    <h4 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-4">
                        Aplikasi QuickFix Mobile
                    </h4>
                    <p class="text-sm md:text-base text-slate-600 mb-4 leading-relaxed">
                        QuickFix tidak hanya hadir dalam bentuk website, tetapi juga dirancang untuk pengalaman mobile
                        yang optimal. Pengguna bisa memesan teknisi, memantau status pesanan, dan melihat riwayat servis
                        langsung dari genggaman.
                    </p>
                    <ul class="list-disc list-inside text-slate-700 space-y-2 text-sm md:text-base mb-5">
                        <li>Pilih layanan favorit hanya dengan beberapa ketukan.</li>
                        <li>Lacak status pesanan dan kedatangan teknisi secara real-time.</li>
                        <li>Riwayat servis tercatat rapi untuk memudahkan pengecekan.</li>
                        <li>Desain modern, ringan, dan nyaman untuk penggunaan harian.</li>
                    </ul>
                    <a href="#"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-[1.03] transition text-sm md:text-base">
                        <i data-lucide="smartphone" class="w-4 h-4"></i>
                        Lihat Demo Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 md:py-24 bg-gradient-to-br from-blue-50 via-cyan-50/60 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-fadeInUp">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-3">
                    Kenapa Pilih
                    <span
                        class="bg-gradient-to-r from-blue-600 via-cyan-500 to-purple-600 bg-clip-text text-transparent">
                        QuickFix?
                    </span>
                </h2>
                <p class="text-base md:text-lg text-slate-600 max-w-2xl mx-auto">
                    Kami menggabungkan teknisi profesional, sistem pemesanan modern, dan pengalaman pengguna yang
                    nyaman dalam satu platform terintegrasi.
                </p>
                <div class="section-divider"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <div class="feature-card bg-white rounded-2xl p-7 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.05s;">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="zap" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Respon Cepat</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Teknisi siap datang dalam waktu singkat setelah pemesanan, sehingga masalah perangkat Anda bisa
                        segera teratasi.
                    </p>
                </div>

                <div class="feature-card bg-white rounded-2xl p-7 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.12s;">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="shield-check" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Garansi Layanan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Setiap pengerjaan dilengkapi garansi hingga 30 hari untuk memberikan rasa aman dan kepercayaan
                        bagi pelanggan.
                    </p>
                </div>

                <div class="feature-card bg-white rounded-2xl p-7 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.19s;">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Teknisi Bersertifikat</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Teknisi kami telah melalui proses seleksi dan sertifikasi, sehingga pengerjaan lebih rapi,
                        aman, dan profesional.
                    </p>
                </div>

                <div class="feature-card bg-white rounded-2xl p-7 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.26s;">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-5">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-amber-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Harga Transparan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Estimasi biaya jelas sejak awal. Tidak ada biaya tersembunyi, semua tercatat rapi di sistem.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section (replace Testimonials Section) -->
    <section id="testimonials" class="py-20 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 animate-fadeInUp">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-3">
                    Cara Kerja
                    <span
                        class="bg-gradient-to-r from-blue-600 via-cyan-500 to-purple-600 bg-clip-text text-transparent">
                        QuickFix
                    </span>
                </h2>
                <p class="text-base md:text-lg text-slate-600 max-w-2xl mx-auto">
                    Alur kerja yang jelas dan terstruktur, mulai dari permintaan layanan hingga teknisi menyelesaikan
                    pekerjaan di lokasi pelanggan.
                </p>
                <div class="section-divider"></div>
            </div>

            <div class="grid md:grid-cols-4 gap-6 lg:gap-8">
                <!-- Step 1 -->
                <div class="testimonial-card bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-2xl p-6 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.05s;">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                            1
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Langkah 1</span>
                            <span class="text-sm font-bold text-slate-900">Pelanggan Membuat Permintaan</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-3 leading-relaxed">
                        Pelanggan mengisi data kerusakan dan memilih kategori layanan melalui aplikasi atau website
                        QuickFix.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-blue-700">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Detail kerusakan tercatat otomatis</span>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="testimonial-card bg-gradient-to-br from-purple-50 via-white to-purple-50 rounded-2xl p-6 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.12s;">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-9 h-9 rounded-full bg-purple-600 text-white flex items-center justify-center text-sm font-bold">
                            2
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Langkah 2</span>
                            <span class="text-sm font-bold text-slate-900">Sistem Memilih Teknisi</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-3 leading-relaxed">
                        Sistem QuickFix mengatur penugasan teknisi berdasarkan lokasi, keahlian, dan ketersediaan
                        jadwal.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-purple-700">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>Penugasan lebih cepat & tepat sasaran</span>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="testimonial-card bg-gradient-to-br from-cyan-50 via-white to-cyan-50 rounded-2xl p-6 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.19s;">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-9 h-9 rounded-full bg-cyan-500 text-white flex items-center justify-center text-sm font-bold">
                            3
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Langkah 3</span>
                            <span class="text-sm font-bold text-slate-900">Teknisi Datang ke Lokasi</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-3 leading-relaxed">
                        Teknisi menerima detail pekerjaan di aplikasi mobile, kemudian datang ke lokasi pelanggan sesuai
                        jadwal yang disepakati.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-cyan-700">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>Lokasi & progres dikelola secara real-time</span>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="testimonial-card bg-gradient-to-br from-emerald-50 via-white to-emerald-50 rounded-2xl p-6 shadow-md border border-slate-100 animate-fadeInUp"
                    style="animation-delay:0.26s;">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm font-bold">
                            4
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Langkah 4</span>
                            <span class="text-sm font-bold text-slate-900">Pekerjaan Selesai & Tercatat</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-3 leading-relaxed">
                        Setelah pekerjaan selesai, teknisi mengunggah laporan, foto before–after, dan status disimpan di
                        sistem sebagai riwayat.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-emerald-700">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span>Riwayat servis tersimpan & bisa dipantau</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 md:py-24 bg-gradient-to-br from-blue-50 via-cyan-50/70 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-12 md:mb-16 animate-fadeInUp">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 mb-3">
                    Kontak
                    <span
                        class="bg-gradient-to-r from-blue-600 via-cyan-500 to-purple-600 bg-clip-text text-transparent">
                        QuickFix
                    </span>
                </h2>
                <p class="text-base md:text-lg text-slate-600 max-w-xl mx-auto">
                    Anda dapat menghubungi kami melalui informasi berikut.
                </p>
                <div class="section-divider"></div>
            </div>

            <div class="max-w-3xl mx-auto bg-white rounded-2xl p-8 shadow-md border border-slate-100 animate-fadeInUp"
                style="animation-delay:0.15s;">

                <div class="space-y-6 text-sm">

                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Telepon / WhatsApp</p>
                            <p class="text-slate-500 text-xs">+62 xxx xxxx xxxx</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-cyan-600">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Email</p>
                            <p class="text-slate-500 text-xs">support@quickfix.id</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Alamat</p>
                            <p class="text-slate-500 text-xs">Jl. Teknologi No. 123, Jakarta</p>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="#"
                        class="px-6 py-3 bg-green-500 text-white rounded-xl font-semibold shadow hover:shadow-lg transform hover:scale-[1.03] transition flex items-center gap-2">
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                        Hubungi via WhatsApp
                    </a>

                    <a href="mailto:support@quickfix.id"
                        class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold shadow hover:shadow-lg transform hover:scale-[1.03] transition flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        Kirim Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white/95 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                <div class="text-slate-500 text-center md:text-left">
                    &copy; {{ date('Y') }} QuickFix. All rights reserved.
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-pink-500 transition">
                        <i data-lucide="instagram" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-blue-600 transition">
                        <i data-lucide="linkedin" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Animate on scroll for fade/slide elements
        const animatedEls = document.querySelectorAll('.animate-fadeInUp, .animate-slideInLeft, .animate-slideInRight');
        const observer = new IntersectionObserver(
            entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.animationPlayState = 'running';
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12 }
        );

        animatedEls.forEach(el => {
            el.style.opacity = 0;
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>

</body>

</html>