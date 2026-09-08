<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\NotaPembeli;
use App\Models\StokBarang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 6 bulan terakhir
        $periodeLabelsRaw = [];
        $periodeLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $periodeLabelsRaw[] = $bulan->format('Y-m');
            $periodeLabels[] = $bulan->format('M Y');
        }

        // 1. Barang Masuk (pakai soft delete jika ada)
        $barangMasuk = BarangMasuk::select(
                DB::raw('DATE_FORMAT(tanggal_masuk, "%Y-%m") as periode'),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('tanggal_masuk', '>=', now()->subMonths(5)->startOfMonth())
            ->whereNull('deleted_at') // asumsi model BarangMasuk pakai SoftDeletes
            ->groupBy(DB::raw('DATE_FORMAT(tanggal_masuk, "%Y-%m")'))
            ->orderBy('periode')
            ->get()
            ->pluck('total', 'periode');

        // 2. Barang Keluar (join nota_pembeli) - HANYA filter deleted_at di barang_keluar
        $barangKeluar = BarangKeluar::join('nota_pembeli', 'barang_keluar.nota_pembeli_id', '=', 'nota_pembeli.id')
            ->select(
                DB::raw("DATE_FORMAT(nota_pembeli.tanggal_keluar, '%Y-%m') as periode"),
                DB::raw("SUM(barang_keluar.jumlah) as total")
            )
            ->where('nota_pembeli.tanggal_keluar', '>=', now()->subMonths(5)->startOfMonth())
            ->whereNull('barang_keluar.deleted_at') // HANYA ini, karena nota_pembeli tidak pakai softdelete
            ->groupBy(DB::raw("DATE_FORMAT(nota_pembeli.tanggal_keluar, '%Y-%m')"))
            ->orderBy('periode', 'asc')
            ->get()
            ->pluck('total', 'periode');

        // 3. Penjualan (omzet) dari nota_pembeli (tidak ada softdelete)
        $penjualan = NotaPembeli::select(
                DB::raw('DATE_FORMAT(tanggal_keluar, "%Y-%m") as periode'),
                DB::raw('SUM(total_harga) as total_omzet')
            )
            ->where('tanggal_keluar', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy(DB::raw('DATE_FORMAT(tanggal_keluar, "%Y-%m")'))
            ->orderBy('periode')
            ->get()
            ->pluck('total_omzet', 'periode');

        // 4. Stok per kategori
        $stokKategori = StokBarang::select('kategori', DB::raw('SUM(stok) as total_stok'))
            ->groupBy('kategori')
            ->get();

        // 5. Statistik ringkasan
        $totalSku = StokBarang::count();
        $totalNilaiStok = StokBarang::sum(DB::raw('stok * harga_beli'));

        $totalMasukBulanIni = BarangMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->whereNull('deleted_at')
            ->sum('jumlah');

        $totalKeluarBulanIni = BarangKeluar::join('nota_pembeli', 'barang_keluar.nota_pembeli_id', '=', 'nota_pembeli.id')
            ->whereMonth('nota_pembeli.tanggal_keluar', now()->month)
            ->whereYear('nota_pembeli.tanggal_keluar', now()->year)
            ->whereNull('barang_keluar.deleted_at')
            ->sum('barang_keluar.jumlah');

        $totalOmzetBulanIni = NotaPembeli::whereMonth('tanggal_keluar', now()->month)
            ->whereYear('tanggal_keluar', now()->year)
            ->sum('total_harga');

        // Mapping data ke posisi bulan
        $masukData = [];
        $keluarData = [];
        $penjualanData = [];

        foreach ($periodeLabelsRaw as $prd) {
            $masukData[] = $barangMasuk->get($prd, 0);
            $keluarData[] = $barangKeluar->get($prd, 0);
            $omzet = $penjualan->get($prd, 0);
            $penjualanData[] = $omzet > 0 ? round($omzet / 1000000, 1) : 0;
        }

        $kategoriLabels = $stokKategori->pluck('kategori')->toArray();
        $stokData = $stokKategori->pluck('total_stok')->toArray();

        return view('dashboard', compact(
            'periodeLabels', 'masukData', 'keluarData',
            'penjualanData', 'kategoriLabels', 'stokData',
            'totalSku', 'totalNilaiStok', 'totalMasukBulanIni',
            'totalKeluarBulanIni', 'totalOmzetBulanIni'
        ));
    }
}