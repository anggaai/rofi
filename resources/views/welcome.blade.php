<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockMaster — Warehouse Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,850&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-slate-300 font-['Plus_Jakarta_Sans',sans-serif] tracking-tight min-h-screen flex flex-col justify-between relative bg-cover bg-center bg-no-repeat bg-fixed selection:bg-blue-600/30 selection:text-blue-200" 
      style="background-image: url('{{ asset('images/bg-gudang.png') }}');"> 
    
    <!-- Dark Overlay Premium untuk Kontras Teks -->
    <div class="absolute inset-0 bg-slate-950/40 pointer-events-none z-0"></div>

    <!-- Header / Navbar -->
    <header class="w-full fixed top-0 left-0 z-50 bg-slate-950/40 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="font-extrabold text-base tracking-wider text-white uppercase">Stock<span class="text-blue-400">master</span></span>
            </div>

            <nav class="flex items-center space-x-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-blue-400 hover:text-blue-300 transition-colors">
                            Console Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-300 hover:text-white transition-colors">
                            Sign In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs font-bold bg-blue-600 text-white px-4 py-2 rounded-xl shadow-md shadow-blue-600/20 hover:bg-blue-500 transition-all">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="relative max-w-7xl mx-auto px-6 sm:px-8 w-full flex-1 grid lg:grid-cols-12 gap-12 items-center pt-40 pb-24 z-10">
    
        <!-- Ambient Premium Glow Effects -->
        <div class="absolute top-1/4 left-10 -z-10 w-96 h-96 bg-blue-500/15 rounded-full blur-3xl pointer-events-none animate-pulse duration-[6s]"></div>
        <div class="absolute bottom-1/4 right-1/4 -z-10 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Kolom Konten Utama -->
        <div class="lg:col-span-9 xl:col-span-9 w-full text-left bg-transparent space-y-12">
        
            <!-- Bagian Heading & Deskripsi -->
            <div class="space-y-8">
                
                <!-- Teks Raksasa Premium - UKURAN LEBIH BESAR -->
                <div class="space-y-8 max-w-5xl">
    
                    <h1 class="tracking-tight text-white select-none antialiased drop-shadow-md">
                        <!-- Baris pertama: Optimalkan Stok - Lebih besar dari sebelumnya -->
                        <span class="block text-7xl sm:text-8xl lg:text-9xl xl:text-[6rem] 2xl:text-[7rem] font-black tracking-tighter text-white leading-[0.85] uppercase">
                            Optimalkan Stok,
                        </span>
                        <!-- Baris kedua: Kendali Material Presisi - Lebih besar & lebih striking -->
                        <span class="block mt-6 font-serif italic font-normal tracking-wide text-4xl sm:text-6xl lg:text-7xl xl:text-[4.5rem] 2xl:text-[5.5rem] bg-gradient-to-r from-blue-400 via-indigo-300 to-cyan-400 bg-clip-text text-transparent leading-[1.1]">
                            Kendali Material Presisi.
                        </span>
                    </h1>

                </div>
                
                <!-- Deskripsi -->
                <p class="text-slate-300 text-base sm:text-lg max-w-2xl leading-relaxed font-normal antialiased">
                    Sistem manajemen inventaris bahan bangunan modern berbasis metode 
                    <span class="font-semibold text-white bg-white/10 px-2 py-0.5 rounded border border-white/10 font-mono text-sm inline-block">Buffer Stock</span>. 
                    Amankan ketersediaan material kritis, deteksi otomatis titik batas minimum pasokan, dan kelola distribusi logistik secara real-time.
                </p>
            </div>

            <!-- Tombol Aksi Kategori Premium -->
            <div class="flex flex-wrap gap-4 items-center pt-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="group relative px-8 py-4 bg-white text-slate-950 rounded-xl font-bold text-xs tracking-wider uppercase transition-all duration-300 hover:bg-slate-100 shadow-xl shadow-white/5 transform hover:-translate-y-0.5 overflow-hidden">
                        <span class="relative z-10 flex items-center gap-2">
                            Enter Dashboard Console
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold text-xs tracking-wider uppercase hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-600/30 transform hover:-translate-y-0.5">
                        Sign In to System
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white/5 backdrop-blur-sm border border-white/10 text-white rounded-xl font-bold text-xs tracking-wider uppercase hover:bg-white/10 hover:border-white/20 transition-all duration-300 shadow-sm transform hover:-translate-y-0.5">
                        Register Operator
                    </a>
                @endauth
            </div>

        </div>

        <!-- Kolom Kanan -->
        <div class="hidden lg:block lg:col-span-3 xl:col-span-3">
            <!-- Sengaja dikosongkan agar teks kiri mendapat ruang maksimal -->
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full py-6 bg-slate-950/40 backdrop-blur-md border-t border-white/5 z-10">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 flex flex-col sm:flex-row justify-between items-center gap-2 text-[11px] font-medium text-slate-400">
            <p>&copy; 2026 StockMaster Enterprise. All rights reserved.</p>
            <div class="flex space-x-4">
                <span class="text-slate-400">System Status: <span class="text-emerald-400 font-bold">Operational</span></span>
            </div>
        </div>
    </footer>

</body>
</html>