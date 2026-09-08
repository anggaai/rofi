<div class="relative min-h-screen bg-no-repeat bg-fixed bg-cover bg-center z-0" 
     style="background-image: linear-gradient(to bottom, rgba(30, 64, 175, 0.4), rgba(255, 255, 255, 0.8)), url('{{ asset('images/bgmustika.png') }}');">  
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] pointer-events-none"></div>

    <x-sidebar>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="space-y-6">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/90 backdrop-blur-md p-5 rounded-2xl shadow-sm border border-slate-200/50">
                    <div>
                        <span class="text-xs font-semibold text-indigo-600 uppercase tracking-widest">Inventory Barang Mustikajaya</span>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Dashboard Utama</h1>
                        <p class="text-slate-500 text-sm mt-1">Real-time data performa manajemen stok dan arus keuangan Anda.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 font-medium text-sm rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ now()->translatedFormat('F Y') }}
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white/95 backdrop-blur-sm p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Barang Masuk (Bulan Ini)</p>
                                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalMasukBulanIni) }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
                                <span class="text-xs text-slate-400 font-medium inline-flex items-center mt-1">
                                    Total SKU: <strong class="ml-1">{{ $totalSku }}</strong>
                                </span>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/95 backdrop-blur-sm p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-rose-500"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Barang Keluar (Bulan Ini)</p>
                                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalKeluarBulanIni) }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
                                <span class="text-xs text-slate-400 font-medium inline-flex items-center mt-1">Logistik aktif di gudang</span>
                            </div>
                            <div class="p-3 bg-rose-50 rounded-xl text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 p-5 rounded-xl border border-slate-800 shadow-xl relative overflow-hidden">
                        <div class="absolute -right-5 -bottom-5 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl"></div>
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <p class="text-xs font-semibold text-indigo-300 uppercase tracking-wider">Total Nilai Stok</p>
                                <h3 class="text-xl font-bold text-white mt-1">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</h3>
                                <span class="text-xs text-indigo-200 font-medium bg-white/10 px-2 py-0.5 rounded-md inline-flex items-center mt-1">
                                    Omzet Bulan Ini: Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="p-3 bg-white/10 rounded-xl text-indigo-400">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/95 backdrop-blur-sm p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">📦 Barang Masuk</h3>
                            <p class="text-xs text-slate-400">Trend volume barang masuk per periode</p>
                        </div>
                        <div class="px-3 py-1.5 bg-emerald-50 rounded-lg text-emerald-600 text-xs font-semibold">
                            Total Masuk: {{ array_sum($masukData) }} pcs
                        </div>
                    </div>
                    <div id="chartBarangMasuk" style="width:100%; height:320px;"></div>
                </div>

                <div class="bg-white/95 backdrop-blur-sm p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">🚚 Barang Keluar</h3>
                            <p class="text-xs text-slate-400">Trend volume barang keluar per periode</p>
                        </div>
                        <div class="px-3 py-1.5 bg-rose-50 rounded-lg text-rose-600 text-xs font-semibold">
                            Total Keluar: {{ array_sum($keluarData) }} pcs
                        </div>
                    </div>
                    <div id="chartBarangKeluar" style="width:100%; height:320px;"></div>
                </div>

                <div class="bg-white/95 backdrop-blur-sm p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">💰 Penjualan Per Bulan</h3>
                            <p class="text-xs text-slate-400">Omzet penjualan dalam Juta Rupiah</p>
                        </div>
                        <div class="px-3 py-1.5 bg-indigo-50 rounded-lg text-indigo-600 text-xs font-semibold">
                            Total Omzet: Rp {{ array_sum($penjualanData) }} Juta
                        </div>
                    </div>
                    <div id="chartPenjualan" style="width:100%; height:320px;"></div>
                </div>

                {{-- <div class="bg-white/95 backdrop-blur-sm p-5 rounded-xl border border-slate-200 shadow-sm">
                    <div class="mb-4">
                        <h3 class="font-bold text-slate-800 text-lg">📊 Kategori Stok</h3>
                        <p class="text-xs text-slate-400">Penyebaran produk berdasarkan kategori</p>
                    </div>
                    <div id="chartKategori" style="width:100%; height:320px;"></div>
                </div> --}}

            </div> 
        </div>
    </x-sidebar>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ========== CHART 1: BARANG MASUK (AREA CHART) ==========
        var optionsMasuk = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            colors: ['#10b981'],
            stroke: { width: 3, curve: 'smooth', lineCap: 'round' },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            markers: {
                size: 6,
                colors: ['#ffffff'],
                strokeColors: '#10b981',
                strokeWidth: 2.5,
                hover: { size: 8 }
            },
            dataLabels: { enabled: false },
            grid: {
                strokeDashArray: 5,
                borderColor: '#e2e8f0',
                padding: { top: 10, right: 15, bottom: 5, left: 10 }
            },
            series: [{ name: 'Barang Masuk', data: @json($masukData) }],
            xaxis: {
                categories: @json($periodeLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#64748b', fontWeight: 500, fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#64748b', fontWeight: 500, fontSize: '11px' },
                    formatter: function(val) { return Math.round(val) + ' pcs'; }
                }
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function(val) { return val + ' pcs'; } }
            }
        };
        new ApexCharts(document.querySelector("#chartBarangMasuk"), optionsMasuk).render();

        // ========== CHART 2: BARANG KELUAR (AREA CHART) ==========
        var optionsKeluar = {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            colors: ['#f43f5e'],
            stroke: { width: 3, curve: 'smooth', lineCap: 'round' },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            markers: {
                size: 6,
                colors: ['#ffffff'],
                strokeColors: '#f43f5e',
                strokeWidth: 2.5,
                hover: { size: 8 }
            },
            dataLabels: { enabled: false },
            grid: {
                strokeDashArray: 5,
                borderColor: '#e2e8f0',
                padding: { top: 10, right: 15, bottom: 5, left: 10 }
            },
            series: [{ name: 'Barang Keluar', data: @json($keluarData) }],
            xaxis: {
                categories: @json($periodeLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#64748b', fontWeight: 500, fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#64748b', fontWeight: 500, fontSize: '11px' },
                    formatter: function(val) { return Math.round(val) + ' pcs'; }
                }
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function(val) { return val + ' pcs'; } }
            }
        };
        new ApexCharts(document.querySelector("#chartBarangKeluar"), optionsKeluar).render();

        // ========== CHART 3: PENJUALAN (BAR CHART) ==========
        var optionsPenjualan = {
            chart: {
                type: 'bar',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            colors: ['#6366f1'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '50%',
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -12,
                style: { fontSize: '11px', colors: ['#6366f1'], fontWeight: 600 },
                formatter: function(val) { return 'Rp ' + val + 'M'; }
            },
            grid: {
                strokeDashArray: 5,
                borderColor: '#e2e8f0',
                padding: { top: 25, right: 15, bottom: 5, left: 10 }
            },
            series: [{ name: 'Omzet', data: @json($penjualanData) }],
            xaxis: {
                categories: @json($periodeLabels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#64748b', fontWeight: 500, fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#64748b', fontWeight: 500, fontSize: '11px' },
                    formatter: function(val) { return "Rp " + val + "M"; }
                }
            },
            tooltip: {
                y: { formatter: function(val) { return "Rp " + val + " Juta"; } }
            }
        };
        new ApexCharts(document.querySelector("#chartPenjualan"), optionsPenjualan).render();

        // ========== CHART 4: DONUT KATEGORI ==========
        var optionsKategori = {
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#8b5cf6', '#06b6d4', '#84cc16'],
            labels: @json($kategoriLabels),
            series: @json($stokData),
            dataLabels: { enabled: false },
            legend: {
                position: 'bottom',
                fontSize: '11px',
                fontWeight: 500,
                labels: { colors: '#475569' },
                markers: { width: 10, height: 10, radius: 10 },
                itemMargin: { horizontal: 8, vertical: 5 }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '68%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Stok',
                                color: '#64748b',
                                fontSize: '11px',
                                fontWeight: 600,
                                formatter: function(w) {
                                    let total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return total.toLocaleString('id-ID') + ' pcs';
                                }
                            },
                            value: {
                                fontSize: '18px',
                                fontWeight: 700,
                                color: '#1e293b',
                                formatter: function(val) { return val.toLocaleString('id-ID'); }
                            }
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { height: 260 },
                    legend: { position: 'bottom', fontSize: '10px' }
                }
            }]
        };
        new ApexCharts(document.querySelector("#chartKategori"), optionsKategori).render();

    });
</script>