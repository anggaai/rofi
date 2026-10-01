<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SI MJ3</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Smooth transition untuk sidebar */
        .sidebar-transition {
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .content-transition {
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Hover effect untuk tombol toggle */
        .toggle-btn:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased">

<div x-data="{ open: true, currentPath: window.location.pathname }" class="flex min-h-screen relative">
    
    {{-- SIDEBAR --}}
    <aside 
        :class="open ? 'w-72' : 'w-20'" 
        class="sidebar-transition fixed left-0 top-0 h-full z-50 bg-[#0F172A] border-r border-slate-800 shadow-2xl flex flex-col"
    >
        <!-- Header Sidebar dengan Tombol Toggle -->
        <div class="relative h-24 flex items-center justify-between px-4 overflow-hidden flex-shrink-0 border-b border-slate-800/50">
            <div class="flex items-center space-x-4" :class="open ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-blue-500 blur-lg opacity-20"></div>
                    <div class="relative bg-white p-2 rounded-xl shadow-sm border border-slate-100 flex items-center justify-center">
                        <img src="{{ asset('images/Logosimj.png') }}" alt="Logo SIMJ" class="w-10 h-10 object-contain">
                    </div>
                </div>
                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <h1 class="text-white font-bold text-lg tracking-wider leading-none">SI MJ3</h1>
                    <p class="text-blue-400 text-[10px] font-semibold uppercase tracking-[0.2em] mt-1">Inventory System</p>
                </div>
            </div>
            
            <!-- Tombol Toggle di Sidebar (Selalu Terlihat) -->
            <button @click="open = !open" 
                class="toggle-btn absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-white transition-all duration-300 border border-slate-700/50 shadow-lg group"
                :class="open ? 'rotate-0' : 'rotate-180'"
                :style="open ? 'right: 12px' : 'right: 50%; transform: translateX(50%) translateY(-50%);'"
            >
                <svg class="w-5 h-5 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto no-scrollbar">
            <div x-show="open" x-cloak class="px-4 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-[0.15em]">
                Main Menu
            </div>

            <!-- Dashboard -->
            <a href="/dashboard" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath === '/dashboard' || currentPath === '/',
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !(currentPath === '/dashboard' || currentPath === '/')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            </a>

            <!-- Stok Barang -->
            <a href="/stok" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/stok'),
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/stok')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Stok Barang</span>
            </a>

            <!-- Barang Masuk -->
            <a href="/barang-masuk" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/barang-masuk'),
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/barang-masuk')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Barang Masuk</span>
            </a>

            <!-- Barang Keluar -->
            {{-- <a href="/barang-keluar" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/barang-keluar'),
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/barang-keluar')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Barang Keluar</span>
            </a> --}}
            
             <!-- Nota Pembeli -->
            <a href="/nota-pembeli" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/nota-pembeli'),
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/nota-pembeli')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Nota Pembeli</span>
            </a>

            <!-- Buffer Stok -->
            <a href="/buffer" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/buffer'),
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/buffer')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Buffer Stok</span>
            </a>

            @if(auth()->user()->isSuperAdmin())
            <!-- Manajemen Akun -->
            <a href="/manajemen"
            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
            :class="{
                'justify-center': !open,
                'justify-start': open,
                'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/manajemen'),
                'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/manajemen')
            }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>

                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">
                    Manajemen Akun
                </span>
            </a>

            @endif

            <!-- sampah -->
            <a href="/sampah" 
               class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
               :class="{
                   'justify-center': !open, 
                   'justify-start': open,
                   'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/sampah'),
                   'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/sampah')
               }">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Sampah</span>
            </a>

            <div class="mt-8 pt-6 border-t border-slate-800/50">
                <!-- Profil Pengguna -->
                <a href="/profile" 
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 group"
                   :class="{
                       'justify-center': !open, 
                       'justify-start': open,
                       'bg-blue-600/20 text-blue-400 border-l-4 border-blue-500': currentPath.startsWith('/profile'),
                       'text-slate-300 hover:bg-slate-800/50 hover:text-white': !currentPath.startsWith('/profile')
                   }">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Profil Pengguna</span>
                </a>
            </div>
        </nav>

        <!-- Tombol Logout -->
        <div class="p-4 border-t border-slate-800/50 flex-shrink-0">
    {{-- Form logout tersembunyi --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Tombol logout yang memicu submit form --}}
    <button onclick="document.getElementById('logout-form').submit();" 
            class="w-full flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 hover:bg-red-500/20 text-red-400 hover:text-red-300" 
            :class="{'justify-center': !open, 'justify-start': open}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span x-show="open" x-transition class="text-sm font-medium whitespace-nowrap">Logout</span>
    </button>
</div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div 
        :class="open ? 'ml-72' : 'ml-20'" 
        class="content-transition flex-1 min-h-screen"
    >
        <!-- Header dengan Tombol Toggle Mobile -->
        <div class="sticky top-0 z-40 bg-[#F8FAFC]/80 backdrop-blur-sm border-b border-slate-200 flex items-center justify-between px-4 py-3 lg:hidden">
            <button @click="open = !open" class="p-2 rounded-lg bg-white shadow-md text-slate-600 hover:text-blue-600 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-sm font-semibold text-slate-700">Admin SIGAP</p>
                    <p class="text-xs text-slate-500">Inventory Manager</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg">
                    A
                </div>
            </div>
        </div>

        <main class="p-4 md:p-8">
            {{ $slot }}
            
            {{-- Contoh konten dashboard untuk preview --}}
            @if($slot->isEmpty())
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
                        <p class="text-slate-500 mt-1">Selamat datang kembali, Admin</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white rounded-xl px-4 py-2 shadow-sm border border-slate-200">
                            <span class="text-sm text-slate-500">📅 April 2026</span>
                        </div>
                        <div class="bg-white rounded-xl px-4 py-2 shadow-sm border border-slate-200 flex items-center gap-2">
                            <span class="text-lg">☁️</span>
                            <span class="text-sm font-semibold">30°C</span>
                            <span class="text-xs text-slate-500">Berawan</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm">Total SKU</p>
                                <p class="text-3xl font-bold text-slate-800 mt-1">0</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-4">Total barang dalam sistem</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm">Nilai Stok</p>
                                <p class="text-3xl font-bold text-slate-800 mt-1">Rp0</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-4">Total nilai persediaan</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm">Barang Keluar</p>
                                <p class="text-3xl font-bold text-slate-800 mt-1">0</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-4">Total barang keluar periode ini</p>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all duration-300 hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-500 text-sm">Buffer Stok</p>
                                <p class="text-3xl font-bold text-slate-800 mt-1">0</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-4">Barang perlu restock</p>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-slate-800">6 Minggu Terakhir (Jutaan Rupiah)</h3>
                            <span class="text-xs text-slate-400">Barang Keluar</span>
                        </div>
                        <canvas id="weeklyChart" height="200"></canvas>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-slate-800">Stok per Kategori</h3>
                            <span class="text-xs text-slate-400">Unit Tersedia</span>
                        </div>
                        <canvas id="categoryChart" height="200"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-slate-600">Tidak ada aktivitas terbaru</span>
                            </div>
                            <span class="text-xs text-slate-400">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Chart 6 minggu terakhir
                    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
                    new Chart(weeklyCtx, {
                        type: 'line',
                        data: {
                            labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Minggu 6'],
                            datasets: [{
                                label: 'Nilai (Juta Rp)',
                                data: [0, 0, 0, 0, 0, 0],
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { position: 'top' }
                            }
                        }
                    });

                    // Chart stok per kategori
                    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                    new Chart(categoryCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Elektronik', 'Fashion', 'Makanan', 'Minuman', 'Lainnya'],
                            datasets: [{
                                label: 'Unit Tersedia',
                                data: [0, 0, 0, 0, 0],
                                backgroundColor: '#8B5CF6',
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { position: 'top' }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#E2E8F0' } }
                            }
                        }
                    });
                });
            </script>
            @endif
        </main>
    </div>
</div>

<!-- Alpine.js CDN jika diperlukan -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    /* Untuk mencegah flash sebelum Alpine.js load */
    [x-cloak] { display: none !important; }
</style>
</body>
</html>