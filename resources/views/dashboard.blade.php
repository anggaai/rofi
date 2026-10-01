<div class="relative min-h-screen bg-gradient-to-b from-blue-300 via-blue-200 to-white z-0">

  

    <x-sidebar>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="space-y-5">


                {{-- ============================================================ --}}
                {{-- HEADER --}}
                {{-- ============================================================ --}}

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        <div>

                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-[0.16em]">
                                Inventory Barang Mustikajaya
                            </span>

                            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">
                                Dashboard Utama
                            </h1>

                            <p class="text-sm text-slate-500 mt-1">
                                Ringkasan stok, penjualan, dan kondisi inventori secara otomatis.
                            </p>

                        </div>


                        <div class="flex flex-wrap items-center gap-2">

                            <div class="px-3 py-2 bg-slate-100 rounded-xl text-xs font-semibold text-slate-600">
                                📅 {{ now()->translatedFormat('F Y') }}
                            </div>

                            <div
                                class="px-3 py-2 rounded-xl text-xs font-bold
                                {{ $stokKritisCount > 0
                                    ? 'bg-rose-50 text-rose-700'
                                    : 'bg-emerald-50 text-emerald-700' }}"
                            >
                                {{ $stokKritisCount > 0
                                    ? '⚠️ ' . $stokKritisCount . ' stok kritis'
                                    : '✅ Semua stok aman' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ============================================================ --}}
                {{-- KEUANGAN + SMART INSIGHT --}}
                {{-- ============================================================ --}}

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">


                    {{-- ======================================================== --}}
                    {{-- CARD UNTUNG / RUGI --}}
                    {{-- ======================================================== --}}

                    <div
                        class="xl:col-span-2 rounded-2xl border shadow-sm overflow-hidden
                        {{ $statusKeuangan === 'untung'
                            ? 'bg-emerald-950 border-emerald-900'
                            : ($statusKeuangan === 'rugi'
                                ? 'bg-rose-950 border-rose-900'
                                : 'bg-slate-900 border-slate-800') }}"
                    >

                        <div class="p-6">

                            <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-6 items-center">


                                {{-- NILAI --}}
                                <div>

                                    <div class="flex flex-wrap items-center gap-2 mb-3">

                                        <span
                                            class="text-[11px] uppercase tracking-[0.16em] font-bold
                                            {{ $statusKeuangan === 'untung'
                                                ? 'text-emerald-300'
                                                : ($statusKeuangan === 'rugi'
                                                    ? 'text-rose-300'
                                                    : 'text-slate-300') }}"
                                        >
                                            Estimasi Kinerja Inventory Bulan Ini
                                        </span>


                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                            {{ $statusKeuangan === 'untung'
                                                ? 'bg-emerald-400/15 text-emerald-300'
                                                : ($statusKeuangan === 'rugi'
                                                    ? 'bg-rose-400/15 text-rose-300'
                                                    : 'bg-white/10 text-slate-300') }}"
                                        >
                                            @if($statusKeuangan === 'untung')
                                                Untung
                                            @elseif($statusKeuangan === 'rugi')
                                                Rugi
                                            @else
                                                Seimbang
                                            @endif
                                        </span>

                                    </div>


                                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight">

                                        @if($hasilEstimasiBulanIni < 0)
                                            -Rp {{ number_format(abs($hasilEstimasiBulanIni), 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($hasilEstimasiBulanIni, 0, ',', '.') }}
                                        @endif

                                    </h2>


                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3">

                                        <span class="text-sm text-white/70">
                                            Margin estimasi:
                                            <strong class="text-white">
                                                {{ number_format($marginEstimasiBulanIni, 1, ',', '.') }}%
                                            </strong>
                                        </span>


                                        @if($perubahanHasilPersen !== null)

                                            <span
                                                class="text-xs font-bold
                                                {{ $perubahanHasilNominal >= 0
                                                    ? 'text-emerald-300'
                                                    : 'text-rose-300' }}"
                                            >
                                                {{ $perubahanHasilNominal >= 0 ? '↗' : '↘' }}
                                                {{ number_format(abs($perubahanHasilPersen), 1, ',', '.') }}%
                                                dari bulan lalu
                                            </span>

                                        @else

                                            <span class="text-xs text-white/50">
                                                Belum ada pembanding bulan lalu
                                            </span>

                                        @endif

                                    </div>


                                    {{-- RINCIAN KEUANGAN --}}
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-6">

                                        <div class="bg-white/10 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-white/55">
                                                Omzet
                                            </p>

                                            <p class="text-sm font-extrabold text-white mt-1">
                                                Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}
                                            </p>
                                        </div>


                                        <div class="bg-white/10 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-white/55">
                                                Modal Terjual
                                            </p>

                                            <p class="text-sm font-extrabold text-white mt-1">
                                                Rp {{ number_format($modalTerjualBulanIni, 0, ',', '.') }}
                                            </p>
                                        </div>


                                        <div class="bg-white/10 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-white/55">
                                                Laba Kotor
                                            </p>

                                            <p class="text-sm font-extrabold text-white mt-1">
                                                Rp {{ number_format($labaKotorBulanIni, 0, ',', '.') }}
                                            </p>
                                        </div>


                                        <div class="bg-white/10 rounded-xl p-3">
                                            <p class="text-[10px] uppercase tracking-wider text-white/55">
                                                Pengurangan Opname
                                            </p>

                                            <p class="text-sm font-extrabold text-white mt-1">
                                                Rp {{ number_format($nilaiPenguranganOpnameBulanIni, 0, ',', '.') }}
                                            </p>
                                        </div>

                                    </div>

                                </div>


                                {{-- MASCOT --}}
                                <div class="flex md:flex-col items-center md:items-end justify-center gap-3">

                                    @if($statusKeuangan === 'untung')

                                        <div
                                            class="mascot-profit text-6xl md:text-7xl select-none"
                                            aria-label="Kondisi untung"
                                        >
                                            💪
                                        </div>

                                        <div class="md:text-right">
                                            <p class="text-sm font-bold text-emerald-200">
                                                Mantap, tetap jaga ritmenya!
                                            </p>

                                            <p class="text-xs text-emerald-100/60 mt-1 max-w-[190px]">
                                                Margin penjualan masih mampu menutup penyesuaian stok bulan ini.
                                            </p>
                                        </div>

                                    @elseif($statusKeuangan === 'rugi')

                                        <div
                                            class="mascot-loss text-6xl md:text-7xl select-none"
                                            aria-label="Kondisi rugi"
                                        >
                                            😮‍💨
                                        </div>

                                        <div class="md:text-right">
                                            <p class="text-sm font-bold text-rose-200">
                                                Belum ideal, tapi masih bisa dibenahi.
                                            </p>

                                            <p class="text-xs text-rose-100/60 mt-1 max-w-[200px]">
                                                Cek harga jual, margin barang, dan pengurangan stok opname.
                                            </p>
                                        </div>

                                    @else

                                        <div
                                            class="mascot-neutral text-6xl md:text-7xl select-none"
                                            aria-label="Kondisi seimbang"
                                        >
                                            🙂
                                        </div>

                                        <div class="md:text-right">
                                            <p class="text-sm font-bold text-slate-200">
                                                Posisi masih seimbang.
                                            </p>

                                            <p class="text-xs text-slate-300/60 mt-1 max-w-[190px]">
                                                Pantau transaksi berikutnya untuk melihat arah performa bulan ini.
                                            </p>
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================== --}}
                    {{-- SMART INSIGHT --}}
                    {{-- ======================================================== --}}

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-[10px] uppercase tracking-[0.15em] font-bold text-indigo-500">
                                    Smart Insight
                                </p>

                                <h3 class="text-lg font-extrabold text-slate-800 mt-1">
                                    Ringkasan Pintar
                                </h3>
                            </div>

                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-xl">
                                ✨
                            </div>

                        </div>


                        <div class="space-y-3 mt-5">

                            {{-- PRODUK TERLARIS --}}
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">

                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    Produk terlaris
                                </p>

                                @if($produkTerlaris)

                                    <p class="text-sm font-extrabold text-slate-800 mt-1">
                                        {{ $produkTerlaris->nama_barang }}
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ number_format($produkTerlaris->total_terjual) }} unit terjual bulan ini
                                    </p>

                                @else

                                    <p class="text-sm font-semibold text-slate-500 mt-1">
                                        Belum ada penjualan bulan ini.
                                    </p>

                                @endif

                            </div>


                            {{-- ARUS BARANG --}}
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">

                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    Arus persediaan
                                </p>

                                @if($arusBersihBulanIni > 0)

                                    <p class="text-sm font-bold text-emerald-700 mt-1">
                                        +{{ number_format($arusBersihBulanIni) }} unit
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        Barang masuk lebih besar daripada barang keluar.
                                    </p>

                                @elseif($arusBersihBulanIni < 0)

                                    <p class="text-sm font-bold text-amber-700 mt-1">
                                        {{ number_format($arusBersihBulanIni) }} unit
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        Barang keluar lebih besar daripada pasokan masuk.
                                    </p>

                                @else

                                    <p class="text-sm font-bold text-slate-700 mt-1">
                                        Seimbang
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        Jumlah barang masuk dan keluar sama.
                                    </p>

                                @endif

                            </div>


                            {{-- STATUS BUFFER --}}
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">

                                <div class="flex items-center justify-between gap-3">

                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                            Kondisi stok
                                        </p>

                                        <p class="text-sm font-extrabold text-slate-800 mt-1">
                                            {{ $persentaseStokAman }}% aman
                                        </p>
                                    </div>

                                    <div
                                        class="text-2xl"
                                        aria-hidden="true"
                                    >
                                        {{ $stokKritisCount > 0 ? '⚠️' : '✅' }}
                                    </div>

                                </div>


                                <div class="h-2 bg-slate-200 rounded-full overflow-hidden mt-3">

                                    <div
                                        class="h-full rounded-full {{ $stokKritisCount > 0 ? 'bg-amber-500' : 'bg-emerald-500' }}"
                                        style="width: {{ min(100, max(0, $persentaseStokAman)) }}%"
                                    ></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ============================================================ --}}
                {{-- SATU CARD RINGKASAN INVENTORI --}}
                {{-- ============================================================ --}}

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">

                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                Barang Masuk
                            </p>

                            <p class="text-xl font-black text-emerald-600 mt-1">
                                {{ number_format($totalMasukBulanIni) }}
                            </p>

                            <p class="text-xs text-slate-400">unit bulan ini</p>
                        </div>


                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                Barang Keluar
                            </p>

                            <p class="text-xl font-black text-rose-600 mt-1">
                                {{ number_format($totalKeluarBulanIni) }}
                            </p>

                            <p class="text-xs text-slate-400">unit bulan ini</p>
                        </div>


                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                Nilai Stok
                            </p>

                            <p class="text-lg font-black text-slate-800 mt-1">
                                Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-slate-400">{{ $totalSku }} SKU aktif</p>
                        </div>


                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                Transaksi
                            </p>

                            <p class="text-xl font-black text-indigo-600 mt-1">
                                {{ number_format($transaksiBulanIni) }}
                            </p>

                            <p class="text-xs text-slate-400">nota bulan ini</p>
                        </div>


                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                Rata-rata Nota
                            </p>

                            <p class="text-lg font-black text-slate-800 mt-1">
                                Rp {{ number_format($rataRataNotaBulanIni, 0, ',', '.') }}
                            </p>

                            <p class="text-xs text-slate-400">per transaksi</p>
                        </div>


                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">
                                Keluar : Masuk
                            </p>

                            <p class="text-xl font-black text-slate-800 mt-1">

                                @if($rasioKeluarTerhadapMasuk !== null)

                                    {{ number_format($rasioKeluarTerhadapMasuk, 1, ',', '.') }}%

                                @else

                                    -

                                @endif

                            </p>

                            <p class="text-xs text-slate-400">rasio bulan ini</p>
                        </div>

                    </div>

                </div>


                {{-- ============================================================ --}}
                {{-- GRAFIK RINGKAS --}}
                {{-- ============================================================ --}}

                <div class="dashboard-lazy-section grid grid-cols-1 xl:grid-cols-2 gap-5">

                    {{-- ARUS BARANG --}}
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <h3 class="font-extrabold text-slate-800">
                                    Arus Barang
                                </h3>

                                <p class="text-xs text-slate-400 mt-1">
                                    Barang masuk dan keluar selama 6 bulan
                                </p>
                            </div>

                            <span class="text-xl">
                                📦
                            </span>

                        </div>


                        <div
                            id="chartArusBarang"
                            class="mt-3"
                            style="width:100%; height:235px;"
                        ></div>

                    </div>


                    {{-- PENJUALAN --}}
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <h3 class="font-extrabold text-slate-800">
                                    Omzet Penjualan
                                </h3>

                                <p class="text-xs text-slate-400 mt-1">
                                    Omzet bulanan dalam juta rupiah
                                </p>
                            </div>

                            <span class="text-xl">
                                💰
                            </span>

                        </div>


                        <div
                            id="chartPenjualan"
                            class="mt-3"
                            style="width:100%; height:235px;"
                        ></div>

                    </div>

                </div>


                {{-- ============================================================ --}}
                {{-- STOK KRITIS + CATATAN --}}
                {{-- ============================================================ --}}

                <div class="dashboard-lazy-section grid grid-cols-1 xl:grid-cols-3 gap-5">


                    {{-- STOK KRITIS --}}
                    <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                        <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4">

                            <div>

                                <h3 class="font-extrabold text-slate-800">
                                    Stok Perlu Perhatian
                                </h3>

                                <p class="text-xs text-slate-400 mt-1">
                                    Maksimal 5 barang yang berada pada atau di bawah stok minimum
                                </p>

                            </div>


                            <a
                                href="{{ route('buffer.index') }}"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-700"
                            >
                                Buka Buffer →
                            </a>

                        </div>


                        @forelse($stokKritis as $barang)

                            @php
                                $kekurangan = max(
                                    0,
                                    $barang->stok_minimum - $barang->stok
                                );
                            @endphp

                            <div class="px-5 py-3 border-b border-slate-100 last:border-b-0">

                                <div class="flex items-center justify-between gap-4">

                                    <div class="min-w-0">

                                        <p class="text-sm font-bold text-slate-700 truncate">
                                            {{ $barang->nama_barang }}
                                        </p>

                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $barang->kode_barang }}
                                        </p>

                                    </div>


                                    <div class="text-right shrink-0">

                                        <p class="text-sm font-extrabold text-rose-600">
                                            {{ number_format($barang->stok) }} unit
                                        </p>

                                        <p class="text-[11px] text-slate-400">
                                            minimum {{ number_format($barang->stok_minimum) }}

                                            @if($kekurangan > 0)
                                                • kurang {{ number_format($kekurangan) }}
                                            @endif
                                        </p>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="p-8 text-center">

                                <div class="text-4xl">
                                    ✅
                                </div>

                                <p class="text-sm font-bold text-emerald-700 mt-2">
                                    Tidak ada stok kritis.
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Seluruh barang masih berada di atas stok minimum.
                                </p>

                            </div>

                        @endforelse

                    </div>


                    {{-- CATATAN KEUANGAN --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">

                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-xl">
                            💡
                        </div>

                        <h3 class="font-extrabold text-amber-950 mt-4">
                            Cara membaca untung/rugi
                        </h3>

                        <p class="text-xs text-amber-900/70 leading-relaxed mt-2">
                            Dashboard menghitung estimasi dari margin penjualan lalu dikurangi nilai stok
                            yang berkurang melalui opname.
                        </p>

                        <div class="mt-4 p-3 bg-white/70 rounded-xl border border-amber-200">

                            <p class="text-[11px] text-amber-900/70 leading-relaxed">
                                Perhitungan keuntungan dan kerugian dilakukan secara otomatis berdasarkan
                                data transaksi penjualan, harga beli barang, serta penyesuaian stok yang
                                tersimpan pada sistem. Informasi ini digunakan untuk membantu memantau
                                kondisi keuangan dan kinerja persediaan secara berkala.
                            </p>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </x-sidebar>

</div>


{{-- ============================================================ --}}
{{-- APEXCHARTS --}}
{{-- ============================================================ --}}

<script
    src="https://cdn.jsdelivr.net/npm/apexcharts"
    defer
></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | Jika ApexCharts gagal dimuat, dashboard lain tetap bisa dipakai.
        |--------------------------------------------------------------------------
        */
        if (typeof ApexCharts === 'undefined') {
            console.warn('ApexCharts tidak berhasil dimuat.');
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK ARUS BARANG
        |--------------------------------------------------------------------------
        */
        const chartArusElement =
            document.querySelector('#chartArusBarang');


        if (chartArusElement) {

            const optionsArusBarang = {

                chart: {
                    type: 'line',
                    height: 220,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    animations: {
                        enabled: false
                    },
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },

                series: [
                    {
                        name: 'Barang Masuk',
                        data: @json($masukData)
                    },
                    {
                        name: 'Barang Keluar',
                        data: @json($keluarData)
                    }
                ],

                colors: [
                    '#10b981',
                    '#f43f5e'
                ],

                stroke: {
                    width: 2.5,
                    curve: 'smooth',
                    lineCap: 'round'
                },

                markers: {
                    size: 3,
                    strokeWidth: 2,
                    strokeColors: '#ffffff',
                    hover: {
                        size: 5
                    }
                },

                dataLabels: {
                    enabled: false
                },

                grid: {
                    strokeDashArray: 4,
                    borderColor: '#e2e8f0',
                    padding: {
                        top: 0,
                        right: 5,
                        bottom: 0,
                        left: 5
                    }
                },

                xaxis: {
                    categories: @json($periodeLabels),
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
                        }
                    }
                },

                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
                        },
                        formatter: function (value) {
                            return Math.round(value);
                        }
                    }
                },

                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '11px',
                    markers: {
                        width: 8,
                        height: 8,
                        radius: 8
                    }
                },

                tooltip: {
                    shared: true,
                    intersect: false,
                    theme: 'light',
                    y: {
                        formatter: function (value) {
                            return Number(value).toLocaleString('id-ID') + ' unit';
                        }
                    }
                }

            };


            new ApexCharts(
                chartArusElement,
                optionsArusBarang
            ).render();

        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK OMZET
        |--------------------------------------------------------------------------
        */
        const chartPenjualanElement =
            document.querySelector('#chartPenjualan');


        if (chartPenjualanElement) {

            const optionsPenjualan = {

                chart: {
                    type: 'bar',
                    height: 220,
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: false
                    },
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },

                series: [
                    {
                        name: 'Omzet',
                        data: @json($penjualanData)
                    }
                ],

                colors: [
                    '#6366f1'
                ],

                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: '44%'
                    }
                },

                dataLabels: {
                    enabled: false
                },

                grid: {
                    strokeDashArray: 4,
                    borderColor: '#e2e8f0',
                    padding: {
                        top: 0,
                        right: 5,
                        bottom: 0,
                        left: 5
                    }
                },

                xaxis: {
                    categories: @json($periodeLabels),
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
                        }
                    }
                },

                yaxis: {
                    min: 0,
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
                        },
                        formatter: function (value) {
                            return 'Rp ' + value + 'M';
                        }
                    }
                },

                tooltip: {
                    y: {
                        formatter: function (value) {
                            return 'Rp ' + value + ' Juta';
                        }
                    }
                }

            };


            new ApexCharts(
                chartPenjualanElement,
                optionsPenjualan
            ).render();

        }

    });
</script>
