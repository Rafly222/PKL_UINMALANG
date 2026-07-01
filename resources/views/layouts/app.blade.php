<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'E-Presensi Digital Diskominfo Malang')</title>
    
    <!-- Google Fonts: Noto Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Noto Sans', 'sans-serif'],
                    },
                    colors: {
                        malang: {
                            blue: '#1e3a8a',      // Navy Blue Pemkot
                            teal: '#0f766e',      // Emerald/Teal Aksen
                            accent: '#f59e0b',    // Kuning Emas
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Style Lokal -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    
    <!-- Yield CSS Khusus Halaman -->
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col">

    <!-- Header / Navbar Utama Dinsos/Diskominfo -->
    <header class="bg-gradient-to-r from-blue-900 to-indigo-950 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Sisi Kiri: Logo & Judul Instansi -->
                <div class="flex items-center space-x-3">
                    <!-- Representasi Logo Pemkot Malang berbentuk SVG ramah performa -->
                    <svg class="h-12 w-12 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.33l-7.5-5-7.5 5V21M3 21h18M12 9h.008v.008H12V9zm-3 3h.008v.008H9V12zm6 0h.008v.008H15V12z" />
                    </svg>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold tracking-wider leading-tight">E-PRESENSI RESMI</h1>
                        <p class="text-xs text-amber-400 font-medium">DISKOMINFO KOTA MALANG</p>
                    </div>
                </div>

                <!-- Sisi Kanan: Menu Navigasi Pengunjung vs Admin -->
                <nav class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('presensi.form') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all-300 {{ Request::is('/') ? 'bg-amber-500 text-blue-950' : 'hover:bg-blue-800' }}">
                        <i class="fa-solid fa-pen-fancy mr-2"></i>Form Presensi
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all-300 {{ Request::is('admin*') ? 'bg-amber-500 text-blue-950' : 'hover:bg-blue-800' }}">
                        <i class="fa-solid fa-chart-line mr-2"></i>Dashboard Rekap
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Konten Utama Aplikasi -->
    <main class="flex-grow py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Notifikasi Berhasil -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xl mr-3"></i>
                        <span class="text-emerald-800 font-semibold">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">✕</button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer Formal Pemkot Malang -->
    <footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800 text-center text-xs sm:text-sm">
        <p>&copy; 2026 Dinas Komunikasi dan Informatika Pemerintah Kota Malang. All Rights Reserved.</p>
        <p class="mt-1 text-slate-600">Sistem E-Presensi v2.0 - Dikembangkan oleh Mahasiswa PKL UIN Maulana Malik Ibrahim Malang</p>
    </footer>

    <!-- Yield Scripts Khusus Halaman -->
    @yield('scripts')
</body>
</html>