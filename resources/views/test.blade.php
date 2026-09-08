<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    // Menampilkan daftar barang keluar
    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with('stokBarang')->orderBy('tanggal_keluar', 'desc');

        // Fitur pencarian (no nota atau tujuan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%");
            });
        }

        $barangKeluar = $query->paginate(10);

        // Hitung jumlah transaksi hari ini
        $keluarHariIni = BarangKeluar::whereDate('tanggal_keluar', today())->count();

        return view('barang-keluar', compact('barangKeluar', 'keluarHariIni'));
    }

    // Menampilkan form tambah barang keluar
    public function barangkeluarcreate()
    {
        $stokBarang = StokBarang::orderBy('nama_barang')->get();
        return view('barang-keluar-form', compact('stokBarang'));
    }

    // Menyimpan data barang keluar baru
    public function barangkeluarstore(Request $request)
    {
        $request->validate([
            'stok_barang_id' => 'required|exists:stok_barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'tujuan' => 'required|string|max:255',
        ]);

        // (Opsional) Kurangi stok barang di tabel stok_barang
        $stok = StokBarang::findOrFail($request->stok_barang_id);
        if ($stok->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi!']);
        }
        $stok->decrement('stok', $request->jumlah);

        BarangKeluar::create([
            'stok_barang_id' => $request->stok_barang_id,
            'jumlah' => $request->jumlah,
            'tanggal_keluar' => $request->tanggal_keluar,
            'tujuan' => $request->tujuan,
            // 'petugas' bisa diisi dengan auth()->user()->name jika ada
        ]);

        return redirect()->route('barang-keluar')->with('success', 'Barang keluar berhasil dicatat.');
    }

    // (Opsional) Hapus data barang keluar
    public function barangkeluardestroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);
        // Kembalikan stok jika diperlukan
        $barangKeluar->stokBarang->increment('stok', $barangKeluar->jumlah);
        $barangKeluar->delete();

        return redirect()->route('barang-keluar')->with('success', 'Data berhasil dihapus.');
    }
}