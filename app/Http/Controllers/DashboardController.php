<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\NotaPembeli;
use App\Models\StokBarang;
use App\Models\StokOpname;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $sekarang = now();

        $awalBulanIni = $sekarang->copy()->startOfMonth();
        $akhirBulanIni = $sekarang->copy()->endOfMonth();

        $awalBulanLalu = $sekarang->copy()->subMonthNoOverflow()->startOfMonth();
        $akhirBulanLalu = $sekarang->copy()->subMonthNoOverflow()->endOfMonth();

        $awalEnamBulan = $sekarang->copy()->subMonths(5)->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | LABEL 6 BULAN TERAKHIR
        |--------------------------------------------------------------------------
        */
        $periodeLabelsRaw = [];
        $periodeLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = $sekarang->copy()->startOfMonth()->subMonths($i);

            $periodeLabelsRaw[] = $bulan->format('Y-m');
            $periodeLabels[] = $bulan->translatedFormat('M Y');
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK BARANG MASUK
        |--------------------------------------------------------------------------
        */
        $barangMasukPerBulan = BarangMasuk::query()
            ->selectRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m') AS periode")
            ->selectRaw('SUM(jumlah) AS total')
            ->whereBetween('tanggal_masuk', [$awalEnamBulan, $akhirBulanIni])
            ->groupByRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m')")
            ->orderBy('periode')
            ->get()
            ->pluck('total', 'periode');

        /*
        |--------------------------------------------------------------------------
        | GRAFIK BARANG KELUAR
        |--------------------------------------------------------------------------
        | Barang keluar tetap berasal dari detail penjualan pada nota pembeli.
        */
        $barangKeluarPerBulan = BarangKeluar::query()
            ->join(
                'nota_pembeli',
                'barang_keluar.nota_pembeli_id',
                '=',
                'nota_pembeli.id'
            )
            ->whereNull('nota_pembeli.deleted_at')
            ->whereBetween(
                'nota_pembeli.tanggal_keluar',
                [$awalEnamBulan, $akhirBulanIni]
            )
            ->selectRaw("DATE_FORMAT(nota_pembeli.tanggal_keluar, '%Y-%m') AS periode")
            ->selectRaw('SUM(barang_keluar.jumlah) AS total')
            ->groupByRaw("DATE_FORMAT(nota_pembeli.tanggal_keluar, '%Y-%m')")
            ->orderBy('periode')
            ->get()
            ->pluck('total', 'periode');

        /*
        |--------------------------------------------------------------------------
        | GRAFIK OMZET
        |--------------------------------------------------------------------------
        */
        $penjualanPerBulan = NotaPembeli::query()
            ->selectRaw("DATE_FORMAT(tanggal_keluar, '%Y-%m') AS periode")
            ->selectRaw('SUM(total_harga) AS total_omzet')
            ->whereBetween('tanggal_keluar', [$awalEnamBulan, $akhirBulanIni])
            ->groupByRaw("DATE_FORMAT(tanggal_keluar, '%Y-%m')")
            ->orderBy('periode')
            ->get()
            ->pluck('total_omzet', 'periode');

        /*
        |--------------------------------------------------------------------------
        | MAPPING DATA GRAFIK
        |--------------------------------------------------------------------------
        */
        $masukData = [];
        $keluarData = [];
        $penjualanData = [];

        foreach ($periodeLabelsRaw as $periode) {
            $masukData[] = (int) $barangMasukPerBulan->get($periode, 0);
            $keluarData[] = (int) $barangKeluarPerBulan->get($periode, 0);

            $omzet = (float) $penjualanPerBulan->get($periode, 0);
            $penjualanData[] = $omzet > 0
                ? round($omzet / 1000000, 1)
                : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN INVENTORI BULAN INI
        |--------------------------------------------------------------------------
        */
        $totalSku = StokBarang::count();

        $totalNilaiStok = (float) StokBarang::query()
            ->sum(DB::raw('stok * harga_beli'));

        $totalMasukBulanIni = (int) BarangMasuk::query()
            ->whereBetween('tanggal_masuk', [$awalBulanIni, $akhirBulanIni])
            ->sum('jumlah');

        $totalKeluarBulanIni = (int) BarangKeluar::query()
            ->join(
                'nota_pembeli',
                'barang_keluar.nota_pembeli_id',
                '=',
                'nota_pembeli.id'
            )
            ->whereNull('nota_pembeli.deleted_at')
            ->whereBetween(
                'nota_pembeli.tanggal_keluar',
                [$awalBulanIni, $akhirBulanIni]
            )
            ->sum('barang_keluar.jumlah');

        $arusBersihBulanIni =
            $totalMasukBulanIni - $totalKeluarBulanIni;

        /*
        |--------------------------------------------------------------------------
        | PERHITUNGAN KEUANGAN
        |--------------------------------------------------------------------------
        |
        | Dengan struktur database saat ini, barang_keluar menyimpan harga jual
        | (harga_satuan), tetapi tidak menyimpan harga modal historis saat transaksi.
        |
        | Karena itu nilai modal menggunakan harga_beli TERKINI pada stok_barang.
        | Hasil ini ditampilkan sebagai ESTIMASI laba/rugi inventori.
        |
        | Estimasi hasil =
        | laba kotor penjualan - nilai pengurangan stok opname.
        |
        */
        $keuanganBulanIni = $this->hitungKinerjaKeuangan(
            $awalBulanIni,
            $akhirBulanIni
        );

        $keuanganBulanLalu = $this->hitungKinerjaKeuangan(
            $awalBulanLalu,
            $akhirBulanLalu
        );

        $totalOmzetBulanIni =
            $keuanganBulanIni['omzet'];

        $modalTerjualBulanIni =
            $keuanganBulanIni['modal_terjual'];

        $labaKotorBulanIni =
            $keuanganBulanIni['laba_kotor'];

        $nilaiPenguranganOpnameBulanIni =
            $keuanganBulanIni['nilai_pengurangan_opname'];

        $hasilEstimasiBulanIni =
            $keuanganBulanIni['hasil_estimasi'];

        $marginEstimasiBulanIni =
            $keuanganBulanIni['margin_estimasi'];

        if ($hasilEstimasiBulanIni > 0) {
            $statusKeuangan = 'untung';
        } elseif ($hasilEstimasiBulanIni < 0) {
            $statusKeuangan = 'rugi';
        } else {
            $statusKeuangan = 'netral';
        }

        $perubahanHasilNominal =
            $hasilEstimasiBulanIni -
            $keuanganBulanLalu['hasil_estimasi'];

        $perubahanHasilPersen = null;

        if (abs($keuanganBulanLalu['hasil_estimasi']) > 0) {
            $perubahanHasilPersen = round(
                (
                    $perubahanHasilNominal /
                    abs($keuanganBulanLalu['hasil_estimasi'])
                ) * 100,
                1
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIK TRANSAKSI
        |--------------------------------------------------------------------------
        */
        $transaksiBulanIni = NotaPembeli::query()
            ->whereBetween('tanggal_keluar', [$awalBulanIni, $akhirBulanIni])
            ->count();

        $rataRataNotaBulanIni =
            $transaksiBulanIni > 0
                ? $totalOmzetBulanIni / $transaksiBulanIni
                : 0;

        /*
        |--------------------------------------------------------------------------
        | STATUS STOK
        |--------------------------------------------------------------------------
        */
        $stokKritisCount = StokBarang::query()
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->count();

        $stokAmanCount = StokBarang::query()
            ->whereColumn('stok', '>', 'stok_minimum')
            ->count();

        $persentaseStokAman =
            $totalSku > 0
                ? round(($stokAmanCount / $totalSku) * 100)
                : 0;

        $stokKritis = StokBarang::query()
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderByRaw('(stok_minimum - stok) DESC')
            ->orderBy('stok')
            ->limit(5)
            ->get([
                'id',
                'kode_barang',
                'nama_barang',
                'stok',
                'stok_minimum',
            ]);

        /*
        |--------------------------------------------------------------------------
        | PRODUK TERLARIS BULAN INI
        |--------------------------------------------------------------------------
        */
        $produkTerlaris = BarangKeluar::query()
            ->join(
                'nota_pembeli',
                'barang_keluar.nota_pembeli_id',
                '=',
                'nota_pembeli.id'
            )
            ->join(
                'stok_barang',
                'barang_keluar.stok_barang_id',
                '=',
                'stok_barang.id'
            )
            ->whereNull('nota_pembeli.deleted_at')
            ->whereBetween(
                'nota_pembeli.tanggal_keluar',
                [$awalBulanIni, $akhirBulanIni]
            )
            ->select([
                'stok_barang.id',
                'stok_barang.kode_barang',
                'stok_barang.nama_barang',
            ])
            ->selectRaw('SUM(barang_keluar.jumlah) AS total_terjual')
            ->selectRaw(
                'SUM(barang_keluar.jumlah * barang_keluar.harga_satuan) AS omzet_produk'
            )
            ->groupBy(
                'stok_barang.id',
                'stok_barang.kode_barang',
                'stok_barang.nama_barang'
            )
            ->orderByDesc('total_terjual')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | DATA PENDUKUNG INSIGHT
        |--------------------------------------------------------------------------
        */
        $rasioKeluarTerhadapMasuk =
            $totalMasukBulanIni > 0
                ? round(
                    ($totalKeluarBulanIni / $totalMasukBulanIni) * 100,
                    1
                )
                : null;

        return view('dashboard', compact(
            'periodeLabels',
            'masukData',
            'keluarData',
            'penjualanData',
            'totalSku',
            'totalNilaiStok',
            'totalMasukBulanIni',
            'totalKeluarBulanIni',
            'arusBersihBulanIni',
            'totalOmzetBulanIni',
            'modalTerjualBulanIni',
            'labaKotorBulanIni',
            'nilaiPenguranganOpnameBulanIni',
            'hasilEstimasiBulanIni',
            'marginEstimasiBulanIni',
            'statusKeuangan',
            'perubahanHasilNominal',
            'perubahanHasilPersen',
            'transaksiBulanIni',
            'rataRataNotaBulanIni',
            'stokKritisCount',
            'stokAmanCount',
            'persentaseStokAman',
            'stokKritis',
            'produkTerlaris',
            'rasioKeluarTerhadapMasuk'
        ));
    }

    /**
     * Menghitung estimasi kinerja keuangan inventory untuk satu periode.
     *
     * CATATAN:
     * Harga modal memakai stok_barang.harga_beli terkini karena tabel
     * barang_keluar belum menyimpan harga modal historis saat transaksi.
     */
    private function hitungKinerjaKeuangan(
        Carbon $tanggalAwal,
        Carbon $tanggalAkhir
    ): array {
        $penjualan = BarangKeluar::query()
            ->join(
                'nota_pembeli',
                'barang_keluar.nota_pembeli_id',
                '=',
                'nota_pembeli.id'
            )
            ->join(
                'stok_barang',
                'barang_keluar.stok_barang_id',
                '=',
                'stok_barang.id'
            )
            ->whereNull('nota_pembeli.deleted_at')
            ->whereBetween(
                'nota_pembeli.tanggal_keluar',
                [$tanggalAwal, $tanggalAkhir]
            )
            ->selectRaw(
                'COALESCE(SUM(barang_keluar.jumlah * barang_keluar.harga_satuan), 0) AS omzet'
            )
            ->selectRaw(
                'COALESCE(SUM(barang_keluar.jumlah * stok_barang.harga_beli), 0) AS modal_terjual'
            )
            ->selectRaw(
                'COALESCE(SUM(barang_keluar.jumlah * (barang_keluar.harga_satuan - stok_barang.harga_beli)), 0) AS laba_kotor'
            )
            ->first();

        $omzet =
            (float) ($penjualan->omzet ?? 0);

        $modalTerjual =
            (float) ($penjualan->modal_terjual ?? 0);

        $labaKotor =
            (float) ($penjualan->laba_kotor ?? 0);

        /*
        |--------------------------------------------------------------------------
        | NILAI PENGURANGAN STOK OPNAME
        |--------------------------------------------------------------------------
        |
        | Semua opname jenis pengurangan dianggap sebagai pengurang nilai
        | inventori pada dashboard. Ini cocok untuk rusak/hilang/selisih stok.
        |
        */
        $nilaiPenguranganOpname = (float) StokOpname::query()
            ->join(
                'stok_barang',
                'stok_opnames.stok_barang_id',
                '=',
                'stok_barang.id'
            )
            ->where(
                'stok_opnames.jenis_opname',
                'pengurangan'
            )
            ->whereBetween(
                'stok_opnames.tanggal',
                [$tanggalAwal, $tanggalAkhir]
            )
            ->sum(
                DB::raw(
                    'stok_opnames.jumlah_perubahan * stok_barang.harga_beli'
                )
            );

        $hasilEstimasi =
            $labaKotor - $nilaiPenguranganOpname;

        $marginEstimasi =
            $omzet > 0
                ? round(($hasilEstimasi / $omzet) * 100, 1)
                : 0;

        return [
            'omzet' => $omzet,
            'modal_terjual' => $modalTerjual,
            'laba_kotor' => $labaKotor,
            'nilai_pengurangan_opname' => $nilaiPenguranganOpname,
            'hasil_estimasi' => $hasilEstimasi,
            'margin_estimasi' => $marginEstimasi,
        ];
    }
}
