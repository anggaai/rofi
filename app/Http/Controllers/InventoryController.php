<?php

namespace App\Http\Controllers;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\NotaPembeli;
use App\Models\StokBarang;
use App\Models\StokOpname;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Psy\Readline\Interactive\Input\Buffer;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = StokBarang::query();
            if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%")
                ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        $stokBarang = $query->get();
        foreach ($stokBarang as $barang) {

    $hasil = $this->hitungRekomendasiBuffer($barang);

    $barang->rekomendasi_buffer =
        $hasil['buffer_sederhana'];

    $barang->rekomendasi_safety =
        $hasil['safety_stock'];

    $barang->data_buffer_tersedia =
        $hasil['tersedia'];

    $barang->pemakaian_rata =
        $hasil['pemakaian_rata'];

    $barang->pemakaian_maks =
        $hasil['pemakaian_maks'];

    $barang->standar_deviasi =
        $hasil['standar_deviasi'];

    $barang->lead_time_rata =
        $hasil['lead_time_rata'];

    $barang->lead_time_maks =
        $hasil['lead_time_maks'];
}
        $bufferStok = StokBarang::whereRaw('stok <= stok_minimum')->orderBy('stok', 'asc')->get();
        
        // Statistik untuk card
        $totalProduk = StokBarang::count();
        $kategoriAktif = StokBarang::distinct('kategori')->count('kategori');
        $totalNilaiAset = StokBarang::sum(DB::raw('stok * harga_beli'));
        $stokAmanCount = StokBarang::whereRaw('stok > stok_minimum')->count();
        $persentaseStokAman = $totalProduk > 0 ? round(($stokAmanCount / $totalProduk) * 100) : 0;
        $kategoriStats = StokBarang::select('kategori', DB::raw('SUM(stok) as total_stok'))
                                   ->groupBy('kategori')
                                   ->get();
        
        return view('buffer', compact(
            'stokBarang', 'bufferStok', 'totalProduk', 'kategoriAktif', 
            'totalNilaiAset', 'persentaseStokAman', 'kategoriStats', 'stokAmanCount','search'
        ));
    }

    /**
     * Kirim notifikasi WhatsApp via Fonnte (SEMUA ITEM TAMPIL)
     */
    private function sendWhatsAppNotification($bufferStok, $jumlahKritis)
    {
        $no_wa_tujuan = '6285185217795';
        $api_token = '7VrQ2hCw3AG7s1Pp7Jrr';
        
        // Batas maksimal karakter WhatsApp adalah 4096
        // Jika item banyak, bagi menjadi beberapa pesan
        
        $pesanHeader = "⚠️ *PERINGATAN BUFFER STOK* ⚠️\n\n";
        $pesanHeader .= "Terdapat *{$jumlahKritis}* material kritis:\n";
        $pesanHeader .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $pesanItem = "";
        $no = 1;
        
        foreach ($bufferStok as $item) {
            $itemText = "{$no}. *{$item->nama_barang}*\n";
            $itemText .= "   📦 Stok: {$item->stok} / {$item->stok_minimum} unit\n";
            $itemText .= "   🏷️ Kategori: {$item->kategori}\n";
            $itemText .= "   🔗 SKU: {$item->kode_barang}\n\n";
            
            // Jika pesan akan melebihi batas, kirim dulu yang sudah terkumpul
            if (strlen($pesanHeader . $pesanItem . $itemText) > 4000) {
                $this->sendWA($no_wa_tujuan, $api_token, $pesanHeader . $pesanItem);
                $pesanItem = ""; // Reset untuk pesan berikutnya
            }
            
            $pesanItem .= $itemText;
            $no++;
        }
        
        $pesanFooter = "━━━━━━━━━━━━━━━━━━━━\n";
        $pesanFooter .= "📌 Segera lakukan pengadaan!\n";
        $pesanFooter .= "🔗 Cek detail: " . url('/buffer');
        
        // Kirim sisa pesan
        if (!empty($pesanItem)) {
            $this->sendWA($no_wa_tujuan, $api_token, $pesanHeader . $pesanItem . $pesanFooter);
        }
    }
    
    /**
     * Fungsi helper untuk mengirim WA
     */
    private function sendWA($no_wa_tujuan, $api_token, $pesan)
    {
        try {
            $response = Http::withHeaders(['Authorization' => $api_token])
                ->post('https://api.fonnte.com/send', [
                    'target' => $no_wa_tujuan,
                    'message' => $pesan,
                    'countryCode' => '62',
                ]);
            
            Log::info('WA Notifikasi terkirim: ' . $response->body());
            return true;
        } catch (\Exception $e) {
            Log::error('WA Notifikasi gagal: ' . $e->getMessage());
            return false;
        }
    }

    public function create()
    {
        return view('buffer-stok-create');
    }

    
    public function show($id)
    {
        $barang = StokBarang::findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT BARANG MASUK / PEMBELIAN
    |--------------------------------------------------------------------------
    */

   $riwayatMasuk = BarangMasuk::where('stok_barang_id', $barang->id)
        ->whereNotNull('tanggal_masuk')
        ->get()
        ->map(function ($item) {

            return [
                'id' => $item->id,
                'sumber' => 'barang_masuk',
                'tanggal' => Carbon::parse($item->tanggal_masuk),
                'aktivitas' => 'Pembelian',
                'masuk' => $item->jumlah,
                'keluar' => 0,
                'keterangan' => $item->supplier
                    ? 'Supplier: ' . $item->supplier
                    : 'Barang masuk',
            ];
        });


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT BARANG KELUAR / PENJUALAN
    |--------------------------------------------------------------------------
    */

    $riwayatKeluar = BarangKeluar::with('notaPembeli')
        ->where('stok_barang_id', $barang->id)
        ->get()
        ->map(function ($item) {

            $tanggal = $item->notaPembeli?->tanggal_keluar
                ?? $item->created_at;

            return [
                'id' => $item->id,
                'sumber' => 'barang_keluar',
                'tanggal' => Carbon::parse($tanggal),
                'aktivitas' => 'Penjualan',
                'masuk' => 0,
                'keluar' => $item->jumlah,
                'keterangan' => $item->notaPembeli
                    ? 'Nota M3J#-' . str_pad(
                        $item->notaPembeli->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    )
                    : 'Barang keluar',
            ];
        });


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT STOK OPNAME
    |--------------------------------------------------------------------------
    */

    $riwayatOpname = StokOpname::where('stok_barang_id', $barang->id)
        ->get()
        ->map(function ($item) {

            return [
                'id' => $item->id,
                'sumber' => 'stok_opname',
                'tanggal' => Carbon::parse($item->tanggal),

                'aktivitas' => $item->jenis_opname === 'penambahan'
                    ? 'Opname Penambahan'
                    : 'Opname Pengurangan',

                'masuk' => $item->jenis_opname === 'penambahan'
                    ? $item->jumlah_perubahan
                    : 0,

                'keluar' => $item->jenis_opname === 'pengurangan'
                    ? $item->jumlah_perubahan
                    : 0,

                'keterangan' => $item->keterangan_opname ?? '-',
            ];
        });


    /*
    |--------------------------------------------------------------------------
    | GABUNGKAN SEMUA RIWAYAT
    |--------------------------------------------------------------------------
    */

    $semuaRiwayat = collect()
        ->concat($riwayatMasuk)
        ->concat($riwayatKeluar)
        ->concat($riwayatOpname)
        ->sortByDesc('tanggal')
        ->values();


    /*
    |--------------------------------------------------------------------------
    | KALKULASI
    |--------------------------------------------------------------------------
    */

    $totalPembelian = $riwayatMasuk->sum('masuk');

    $totalTerjual = $riwayatKeluar->sum('keluar');

    $totalMasukNonPembelian = $riwayatOpname
        ->where('aktivitas', 'Opname Penambahan')
        ->sum('masuk');

    $totalKeluarNonPenjualan = $riwayatOpname
        ->where('aktivitas', 'Opname Pengurangan')
        ->sum('keluar');


    /*
    |--------------------------------------------------------------------------
    | PAGINATION MANUAL
    |--------------------------------------------------------------------------
    */

    $perPage = 10;

    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    $currentItems = $semuaRiwayat
        ->forPage($currentPage, $perPage)
        ->values();

    $riwayatStok = new LengthAwarePaginator(
        $currentItems,
        $semuaRiwayat->count(),
        $perPage,
        $currentPage,
        [
            'path' => request()->url(),
            'query' => request()->query(),
        ]
    );


    return view('showbarang', compact(
        'barang',
        'riwayatStok',
        'totalPembelian',
        'totalTerjual',
        'totalMasukNonPembelian',
        'totalKeluarNonPenjualan'
    ));;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = StokBarang::findOrFail($id);
        return view('buffer-stok-edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function barangupdate(Request $request, $id)
{
    $barang = StokBarang::findOrFail($id);

    // 1. Bersihkan input dari titik atau karakter non-angka murni
    if ($request->has('harga_beli')) {
        // 1. Ubah koma menjadi titik (jika user mengetik 100,00 menjadi 100.00)
        $hargaBeli = str_replace(',', '.', $request->harga_beli);
        // 2. Buang semua karakter selain angka dan titik desimal
        $hargaBeli = preg_replace('/[^0-9.]/', '', $hargaBeli);
        
        $request->merge(['harga_beli' => $hargaBeli]);
    }
    if ($request->has('harga_jual')) {
        // 1. Ubah koma menjadi titik
        $hargaJual = str_replace(',', '.', $request->harga_jual);
        // 2. Buang semua karakter selain angka dan titik desimal
        $hargaJual = preg_replace('/[^0-9.]/', '', $hargaJual);
        
        $request->merge(['harga_jual' => $hargaJual]);
    }
    
    // 2. SESUAIKAN VALIDASI: Ubah 'integer' menjadi 'numeric' untuk tipe data decimal
    $request->validate([
        'kode_barang' => 'required|string|unique:stok_barang,kode_barang,' . $id . ',id',
        'nama_barang' => 'required|string',
        'kategori' => 'required|string',
        'stok_minimum' => 'required|integer|min:0',
        'harga_beli' => 'required|numeric|min:0', // Menggunakan numeric
        'harga_jual' => 'required|numeric|min:0', // Menggunakan numeric
        'gambar' => 'bail|nullable|mimes:jpg,jpeg,png|max:2048',
    ], [
    'gambar.mimes'    => 'Format foto harus PNG, JPG, atau JPEG.',
    'gambar.max'      => 'Ukuran foto terlalu besar. Maksimal 2 MB.',
    'gambar.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya tidak lebih dari 2 MB.'
    ]);
    
    // Siapkan data untuk update
    $data = [
        'kode_barang' => $request->kode_barang,
        'nama_barang' => $request->nama_barang,
        'kategori' => $request->kategori,
        'stok_minimum' => $request->stok_minimum,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
    ];
    
    // Handle upload gambar
    if ($request->hasFile('gambar')) {
        $uploadPath = public_path('uploads/gambar_barang');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Hapus gambar lama jika ada
        if ($barang->gambar && file_exists($uploadPath . '/' . $barang->gambar)) {
            unlink($uploadPath . '/' . $barang->gambar);
        }
        
        // Upload gambar baru
        $file = $request->file('gambar');
        $namaFoto = time() . '_' . $file->getClientOriginalName();
        $file->move($uploadPath, $namaFoto);
        $data['gambar'] = $namaFoto;
    } else {
        $data['gambar'] = $barang->gambar;
    }
    
    // Update data ke database
    $barang->update($data);
    
    return back ()->with('success', 'Material berhasil diperbarui!');
}
    public function destroy($id)
    {
        $barang = StokBarang::findOrFail($id);
        $barang->delete(); // ✅ SEKARANG SOFTDELETE (BUKAN HAPUS PERMANEN)
        
        // Kirim notifikasi jika setelah hapus masih ada buffer
        $this->checkAndSendNotification();
        
        return back()
                        ->with('success', 'Material berhasil dipindahkan ke tempat sampah!');
    }

    public function destroyBarangMasuk($id)
{
    // 1. Cari data log barang masuk yang mau dihapus
    $logMasuk = BarangMasuk::findOrFail($id);

    // 2. Cari data Stok Barang utamanya berdasarkan relasi 'stok_barang_id'
    $stokBarang = StokBarang::find($logMasuk->stok_barang_id);

    if ($stokBarang && $logMasuk->sudahDiterima()) {
        // 3. KEMBALIKAN STOK: Kurangi stok utama dengan jumlah yang pernah dimasukkan sebelumnya
        $stokBarang->decrement('stok', $logMasuk->jumlah);
    }

    // 4. Hapus riwayat log barang masuk (mengisi deleted_at jika pakai SoftDeletes)
    $logMasuk->delete(); 

    return back()->with('success', 'Riwayat barang masuk berhasil dihapus dan jumlah stok telah dikembalikan seperti semula!');
}
    
    /**
     * Get buffer stock data for API (optional)
     */
    public function getBufferData()
    {
        $bufferStok = StokBarang::whereRaw('stok <= stok_minimum')
                                ->select('nama_barang', 'stok', 'stok_minimum', 'kategori')
                                ->get();
        
        return response()->json($bufferStok);
    }

    /**
     * Update stok minimum via AJAX
     */
    public function updateStokMinimum(Request $request)
{
    try {

        $request->validate([

            'id' =>
                'required|exists:stok_barang,id',

            'metode' =>
                'required|in:custom,sederhana,statistik',

            'stok_minimum_custom' =>
                'nullable|integer|min:0',

        ]);


        $barang = StokBarang::findOrFail(
            $request->id
        );


        $hasil = $this->hitungRekomendasiBuffer(
            $barang
        );


        /*
        |--------------------------------------------------------------------------
        | CUSTOM
        |--------------------------------------------------------------------------
        */

        if ($request->metode === 'custom') {

            if ($request->stok_minimum_custom === null) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Nilai stok minimum custom harus diisi.'
                ], 422);
            }


            $barang->stok_minimum_custom =
                $request->stok_minimum_custom;


            $barang->stok_minimum =
                $request->stok_minimum_custom;
        }


        /*
        |--------------------------------------------------------------------------
        | BUFFER SEDERHANA
        |--------------------------------------------------------------------------
        */

        elseif ($request->metode === 'sederhana') {

            if (!$hasil['tersedia']) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Rekomendasi buffer belum dapat dihitung karena data lead time belum tersedia.'
                ], 422);
            }


            $barang->stok_minimum =
                $hasil['buffer_sederhana'];
        }


        /*
        |--------------------------------------------------------------------------
        | SAFETY STOCK
        |--------------------------------------------------------------------------
        */

        elseif ($request->metode === 'statistik') {

            if (!$hasil['tersedia']) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Safety stock belum dapat dihitung karena data lead time belum tersedia.'
                ], 422);
            }


            $barang->stok_minimum =
                $hasil['safety_stock'];
        }


        $barang->metode_stok_minimum =
            $request->metode;


        $barang->save();


        $this->checkAndSendNotification();


        return response()->json([

            'success' => true,

            'message' =>
                'Patokan stok minimum berhasil diperbarui.',

            'stok_minimum' =>
                $barang->stok_minimum,

        ]);


    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => $e->getMessage(),

        ], 500);
    }
}   

    /**
     * Cek dan kirim notifikasi jika ada buffer stok
     */
    private function checkAndSendNotification()
    {
        $bufferStok = StokBarang::whereRaw('stok <= stok_minimum')->get();
        $jumlahKritis = $bufferStok->count();
        
        if ($jumlahKritis > 0) {
            $this->sendWhatsAppNotification($bufferStok, $jumlahKritis);
        }
    }

        /**
     * Menampilkan data yang sudah di-softdelete (tempat sampah)
     */
    public function sampah()
{
    // 1. Ambil data master stok barang yang sudah dihapus
    $trashedBarang = StokBarang::onlyTrashed()->get();

    // 2. Ambil data riwayat barang masuk yang sudah dihapus
    $trashedBarangMasuk = BarangMasuk::onlyTrashed()
        ->with('stokBarang')
        ->get();

    // 3. Ambil nota pembeli yang sudah dihapus
    $trashedNotaPembeli = NotaPembeli::onlyTrashed()
        ->with([
            'barangKeluar' => function ($query) {
                $query->withTrashed()->with('stokBarang');
            }
        ])
        ->latest('deleted_at')
        ->get();

    // 4. Ambil barang keluar yang sudah dihapus
    $trashedBarangKeluar = BarangKeluar::onlyTrashed()
        ->with([
            'notaPembeli' => function ($query) {
                $query->withTrashed();
            },
            'stokBarang'
        ])
        ->latest('deleted_at')
        ->get();

    $trashedOpnames = StokOpname::onlyTrashed()
    ->with(['stokBarang', 'user'])
    ->orderByDesc('deleted_at')
    ->get();

    // 5. Hitung seluruh data yang ada di tempat sampah
    $countTrashed =
        $trashedBarang->count() +
        $trashedBarangMasuk->count() +
        $trashedNotaPembeli->count() +
        $trashedBarangKeluar->count() +
         $trashedOpnames->count();

    return view('sampah', compact(
        'trashedBarang',
        'trashedBarangMasuk',
        'trashedNotaPembeli',
        'trashedBarangKeluar',
        'trashedOpnames',
        'countTrashed'
    ));
}

   public function restoreNotaPembeli($id)
{
    DB::beginTransaction();

    try {
        // Ambil nota yang sudah di-soft delete
        $nota = NotaPembeli::withTrashed()
            ->findOrFail($id);

        // Pulihkan nota
        $nota->restore();

        // Pulihkan semua barang keluar yang ada di dalam nota
        $barangKeluar = BarangKeluar::withTrashed()
            ->where('nota_pembeli_id', $nota->id)
            ->get();

        foreach ($barangKeluar as $item) {
            $item->restore();

            // Kurangi kembali stok karena barang kembali dianggap keluar
            $stokBarang = StokBarang::find($item->stok_barang_id);

            if ($stokBarang) {
                $stokBarang->decrement('stok', $item->jumlah);
            }
        }

        DB::commit();

        return back()->with(
            'success',
            'Nota berhasil dipulihkan dan stok barang telah disesuaikan kembali.'
        );

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error(
            'Error saat memulihkan nota pembeli: ' . $e->getMessage()
        );

        return back()->with(
            'error',
            'Gagal memulihkan nota: ' . $e->getMessage()
        );
    }
}
    public function restore($id)
    {
        $barang = StokBarang::withTrashed()->findOrFail($id);
        $barang->restore();
        
        return redirect()->route('buffer-stok.trash')
                        ->with('success', 'Material berhasil dikembalikan!');
    }

    /**
     * Hapus permanen dari database
     */
    public function forceDelete($id)
    {
        $barang = StokBarang::withTrashed()->findOrFail($id);
        $barang->forceDelete();
        
        return redirect()->route('buffer-stok.trash')
                        ->with('success', 'Material berhasil dihapus permanen!');
    }

    public function stok(Request $request)
{
    $search = $request->query('search');

    // 1. Ambil data katalog stok barang dengan filter pencarian jika diisi
    $stokBarang = StokBarang::when($search, function ($query, $search) {
                        return $query->where('nama_barang', 'like', '%' . $search . '%')
                                     ->orWhere('kategori', 'like', '%' . $search . '%')
                                     ->orWhere('kode_barang', 'like', '%' . $search . '%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
    
    // 2. Hitung Statistik Ringkasan Atas secara Otomatis dari Database
    $totalProduk = StokBarang::count();
    $kategoriAktif = StokBarang::distinct('kategori')->count('kategori');
    $totalNilaiAset = StokBarang::sum(DB::raw('stok * harga_beli'));
    
    // Hitung persentase stok aman (barang yang sisa stoknya > stok_minimum)
    $stokAmanCount = StokBarang::whereRaw('stok > stok_minimum')->count();
    $persentaseStokAman = $totalProduk > 0 ? round(($stokAmanCount / $totalProduk) * 100) : 100;

    // 3. Lempar semua variabel ke file view 'stok'
    return view('stok', compact(
        'stokBarang', 
        'totalProduk', 
        'kategoriAktif', 
        'totalNilaiAset', 
        'persentaseStokAman'
    ));
}

   public function barangMasuk(Request $request)
{

    $search = $request->input('search');
    $tanggal = $request->input('tanggal');

    /*
    |--------------------------------------------------------------------------
    | DATA TABEL BARANG MASUK
    |--------------------------------------------------------------------------
    */
    $query = BarangMasuk::with('stokBarang')
        ->orderByRaw('tanggal_masuk IS NULL DESC')
        ->latest('tanggal_masuk')
        ->latest('id');

    if ($request->input('status') === 'belum') {
        $query->whereNull('tanggal_masuk');
    } elseif ($request->input('status') === 'diterima') {
        $query->whereNotNull('tanggal_masuk');
    }


    if ($search) {

        $query->where(function ($q) use ($search) {

            $q->where('supplier', 'like', '%' . $search . '%')

                ->orWhereHas('stokBarang', function ($barang) use ($search) {

                    $barang
                        ->where('nama_barang', 'like', '%' . $search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $search . '%')
                        ->orWhere('kategori', 'like', '%' . $search . '%');

                });

        });

    }


    if ($tanggal) {

        $query->whereDate('tanggal_masuk', $tanggal);

    }


    $barangMasuk = $query
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | DATA MATERIAL UNTUK FORM INPUT
    |--------------------------------------------------------------------------
    */
    $stokBarang = StokBarang::orderBy('nama_barang')->get();


    /*
    |--------------------------------------------------------------------------
    | STATISTIK BULAN INI
    |--------------------------------------------------------------------------
    */

    $awalBulan = now()->copy()->startOfMonth();
    $akhirBulan = now()->copy()->endOfMonth();


    // Total unit barang masuk bulan ini
    $totalMasukBulanIni = BarangMasuk::whereBetween(
        'tanggal_masuk',
        [$awalBulan, $akhirBulan]
    )->sum('jumlah');


    // Jumlah transaksi/log barang masuk bulan ini
    $totalTransaksiMasuk = BarangMasuk::whereBetween(
        'tanggal_masuk',
        [$awalBulan, $akhirBulan]
    )->count();


    // Supplier aktif bulan ini
    $supplierAktif = BarangMasuk::whereBetween(
            'tanggal_masuk',
            [$awalBulan, $akhirBulan]
        )
        ->whereNotNull('supplier')
        ->where('supplier', '!=', '')
        ->distinct()
        ->count('supplier');


    // Rata-rata jumlah barang per transaksi
    $rataMasukPerTransaksi = $totalTransaksiMasuk > 0
        ? round($totalMasukBulanIni / $totalTransaksiMasuk, 1)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | RATA-RATA LEAD TIME
    |--------------------------------------------------------------------------
    |
    | Lead Time = Tanggal Masuk - Tanggal Pesan
    |
    */
    $rataLeadTime = BarangMasuk::whereBetween(
            'tanggal_masuk',
            [$awalBulan, $akhirBulan]
        )
        ->whereNotNull('tanggal_pesan')
        ->whereNotNull('tanggal_masuk')
        ->whereColumn('tanggal_masuk', '>=', 'tanggal_pesan')
        ->selectRaw(
            'AVG(DATEDIFF(tanggal_masuk, tanggal_pesan)) AS rata_lead_time'
        )
        ->value('rata_lead_time');


    $rataLeadTime = $rataLeadTime !== null
        ? round((float) $rataLeadTime, 1)
        : null;


    return view('barang-masuk', compact(
        'barangMasuk',
        'stokBarang',
        'search',
        'tanggal',
        'totalMasukBulanIni',
        'totalTransaksiMasuk',
        'supplierAktif',
        'rataMasukPerTransaksi',
        'rataLeadTime'
    ));
}

// Menyimpan data barang masuk baru
public function storeBarangMasuk(Request $request)
{
    $request->validate([
        'stok_barang_id' => 'required|exists:stok_barang,id',
        'jumlah'         => 'required|integer|min:1',
        'tanggal_pesan'  => 'required|date',
        'tanggal_masuk'  => 'nullable|date|after_or_equal:tanggal_pesan',
        'supplier'       => 'nullable|string|max:255',
    ]);

    DB::transaction(function () use ($request) {
        $log = BarangMasuk::create([
            'stok_barang_id' => $request->stok_barang_id,
            'jumlah'         => $request->jumlah,
            'tanggal_pesan'  => $request->tanggal_pesan,
            'tanggal_masuk'  => $request->filled('tanggal_masuk') ? $request->tanggal_masuk : null,
            'supplier'       => $request->supplier,
        ]);

        // Stok hanya bertambah kalau barang sudah diterima
        if ($log->sudahDiterima()) {
            StokBarang::lockForUpdate()
                ->findOrFail($log->stok_barang_id)
                ->increment('stok', $log->jumlah);
        }
    });

    $pesan = $request->filled('tanggal_masuk')
        ? 'Barang masuk berhasil dicatat dan stok bertambah.'
        : 'Pesanan dicatat dengan status Belum Diterima. Stok bertambah setelah tanggal masuk diisi.';

    return redirect()->route('barang-masuk')->with('success', $pesan);
}

    public function barangKeluar(Request $request)
{
    $search = $request->input('search');

    /*
    |--------------------------------------------------------------------------
    | QUERY BARANG KELUAR
    |--------------------------------------------------------------------------
    */
    $query = BarangKeluar::with([
            'stokBarang',
            'notaPembeli'
        ])

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

        // Penting supaya ID tabel tidak tertimpa hasil JOIN
        ->select('barang_keluar.*')

        ->orderBy(
            'nota_pembeli.tanggal_keluar',
            'desc'
        );


    /*
    |--------------------------------------------------------------------------
    | PENCARIAN
    |--------------------------------------------------------------------------
    |
    | Bisa mencari berdasarkan:
    | - Nama pembeli
    | - Tujuan
    | - Nomor nota
    | - Nama barang
    | - Kode barang
    |
    */
    if ($search) {

        // Jika user mengetik misalnya:
        // MJ3#-000012
        // maka hasilnya menjadi 000012
        $nomorNota = preg_replace(
            '/[^0-9]/',
            '',
            $search
        );


        $query->where(function ($q) use (
            $search,
            $nomorNota
        ) {

            // Nama pembeli
            $q->where(
                'nota_pembeli.nama_pembeli',
                'like',
                '%' . $search . '%'
            )

            // Tujuan
            ->orWhere(
                'nota_pembeli.tujuan',
                'like',
                '%' . $search . '%'
            )

            // Nama barang
            ->orWhere(
                'stok_barang.nama_barang',
                'like',
                '%' . $search . '%'
            )

            // Kode barang
            ->orWhere(
                'stok_barang.kode_barang',
                'like',
                '%' . $search . '%'
            );


            /*
            |--------------------------------------------------------------------------
            | NOMOR NOTA
            |--------------------------------------------------------------------------
            */
            if ($nomorNota !== '') {

                $q->orWhere(
                    'nota_pembeli.id',
                    (int) $nomorNota
                );
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
    $barangKeluar = $query
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | JUMLAH BARANG KELUAR HARI INI
    |--------------------------------------------------------------------------
    */
    $keluarHariIni = BarangKeluar::whereHas(
        'notaPembeli',
        function ($q) {

            $q->whereDate(
                'tanggal_keluar',
                today()
            );
        }
    )->count();


    return view(
        'barang-keluar',
        compact(
            'barangKeluar',
            'keluarHariIni',
            'search'
        )
    );
}

public function barangkeluarcreate()
{
    $stokBarang = StokBarang::orderBy('nama_barang')->get();
    return view('barang-keluar-form', compact('stokBarang'));
}

public function barangkeluarstore(Request $request)
{
    try {
        $request->validate([
            'nama_pembeli'   => 'required|string|max:255',
            'tujuan'         => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
            'stok_barang_id' => 'required|exists:stok_barang,id',
            'jumlah'         => 'required|integer|min:1',
        ]);

        $stok = StokBarang::findOrFail($request->stok_barang_id);
        if ($stok->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi!'])->withInput();
        }

        $hargaSatuan = $stok->harga_jual ?? $stok->harga_beli ?? 0;
        $totalHarga = $request->jumlah * $hargaSatuan;

        DB::beginTransaction();

        $nota = NotaPembeli::create([
            'nama_pembeli'   => $request->nama_pembeli,
            'tujuan'         => $request->tujuan,
            'tanggal_keluar' => $request->tanggal_keluar,
            'total_harga'    => $totalHarga,
        ]);

        BarangKeluar::create([
            'nota_pembeli_id' => $nota->id,
            'stok_barang_id'  => $request->stok_barang_id,
            'jumlah'          => $request->jumlah,
            'harga_satuan'    => $hargaSatuan,
        ]);

        $stok->decrement('stok', $request->jumlah);

        DB::commit();

        return redirect()->route('barang-keluar')->with('success', 'Nota baru dan rincian barang keluar berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();
        // Log error untuk debugging
        Log::error('Error saat menyimpan barang keluar: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

public function barangkeluardestroy($id)
{
    $barangKeluar = BarangKeluar::findOrFail($id);
    $barangKeluar->stokBarang->increment('stok', $barangKeluar->jumlah);
    
    // Kurangi juga total harga di nota pembeli sebelum datanya dihapus jika diperlukan
    if($barangKeluar->notaPembeli) {
        $totalItem = $barangKeluar->jumlah * $barangKeluar->harga_satuan;
        $barangKeluar->notaPembeli->decrement('total_harga', $totalItem);
    }

    $barangKeluar->delete();

    return redirect()->route('barang-keluar')->with('success', 'Data berhasil dihapus.');
}

  public function barangstore(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | RAPIIKAN NAMA BARANG
    |--------------------------------------------------------------------------
    |
    | Contoh:
    | "  Semen   Tiga Roda  "
    | menjadi:
    | "Semen Tiga Roda"
    |
    */
    $namaBarang = preg_replace(
        '/\s+/',
        ' ',
        trim($request->nama_barang)
    );

    $request->merge([
        'nama_barang' => $namaBarang
    ]);


    /*
    |--------------------------------------------------------------------------
    | VALIDASI DATA
    |--------------------------------------------------------------------------
    */
    $request->validate([

        'nama_barang' => [
            'required',
            'string',
            'max:255',

            function ($attribute, $value, $fail) {

                /*
                | Hanya cek barang yang MASIH AKTIF.
                |
                | Barang yang deleted_at terisi / masuk sampah
                | otomatis TIDAK ikut diperiksa.
                */
                $sudahAda = StokBarang::whereRaw(
                    'LOWER(TRIM(nama_barang)) = ?',
                    [mb_strtolower(trim($value))]
                )->exists();


                if ($sudahAda) {

                    $fail(
                        'Nama barang tersebut sudah terdaftar.'
                    );

                }
            },
        ],

        'kategori' => 'required',

        'stok' => 'required|integer|min:0',

        'stok_minimum' => 'required|integer|min:0',

        'harga_beli' => 'required|integer|min:0',

        'harga_jual' => 'required|integer|min:0',

        'kode_barang' => [
            'nullable',
            Rule::unique(
                'stok_barang',
                'kode_barang'
            )
        ],

            'gambar' => 'bail|nullable|mimes:jpg,jpeg,png|max:2048',
        ], [
            'gambar.mimes'    => 'Format foto harus PNG, JPG, atau JPEG.',
            'gambar.max'      => 'Ukuran foto terlalu besar. Maksimal 2 MB.',
            'gambar.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya tidak lebih dari 2 MB.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | SIAPKAN DATA
            |--------------------------------------------------------------------------
            */
            $data = $request->all();

            $namaFoto = null;


            /*
            |--------------------------------------------------------------------------
            | UPLOAD GAMBAR
            |--------------------------------------------------------------------------
            */
            if ($request->hasFile('gambar')) {

                $namaFoto =
                    time() . '_' .
                    $request
                        ->file('gambar')
                        ->getClientOriginalName();


                $request
                    ->file('gambar')
                    ->move(
                        public_path(
                            'uploads/gambar_barang'
                        ),
                        $namaFoto
                    );


                $data['gambar'] = $namaFoto;
            }


            /*
            |--------------------------------------------------------------------------
            | SIMPAN BARANG
            |--------------------------------------------------------------------------
            */
            StokBarang::create($data);


            return redirect()
                ->route('stok')
                ->with(
                    'success',
                    'Material berhasil ditambahkan!'
                );
}
public function restoreBarangMasuk($id)
{
    // Cari data di sampah menggunakan withTrashed()
    $logMasuk = BarangMasuk::withTrashed()->findOrFail($id);
    
    // Kembalikan statusnya menjadi aktif
    $logMasuk->restore();

    // Opsional: Karena data kembali aktif, kita harus menambahkan kembali stok utama barang tersebut
    $stokBarang = StokBarang::find($logMasuk->stok_barang_id);
    if ($stokBarang && $logMasuk->sudahDiterima()) {
        $stokBarang->increment('stok', $logMasuk->jumlah);
    }

    return redirect()->route('buffer-stok.trash')
                    ->with('success', 'Riwayat barang masuk berhasil dipulihkan dan stok disesuaikan!');
}

/**
 * Menghapus permanen data riwayat barang masuk dari database
 */
public function forceDeleteBarangMasuk($id)
{
    // Cari data di sampah menggunakan withTrashed()
    $logMasuk = BarangMasuk::withTrashed()->findOrFail($id);
    
    // Hapus total secara permanen dari database
    $logMasuk->forceDelete();

    return redirect()->route('buffer-stok.trash')
                    ->with('success', 'Riwayat barang masuk berhasil dihapus secara permanen!');
}

// 1. Menampilkan halaman nota & data barang untuk dropdown modal
public function notaPembeli(Request $request)
{
        // Ambil data pencarian jika ada
        $search = $request->get('search');

        // Gunakan eager loading (with) agar query efisien
        $notaPembeli = NotaPembeli::with(['barangKeluar.stokBarang'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_pembeli', 'like', "%{$search}%")
                             ->orWhere('id', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // Ambil data stok barang untuk modal input
        $stokBarang = StokBarang::where('stok', '>', 0)->get();

        return view('notapembeli', compact('notaPembeli', 'stokBarang'));
}

// 2. Menyimpan nota baru dan memotong stok barang utama otomatis (Barang Keluar)
public function storeNotaPembeli(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDASI FORM DASAR
    |--------------------------------------------------------------------------
    */
    $request->validate([
        'nama_pembeli' => 'required|string|max:255',
        'tujuan' => 'nullable|string',
        'barang' => 'required|array|min:1',
        'barang.*.stok_barang_id' => 'required|exists:stok_barang,id',
        'barang.*.jumlah' => 'required|integer|min:1',
        'barang.*.harga_satuan' => 'required|numeric|min:0',
        'tanggal_keluar' => 'required|date',
    ]);


    /*
    |--------------------------------------------------------------------------
    | MULAI DATABASE TRANSACTION
    |--------------------------------------------------------------------------
    |
    | Semua proses dianggap satu kesatuan.
    | Jika ada satu stok bermasalah, semuanya dibatalkan.
    |
    */
    DB::beginTransaction();


    try {

        /*
        |--------------------------------------------------------------------------
        | 1. KELOMPOKKAN BARANG YANG SAMA
        |--------------------------------------------------------------------------
        |
        | Contoh request:
        |
        | Semen = 6
        | Pasir = 2
        | Semen = 5
        |
        | Akan menjadi:
        |
        | Semen = 11
        | Pasir = 2
        |
        */
        $barangGabungan = collect($request->barang)

            ->groupBy(function ($item) {

                return (string) $item['stok_barang_id'];

            })

            ->map(function ($items) {

                return $items->sum(function ($item) {

                    return (int) $item['jumlah'];

                });

            });


        /*
        |--------------------------------------------------------------------------
        | 2. AMBIL SEMUA STOK DAN KUNCI SEMENTARA
        |--------------------------------------------------------------------------
        |
        | lockForUpdate() penting supaya dua transaksi bersamaan
        | tidak menjual stok yang sama melewati batas.
        |
        */
        $stokBarang = StokBarang::whereIn(
                'id',
                $barangGabungan->keys()->all()
            )

            ->lockForUpdate()

            ->get()

            ->keyBy(function ($item) {

                return (string) $item->id;

            });


        /*
        |--------------------------------------------------------------------------
        | 3. CEK TOTAL QTY DENGAN STOK ASLI DATABASE
        |--------------------------------------------------------------------------
        */
        $stokTidakCukup = [];


        foreach ($barangGabungan as $stokId => $totalJumlah) {

            $barang =
                $stokBarang->get((string) $stokId);


            /*
            |--------------------------------------------------------------------------
            | Jaga-jaga barang tidak ditemukan
            |--------------------------------------------------------------------------
            */
            if (!$barang) {

                $stokTidakCukup[] =
                    "Barang dengan ID {$stokId} tidak ditemukan.";

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | TOTAL QTY TIDAK BOLEH MELEBIHI STOK
            |--------------------------------------------------------------------------
            */
            if ($totalJumlah > $barang->stok) {

                $stokTidakCukup[] =
                    "{$barang->nama_barang}: Qty {$totalJumlah} melebihi stok tersedia {$barang->stok}.";
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 4. JIKA ADA STOK TIDAK CUKUP
        |--------------------------------------------------------------------------
        |
        | Nota BELUM dibuat.
        | Barang keluar BELUM dibuat.
        | Stok BELUM dikurangi.
        |
        */
        if (!empty($stokTidakCukup)) {

            DB::rollBack();


            return redirect()
                ->back()

                ->withErrors([
                    'stok' =>
                        "Stok tidak mencukupi.\n\n" .
                        implode("\n", $stokTidakCukup)
                ])

                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | 5. BUAT NOTA
        |--------------------------------------------------------------------------
        |
        | Bagian ini hanya dijalankan jika SEMUA stok aman.
        |
        */
        $nota = NotaPembeli::create([

            'nama_pembeli' =>
                $request->nama_pembeli,

            'tujuan' =>
                $request->tujuan,

            'tanggal_keluar' =>
                $request->tanggal_keluar,

            'total_harga' => 0,
        ]);


        $totalHarga = 0;


        /*
        |--------------------------------------------------------------------------
        | 6. SIMPAN DETAIL BARANG KELUAR
        |--------------------------------------------------------------------------
        */
        foreach ($request->barang as $item) {

            $jumlah =
                (int) $item['jumlah'];

            $hargaSatuan =
                (float) $item['harga_satuan'];


            $subtotal =
                $jumlah * $hargaSatuan;


            $totalHarga += $subtotal;


            BarangKeluar::create([

                'nota_pembeli_id' =>
                    $nota->id,

                'stok_barang_id' =>
                    $item['stok_barang_id'],

                'jumlah' =>
                    $jumlah,

                'harga_satuan' =>
                    $hargaSatuan,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 7. POTONG STOK BERDASARKAN TOTAL BARANG GABUNGAN
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | Semen baris 1 = 6
        | Semen baris 2 = 5
        |
        | Stok dipotong SATU KALI sebesar 11.
        |
        */
        foreach ($barangGabungan as $stokId => $totalJumlah) {

            $barang =
                $stokBarang->get((string) $stokId);


            $barang->decrement(
                'stok',
                (int) $totalJumlah
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 8. UPDATE TOTAL HARGA NOTA
        |--------------------------------------------------------------------------
        */
        $nota->update([

            'total_harga' =>
                $totalHarga

        ]);


        /*
        |--------------------------------------------------------------------------
        | 9. SIMPAN SELURUH TRANSAKSI
        |--------------------------------------------------------------------------
        */
        DB::commit();


        return redirect()
            ->back()

            ->with(
                'success',
                'Nota baru berhasil diterbitkan!'
            );


    } catch (\Exception $e) {

        /*
        |--------------------------------------------------------------------------
        | JIKA ADA ERROR, BATALKAN SEMUANYA
        |--------------------------------------------------------------------------
        */
        DB::rollBack();


        Log::error(
            'Error saat menyimpan nota pembeli: ' .
            $e->getMessage()
        );


        return redirect()
            ->back()

            ->with(
                'error',
                'Terjadi kesalahan: ' .
                $e->getMessage()
            )

            ->withInput();
    }
}

// 3. Menghapus nota pembeli dan mengembalikan stok barang semula
public function destroyNotaPembeli($id)
{
    DB::beginTransaction();

    try {
        // 1. Cari nota pembeli berdasarkan ID nota
        $nota = NotaPembeli::with('barangKeluar')->findOrFail($id);

        // 2. Ambil semua barang keluar yang ada di dalam nota
        foreach ($nota->barangKeluar as $barangKeluar) {

            // 3. Kembalikan stok barang ke gudang
            $stokBarang = StokBarang::find($barangKeluar->stok_barang_id);

            if ($stokBarang) {
                $stokBarang->increment('stok', $barangKeluar->jumlah);
            }

            // 4. Soft delete setiap barang keluar
            $barangKeluar->delete();
        }

        // 5. Soft delete nota pembeli
        $nota->delete();

        DB::commit();

        return back()->with(
            'success',
            'Nota berhasil dihapus dan seluruh stok barang telah dikembalikan ke gudang.'
        );

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('Error saat menghapus nota pembeli: ' . $e->getMessage());

        return back()->with(
            'error',
            'Gagal menghapus nota: ' . $e->getMessage()
        );
    }
}

public function cetak($id)
{
    // Mengambil data nota spesifik beserta detail rincian barang keluar dan stok barangnya
    $nota = NotaPembeli::with(['barangKeluar.stokBarang'])->findOrFail($id);

    // Mengembalikan view khusus cetak cetak_invoice.blade.php
    return view('cetak-notapembeli', compact('nota'));
}

public function manajemen()
{
    $users = User::latest()->get();

    $totalUser = User::count();

    $pending = User::where('status', 'pending')->count();

    $approved = User::where('status', 'approved')->count();

    $rejected = User::where('status', 'rejected')->count();

    return view('manajemen', compact(
        'users',
        'totalUser',
        'pending',
        'approved',
        'rejected'
    ));
}

public function manajemenUpdate(Request $request, $id)
{
    // 1. Validasi input dari form modal
    $request->validate([
        'role' => 'required|in:admin,user',
        'status' => 'required|in:approved,pending,rejected',
    ]);

    // 2. Cari data user berdasarkan ID
    $user = User::findOrFail($id);

    // 3. Update data ke database
    $user->update([
        'role' => $request->role,
        'status' => $request->status,
    ]);

    // 4. Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
}
public function opname()
{
    $barangs = StokBarang::orderBy('nama_barang')->get();

    $opnames = StokOpname::with(['stokBarang', 'user'])
        ->latest('tanggal')
        ->get();

    return view('inventory.opname', compact('barangs', 'opnames'));
}
public function storeOpname(Request $request)
{
    $request->validate([
        'stok_barang_id' => 'required|exists:stok_barang,id',
        'jenis_opname' => 'required|in:penambahan,pengurangan',
        'jumlah_perubahan' => 'required|integer|min:1',
        'keterangan_opname' => 'nullable|string|max:500',
    ]);

    DB::transaction(function () use ($request) {

        $barang = StokBarang::lockForUpdate()
            ->findOrFail($request->stok_barang_id);

        // Stok sebelum otomatis mengambil stok saat ini
        $stokSebelum = $barang->stok;

        // Hitung stok sesudah berdasarkan jenis opname
        if ($request->jenis_opname === 'penambahan') {

            $stokSesudah = $stokSebelum + $request->jumlah_perubahan;

        } else {

            if ($request->jumlah_perubahan > $stokSebelum) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah_perubahan' =>
                        'Jumlah pengurangan (' . $request->jumlah_perubahan .
                        ') melebihi stok yang tersedia (' . $stokSebelum . ').'
                ]);
            }

            $stokSesudah = $stokSebelum - $request->jumlah_perubahan;
        }

        // Simpan histori opname
        StokOpname::create([
            'stok_barang_id' => $barang->id,
            'jenis_opname' => $request->jenis_opname,
            'jumlah_perubahan' => $request->jumlah_perubahan,
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSesudah,
            'keterangan_opname' => $request->keterangan_opname,
            'user_id' => Auth::id(),
            'tanggal' => now(),
        ]);

        // Update stok utama
        $barang->update([
            'stok' => $stokSesudah
        ]);
    });

    return redirect()
        ->back()
        ->with('success', 'Stok opname berhasil disimpan.');
}
public function destroyOpname($id)
{
    DB::transaction(function () use ($id) {

        $opname = StokOpname::findOrFail($id);

        $barang = StokBarang::lockForUpdate()
            ->findOrFail($opname->stok_barang_id);

        /*
        |--------------------------------------------------------------------------
        | Batalkan efek opname
        |--------------------------------------------------------------------------
        |
        | Penambahan dahulu +5
        | Dihapus sekarang -5
        |
        | Pengurangan dahulu -5
        | Dihapus sekarang +5
        |
        */

        if ($opname->jenis_opname === 'penambahan') {

            if ($barang->stok < $opname->jumlah_perubahan) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'opname_delete' =>
                        'Riwayat opname tidak dapat dihapus karena stok saat ini tidak mencukupi untuk membatalkan penambahan tersebut.'
                ]);
            }

            $barang->stok -= $opname->jumlah_perubahan;

        } else {

            $barang->stok += $opname->jumlah_perubahan;
        }

        $barang->save();

        // Soft Delete
        $opname->delete();
    });

    return back()->with(
        'success',
        'Riwayat stok opname berhasil dipindahkan ke tempat sampah dan perubahan stok telah dibatalkan.'
    );
}
public function forceDeleteOpname($id)
{
    $opname = StokOpname::onlyTrashed()
        ->findOrFail($id);

    $opname->forceDelete();

    return back()->with(
        'success',
        'Riwayat stok opname berhasil dihapus permanen.'
    );
}
public function restoreOpname($id)
{
    DB::transaction(function () use ($id) {

        $opname = StokOpname::onlyTrashed()
            ->findOrFail($id);

        $barang = StokBarang::lockForUpdate()
            ->findOrFail($opname->stok_barang_id);

        if ($opname->jenis_opname === 'penambahan') {

            $barang->stok += $opname->jumlah_perubahan;

        } else {

            if ($opname->jumlah_perubahan > $barang->stok) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'opname_restore' =>
                        'Riwayat opname tidak dapat dipulihkan karena jumlah pengurangan melebihi stok yang tersedia.'
                ]);
            }

            $barang->stok -= $opname->jumlah_perubahan;
        }

        $barang->save();

        $opname->restore();
    });

    return back()->with(
        'success',
        'Riwayat stok opname berhasil dipulihkan.'
    );
}
public function forceDeleteNotaPembeli($id)
{
    $nota = NotaPembeli::onlyTrashed()->findOrFail($id);

    $nota->forceDelete();

    return back()->with(
        'success',
        'Nota pembeli berhasil dihapus permanen.'
    );
}
private function hitungRekomendasiBuffer(StokBarang $barang, $periodeHari = 30)
{
    /*
    |--------------------------------------------------------------------------
    | PERIODE ANALISIS
    |--------------------------------------------------------------------------
    */

    $tanggalAkhir = Carbon::today();

    $tanggalAwal = Carbon::today()
        ->subDays($periodeHari - 1);


    /*
    |--------------------------------------------------------------------------
    | SIAPKAN PEMAKAIAN HARIAN
    |--------------------------------------------------------------------------
    |
    | Pemakaian diambil dari BARANG KELUAR / PENJUALAN.
    | Stok opname tidak dimasukkan karena bukan permintaan pelanggan.
    |
    */

    $pemakaianHarian = [];

    $tanggal = $tanggalAwal->copy();

    while ($tanggal <= $tanggalAkhir) {

        $pemakaianHarian[$tanggal->format('Y-m-d')] = 0;

        $tanggal->addDay();
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL PENJUALAN
    |--------------------------------------------------------------------------
    */

    $barangKeluar = BarangKeluar::with('notaPembeli')

        ->where('stok_barang_id', $barang->id)

        ->whereHas('notaPembeli', function ($query) use (
            $tanggalAwal,
            $tanggalAkhir
        ) {

            $query->whereBetween(
                'tanggal_keluar',
                [
                    $tanggalAwal->format('Y-m-d'),
                    $tanggalAkhir->format('Y-m-d')
                ]
            );

        })

        ->get();


    foreach ($barangKeluar as $item) {

        if (!$item->notaPembeli) {
            continue;
        }

        $tanggalKeluar = Carbon::parse(
            $item->notaPembeli->tanggal_keluar
        )->format('Y-m-d');


        if (isset($pemakaianHarian[$tanggalKeluar])) {

            $pemakaianHarian[$tanggalKeluar]
                += $item->jumlah;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG PEMAKAIAN HARIAN
    |--------------------------------------------------------------------------
    */

    $dataPemakaian = array_values($pemakaianHarian);

    $jumlahHari = count($dataPemakaian);

    $rataPemakaian = $jumlahHari > 0
        ? array_sum($dataPemakaian) / $jumlahHari
        : 0;

    $maksPemakaian = $jumlahHari > 0
        ? max($dataPemakaian)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | STANDAR DEVIASI PERMINTAAN
    |--------------------------------------------------------------------------
    */

    $jumlahKuadrat = 0;

    foreach ($dataPemakaian as $nilai) {

        $jumlahKuadrat += pow(
            $nilai - $rataPemakaian,
            2
        );
    }

    $standarDeviasi = $jumlahHari > 0

        ? sqrt(
            $jumlahKuadrat / $jumlahHari
        )

        : 0;


    /*
    |--------------------------------------------------------------------------
    | HITUNG LEAD TIME
    |--------------------------------------------------------------------------
    */

    $barangMasuk = BarangMasuk::where(
            'stok_barang_id',
            $barang->id
        )

        ->whereNotNull('tanggal_pesan')
        ->whereNotNull('tanggal_masuk')
        ->get();

    $leadTimes = $barangMasuk
        ->map(function ($item) {
            return Carbon::parse($item->tanggal_pesan)
                ->diffInDays(
                    Carbon::parse($item->tanggal_masuk)
                );
        })
        ->filter(function ($nilai) {
            return $nilai >= 0;
        });


    /*
    |--------------------------------------------------------------------------
    | DATA BELUM CUKUP
    |--------------------------------------------------------------------------
    */

    if ($leadTimes->isEmpty()) {
        return [
            'tersedia' => false,
            'buffer_sederhana' => null,
            'safety_stock' => null,
            'pemakaian_rata' => $rataPemakaian,
            'pemakaian_maks' => $maksPemakaian,
            'standar_deviasi' => $standarDeviasi,
            'lead_time_rata' => null,
            'lead_time_maks' => null,

        ];
    }


    $leadTimeRata = $leadTimes->avg();

    $leadTimeMaks = $leadTimes->max();


    /*
    |--------------------------------------------------------------------------
    | METODE SEDERHANA
    |--------------------------------------------------------------------------
    |
    | Buffer =
    | (Pemakaian maks × Lead time maks)
    | -
    | (Pemakaian rata × Lead time rata)
    |
    */

    $bufferSederhana =

        ($maksPemakaian * $leadTimeMaks)
        -
        ($rataPemakaian * $leadTimeRata);
    $bufferSederhana = max(
        0,
        ceil($bufferSederhana)
    );


    /*
    |--------------------------------------------------------------------------
    | METODE STATISTIK
    |--------------------------------------------------------------------------
    |
    | Safety Stock = Z × σ × √L
    |
    | Z = 1.65 (service level 95%)
    | L = lead time rata-rata
    |
    */

    $z = 1.65;
    $safetyStock =
        $z
        * $standarDeviasi
        * sqrt($leadTimeRata);
    $safetyStock = max(
        0,
        ceil($safetyStock)
    );
    return [
        'tersedia' => true,
        'buffer_sederhana' => $bufferSederhana,
        'safety_stock' => $safetyStock,
        'pemakaian_rata' => round($rataPemakaian, 2),
        'pemakaian_maks' => $maksPemakaian,
        'standar_deviasi' => round($standarDeviasi, 2),
        'lead_time_rata' => round($leadTimeRata, 2),
        'lead_time_maks' => $leadTimeMaks,
    ];
}

public function applyBufferMethodAll(Request $request)
{
    $request->validate([
        'metode' =>
            'required|in:custom,sederhana,statistik',
    ]);


    $berhasil = 0;
    $dilewati = 0;


    DB::transaction(function () use (
        $request,
        &$berhasil,
        &$dilewati
    ) {

        $barangs = StokBarang::all();


        foreach ($barangs as $barang) {

            /*
            | CUSTOM
            */

            if ($request->metode === 'custom') {

                $barang->stok_minimum =
                    $barang->stok_minimum_custom;

                $barang->metode_stok_minimum =
                    'custom';

                $barang->save();

                $berhasil++;

                continue;
            }


            /*
            | RUMUS
            */

            $hasil =
                $this->hitungRekomendasiBuffer(
                    $barang
                );


            if (!$hasil['tersedia']) {

                $dilewati++;

                continue;
            }


            if ($request->metode === 'sederhana') {

                $barang->stok_minimum =
                    $hasil['buffer_sederhana'];

            } else {

                $barang->stok_minimum =
                    $hasil['safety_stock'];
            }


            $barang->metode_stok_minimum =
                $request->metode;


            $barang->save();

            $berhasil++;
        }
    });


    $this->checkAndSendNotification();


    return response()->json([

        'success' => true,

        'message' =>
            "{$berhasil} barang berhasil diperbarui. {$dilewati} barang dilewati karena data lead time belum tersedia."

    ]);
}
// Mengubah data barang masuk (jumlah, tanggal, supplier)
public function updateBarangMasuk(Request $request, $id)
{
    $request->validate([
        'jumlah'        => 'required|integer|min:1',
        'tanggal_pesan' => 'required|date',
        'tanggal_masuk' => 'nullable|date|after_or_equal:tanggal_pesan',
        'supplier'      => 'nullable|string|max:255',
    ], [
        'tanggal_masuk.after_or_equal' => 'Tanggal masuk tidak boleh sebelum tanggal pemesanan.',
    ]);

    DB::transaction(function () use ($request, $id) {
        $log  = BarangMasuk::lockForUpdate()->findOrFail($id);
        $stok = StokBarang::lockForUpdate()->findOrFail($log->stok_barang_id);

        $sudahDiterima = $log->tanggal_masuk !== null;
        $akanDiterima  = $request->filled('tanggal_masuk');
        $jumlahBaru    = (int) $request->jumlah;

        // Barang yang sudah diterima tidak boleh dikembalikan ke "belum diterima"
        if ($sudahDiterima && ! $akanDiterima) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'tanggal_masuk' => 'Barang yang sudah diterima tidak bisa dikembalikan ke status belum diterima. Hapus riwayatnya jika memang salah input.',
            ]);
        }

        if (! $sudahDiterima && $akanDiterima) {
            $perubahan = $jumlahBaru;                        // baru diterima: stok + jumlah
        } elseif ($sudahDiterima && $akanDiterima) {
            $perubahan = $jumlahBaru - (int) $log->jumlah;   // sudah diterima: stok + selisih
        } else {
            $perubahan = 0;                                  // masih belum diterima
        }

        if ($stok->stok + $perubahan < 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'jumlah' => 'Jumlah tidak bisa dikurangi sebanyak itu. Stok saat ini hanya '
                    . $stok->stok . ' karena sebagian barang sudah keluar.',
            ]);
        }

        $log->update([
            'jumlah'        => $jumlahBaru,
            'tanggal_pesan' => $request->tanggal_pesan,
            'tanggal_masuk' => $akanDiterima ? $request->tanggal_masuk : null,
            'supplier'      => $request->supplier,
        ]);

        if ($perubahan !== 0) {
            $stok->increment('stok', $perubahan);
        }
    });

    return redirect()->route('barang-masuk')
        ->with('success', 'Data barang masuk berhasil diperbarui dan stok disesuaikan.');
}

}