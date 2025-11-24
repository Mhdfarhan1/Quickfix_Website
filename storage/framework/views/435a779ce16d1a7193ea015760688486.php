<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickFix - Teknisi On Demand Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="icon" href="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>?v=2" type="image/png">
    <link rel="shortcut icon" href="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>?v=2" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>?v=2">


    <style>
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
            animation: gradientShift 15s ease infinite;
        }

        /* Animasi float */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
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
            animation: fadeInUp 1s ease-out forwards;
            opacity: 0;
        }

        /* Slide in left/right */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slideInLeft {
            animation: slideInLeft 1s ease-out forwards;
            opacity: 0;
        }

        .animate-slideInRight {
            animation: slideInRight 1s ease-out forwards;
            opacity: 0;
        }

        /* Card hover */
        .service-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .service-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.12);
        }

        /* Navbar blur */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }

        .floating-tech {
            animation: floaty 6s ease-in-out infinite;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }

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
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.9;
                transform: scale(1.05);
            }
        }

        .floating-tech {
            animation: floaty 6s ease-in-out infinite;
        }

        .animate-spin-slow {
            animation: spinSlow 20s linear infinite;
        }

        .animate-pulse-slow {
            animation: pulseSlow 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md shadow-sm animate-fadeInUp">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3 cursor-pointer">
                    <img src="<?php echo e(asset('assets/img/Logo_quickfix.png')); ?>" alt="Logo QuickFix"
                        class="w-16 h-16 object-contain rounded-full drop-shadow-lg">

                    <span
                        class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">
                        QuickFix
                    </span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home"
                        class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition font-medium">
                        <i data-lucide="home" class="w-5 h-5"></i> Beranda
                    </a>
                    <a href="#services"
                        class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition font-medium">
                        <i data-lucide="wrench" class="w-5 h-5"></i> Layanan
                    </a>
                    <a href="#features"
                        class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition font-medium">
                        <i data-lucide="star" class="w-5 h-5"></i> Fitur
                    </a>
                    <a href="#testimonials"
                        class="flex items-center gap-2 text-gray-700 hover:text-blue-600 transition font-medium">
                        <i data-lucide="message-circle" class="w-5 h-5"></i> Testimoni
                    </a>
                    <a href="<?php echo e(route('admin.login')); ?>"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl hover:shadow-lg transform hover:scale-105 transition">
                        <i data-lucide="log-in" class="w-5 h-5"></i> Login
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="#home" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">
                    <i data-lucide="home" class="w-5 h-5"></i> Beranda
                </a>
                <a href="#services" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">
                    <i data-lucide="wrench" class="w-5 h-5"></i> Layanan
                </a>
                <a href="#features" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">
                    <i data-lucide="star" class="w-5 h-5"></i> Fitur
                </a>
                <a href="#testimonials"
                    class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">
                    <i data-lucide="message-circle" class="w-5 h-5"></i> Testimoni
                </a>
                <a href="#contact"
                    class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-lg text-center">
                    <i data-lucide="phone-call" class="w-5 h-5"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-white">
        <!-- Animated Background -->
        <div class="absolute inset-0 gradient-bg opacity-10"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl animate-float"
            style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid md:grid-cols-2 gap-12 items-center">

                <!-- Text Content -->
                <div class="opacity-0 animate-slideInLeft">
                    <!-- Label atas -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-full mb-4">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-medium text-blue-600">Tersedia 24/7</span>
                    </div>

                    <!-- Judul -->
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                        <span
                            class="bg-gradient-to-r from-blue-600 via-cyan-500 to-blue-600 bg-clip-text text-transparent">
                            QuickFix
                        </span>
                        <br>
                        <span class="text-gray-800">Teknisi On Demand</span>
                    </h1>

                    <!-- Deskripsi -->
                    <p class="text-base md:text-lg text-gray-600 mb-6 leading-relaxed max-w-md">
                        Layanan perbaikan & instalasi teknologi terbaik dengan teknisi bersertifikat.
                        Cepat, terpercaya, dan harga terjangkau.
                    </p>

                    <!-- Tombol -->
                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <a href="#services"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition flex items-center justify-center gap-2 text-sm">
                            <i data-lucide="zap" class="w-4 h-4"></i>
                            Pesan Sekarang
                        </a>
                        <a href="#features"
                            class="px-6 py-3 bg-white text-gray-700 rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition flex items-center justify-center gap-2 text-sm">
                            <i data-lucide="play-circle" class="w-4 h-4"></i>
                            Lihat Demo
                        </a>
                    </div>

                    <!-- Statistik -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">
                                <?php echo e($jumlahTeknisi); ?>

                            </div>
                            <div class="text-xs text-gray-600">Teknisi Ahli</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($jumlahPengguna); ?></div>
                            <div class="text-xs text-gray-600">Jumlah Pelanggan</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">4.9★</div>
                            <div class="text-xs text-gray-600">Rating</div>
                        </div>
                    </div>
                </div>


                <!-- Image with Animation -->
                <div class="relative opacity-0 animate-slideInRight" style="animation-delay: 0.3s;">
                    <div class="relative">
                        <!-- Decorative gradient background with curve -->
                        <div class="absolute inset-0 flex justify-center items-center -z-10">
                            <div
                                class="w-[600px] h-[600px] bg-gradient-to-br from-blue-500/30 via-cyan-400/20 to-purple-400/30 rounded-[50%] blur-3xl animate-pulse-slow">
                            </div>
                        </div>

                        <!-- Optional glowing ring -->
                        <div class="absolute inset-0 flex justify-center items-center -z-10">
                            <div class="w-[500px] h-[500px] rounded-full border-2 border-cyan-400/30 animate-spin-slow">
                            </div>
                        </div>

                        <!-- Animated Technician Image -->
                        <div class="relative">
                            <img src="<?php echo e(asset('assets/img/teknisi.jpg')); ?>" alt="Teknisi QuickFix"
                                class="w-full max-w-md mx-auto drop-shadow-2xl floating-tech rounded-[2rem]" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section (Ganti menjadi Mockup App Mobile) -->
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fadeInUp">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Mockup <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">Aplikasi
                        Mobile</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Tampilan aplikasi QuickFix versi mobile yang modern dan mudah digunakan.
                </p>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-12">
                <!-- Mockup Phone -->
                <div class="relative animate-fadeInUp" style="animation-delay:0.2s;">
                    <div
                        class="w-80 h-[520px] bg-gradient-to-br from-blue-100 to-cyan-100 rounded-[2.5rem] shadow-2xl border-4 border-white flex flex-col items-center justify-start overflow-hidden">
                        <!-- Status Bar -->
                        <div class="w-full flex justify-between items-center px-6 py-3 bg-white/80">
                            <span class="text-xs text-gray-500">09:41</span>
                            <div class="flex gap-1 items-center">
                                <i data-lucide="wifi" class="w-4 h-4 text-gray-400"></i>
                                <i data-lucide="battery" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                        <!-- App Header -->
                        <div class="w-full px-6 py-4 flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-lg flex items-center justify-center">
                                <i data-lucide="wrench" class="w-6 h-6 text-white"></i>
                            </div>
                            <span
                                class="text-xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">QuickFix</span>
                        </div>
                        <!-- Main Content -->
                        <div class="flex-1 w-full px-6 py-2 overflow-y-auto">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Layanan Favorit</h3>
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-white rounded-xl p-4 shadow flex flex-col items-center">
                                    <i data-lucide="laptop" class="w-7 h-7 text-blue-500 mb-2"></i>
                                    <span class="text-sm text-gray-700">Laptop</span>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow flex flex-col items-center">
                                    <i data-lucide="smartphone" class="w-7 h-7 text-green-500 mb-2"></i>
                                    <span class="text-sm text-gray-700">HP/Tablet</span>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow flex flex-col items-center">
                                    <i data-lucide="wifi" class="w-7 h-7 text-purple-500 mb-2"></i>
                                    <span class="text-sm text-gray-700">Jaringan</span>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow flex flex-col items-center">
                                    <i data-lucide="camera" class="w-7 h-7 text-red-500 mb-2"></i>
                                    <span class="text-sm text-gray-700">CCTV</span>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pesanan Terbaru</h3>
                            <div class="bg-white rounded-xl p-4 shadow mb-3 flex items-center gap-3">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">Perbaikan Laptop</div>
                                    <div class="text-xs text-gray-500">Selesai • 2 jam lalu</div>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-4 shadow mb-3 flex items-center gap-3">
                                <i data-lucide="clock" class="w-6 h-6 text-yellow-500"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">Instalasi Jaringan</div>
                                    <div class="text-xs text-gray-500">Menunggu Teknisi</div>
                                </div>
                            </div>
                        </div>
                        <!-- Bottom Navigation -->
                        <div class="w-full px-8 py-4 bg-white/80 flex justify-between items-center">
                            <button class="flex flex-col items-center text-blue-600">
                                <i data-lucide="home" class="w-6 h-6"></i>
                                <span class="text-xs">Beranda</span>
                            </button>
                            <button class="flex flex-col items-center text-gray-400">
                                <i data-lucide="search" class="w-6 h-6"></i>
                                <span class="text-xs">Cari</span>
                            </button>
                            <button class="flex flex-col items-center text-gray-400">
                                <i data-lucide="user" class="w-6 h-6"></i>
                                <span class="text-xs">Akun</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Deskripsi Mockup -->
                <div class="max-w-lg animate-fadeInUp" style="animation-delay:0.4s;">
                    <h4 class="text-2xl font-bold text-gray-800 mb-4">Aplikasi QuickFix Mobile</h4>
                    <p class="text-lg text-gray-600 mb-4">
                        Nikmati kemudahan memesan teknisi langsung dari smartphone Anda. Fitur utama:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Pilih layanan favorit dengan satu klik</li>
                        <li>Lacak status pesanan secara real-time</li>
                        <li>Notifikasi teknisi & progress pesanan</li>
                        <li>Desain modern, responsif, dan mudah digunakan</li>
                    </ul>
                    <a href="#"
                        class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition">
                        Lihat Demo Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gradient-to-br from-blue-50 to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fadeInUp">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Kenapa Pilih <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">QuickFix?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Kami berkomitmen memberikan layanan terbaik dengan teknologi modern
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.1s;">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="zap" class="w-7 h-7 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Respon Cepat</h3>
                    <p class="text-gray-600">Teknisi siap datang dalam waktu kurang dari 30 menit setelah pemesanan.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.2s;">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="w-7 h-7 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Garansi Layanan</h3>
                    <p class="text-gray-600">Semua layanan kami dilengkapi dengan garansi hingga 30 hari.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.3s;">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="users" class="w-7 h-7 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Teknisi Bersertifikat</h3>
                    <p class="text-gray-600">Semua teknisi kami telah tersertifikasi dan berpengalaman.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.4s;">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="dollar-sign" class="w-7 h-7 text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Harga Transparan</h3>
                    <p class="text-gray-600">Tidak ada biaya tersembunyi. Harga jelas dan kompetitif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fadeInUp">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Apa Kata <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">Pelanggan</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Ribuan pelanggan telah mempercayai layanan kami
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.1s;">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/100?img=1" alt="User 1"
                            class="w-16 h-16 rounded-full border-2 border-blue-500">
                        <div>
                            <div class="font-bold text-gray-800">Andi</div>
                            <div class="text-sm text-gray-600">Jakarta</div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"Layanan QuickFix sangat
                        memuaskan! Teknisi datang tepat waktu dan
                        memperbaiki laptop saya dengan cepat."</p>
                    <div class="flex items
                        .center gap-1 text-yellow-400">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.2s;">
                    <div class="flex items
                        .center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/100?img=2" alt="User 2"
                            class="w-16 h-16 rounded-full border-2 border-purple-500">
                        <div>
                            <div class="font-bold text-gray-800">Siti</div>
                            <div class="text-sm text-gray-600">Bandung</div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"Teknisi QuickFix sangat ramah dan
                        profesional. Jaringan WiFi di kantor saya
                        sekarang jauh lebih stabil."</p>
                    <div class="flex items
                        .center gap-1 text-yellow-400">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition animate-fadeInUp"
                    style="animation-delay:0.3s;">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/100?img=3" alt="User 3"
                            class="w-16 h-16 rounded-full border-2 border-green-500">
                        <div>
                            <div class="font-bold text-gray-800">Budi</div>
                            <div class="text-sm text-gray-600">Surabaya</div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">"QuickFix membantu saya mer
                        akit PC gaming impian saya. Sangat puas dengan hasilnya!"</p>
                    <div class="flex items-center gap-1 text-yellow-400">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <i data-lucide="star" class="w-5 h-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-gradient-to-br from-blue-50 to-cyan-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fadeInUp">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Hubungi <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">Kami</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Siap membantu Anda kapan saja, di mana saja
                </p>
            </div>
            <div class="max-w-3xl mx-auto bg-white rounded-2xl p-8 shadow-lg animate-fadeInUp"
                style="animation-delay:0.2s;">
                <form action="#" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea id="message" name="message" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-white/90 backdrop-blur-md shadow-inner animate-fadeInUp" style="animation-delay:0.3s;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">&copy; 2024 QuickFix. All rights reserved.</div>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.244.0/dist/lucide.min.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        // Fade in animation on scroll
        document.querySelectorAll('.animate-fadeInUp').forEach((el, i) => {
            el.style.opacity = 0;
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        el.style.opacity = 1;
                        el.style.animationPlayState = 'running';
                        observer.unobserve(el);
                    }
                });
            }, {
                threshold: 0.1
            });
            observer.observe(el);
        });
        document.querySelectorAll('.animate-slideInLeft, .animate-slideInRight').forEach((el, i) => {
            el.style.opacity = 0;
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        el.style.opacity = 1;
                        el.style.animationPlayState = 'running';
                        observer.unobserve(el);
                    }
                });
            }, {
                threshold: 0.1
            });
            observer.observe(el);
        });
    </script>

</body>

</html><?php /**PATH D:\Quickfix_Website\resources\views/welcome.blade.php ENDPATH**/ ?>