<?php

namespace App\Http\Controllers;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\NotaPembeli;
use App\Models\StokBarang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Psy\Readline\Interactive\Input\Buffer;

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

    /**
     * Store a newly created resource in storage.
     */



    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'kode_barang' => 'required|string|unique:stok_barang',
    //         'nama_barang' => 'required|string',
    //         'kategori' => 'required|string',
    //         'stok' => 'required|integer|min:0',
    //         'stok_minimum' => 'required|integer|min:0',
    //         'harga_beli' => 'required|integer|min:0',
    //         'harga_jual' => 'required|integer|min:0',
    //     ]);
        
    //     StokBarang::create($validated);
        
    //     // Kirim notifikasi jika setelah tambah ada buffer
    //     $this->checkAndSendNotification();
        
    //     return redirect()->route('buffer-stok.index')
    //                      ->with('success', 'Material berhasil ditambahkan!');
    // }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang = StokBarang::findOrFail($id);
        return view('showbarang', compact('barang'));
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
        'stok' => 'required|integer|min:0',
        'stok_minimum' => 'required|integer|min:0',
        'harga_beli' => 'required|numeric|min:0', // Menggunakan numeric
        'harga_jual' => 'required|numeric|min:0', // Menggunakan numeric
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);
    
    // Siapkan data untuk update
    $data = [
        'kode_barang' => $request->kode_barang,
        'nama_barang' => $request->nama_barang,
        'kategori' => $request->kategori,
        'stok' => $request->stok,
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

    if ($stokBarang) {
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
                'id' => 'required|exists:stok_barang,id',
                'stok_minimum' => 'required|integer|min:0'
            ]);
            
            $barang = StokBarang::findOrFail($request->id);
            $barang->stok_minimum = $request->stok_minimum;
            $barang->save();
            
            // Kirim notifikasi setelah update stok minimum
            $this->checkAndSendNotification();
            
            return response()->json(['success' => true, 'message' => 'Stok minimum berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

    // 5. Hitung seluruh data yang ada di tempat sampah
    $countTrashed =
        $trashedBarang->count() +
        $trashedBarangMasuk->count() +
        $trashedNotaPembeli->count() +
        $trashedBarangKeluar->count();

    return view('sampah', compact(
        'trashedBarang',
        'trashedBarangMasuk',
        'trashedNotaPembeli',
        'trashedBarangKeluar',
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

    public function barangMasuk()
{
    $barangMasuk = BarangMasuk::with('stokBarang')->latest()->get();
    $stokBarang = StokBarang::orderBy('nama_barang')->get();
    return view('barang-masuk', compact('barangMasuk', 'stokBarang'));
}

// Menyimpan data barang masuk baru
public function storeBarangMasuk(Request $request)
{
    $request->validate([
        'stok_barang_id' => 'required|exists:stok_barang,id',
        'jumlah' => 'required|integer|min:1',
        'tanggal_masuk' => 'required|date',
        'supplier' => 'nullable|string|max:255',
    ]);

    BarangMasuk::create([
        'stok_barang_id' => $request->stok_barang_id,
        'jumlah' => $request->jumlah,
        'tanggal_masuk' => $request->tanggal_masuk,
        'supplier' => $request->supplier,
    ]);
    $stokBarang = StokBarang::findOrFail($request->stok_barang_id);
    $stokBarang->increment('stok', $request->jumlah);

    return redirect()->route('barang-masuk')->with('success', 'Barang masuk berhasil dicatat.');
}

    public function barangKeluar(Request $request)
{
    // Menggunakan join untuk pengurutan, seleksi kolom barang_keluar agar ID tidak tertimpa
    $query = BarangKeluar::with(['stokBarang', 'notaPembeli'])
        ->join('nota_pembeli', 'barang_keluar.nota_pembeli_id', '=', 'nota_pembeli.id')
        ->select('barang_keluar.*')
        ->orderBy('nota_pembeli.tanggal_keluar', 'desc');

    // PERBAIKAN: Fitur pencarian melacak nama pembeli dan tujuan di tabel nota_pembeli
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nota_pembeli.nama_pembeli', 'LIKE', "%{$search}%")
              ->orWhere('nota_pembeli.tujuan', 'LIKE', "%{$search}%")
              ->orWhere('barang_keluar.id', 'LIKE', "%{$search}%");
        });
    }

    $barangKeluar = $query->paginate(10);

    $keluarHariIni = BarangKeluar::whereHas('notaPembeli', function ($q) {
        $q->whereDate('tanggal_keluar', today());
    })->count();

    return view('barang-keluar', compact('barangKeluar', 'keluarHariIni'));
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
    $request->validate([
        'nama_barang' => 'required',
        'kategori' => 'required',
        'stok' => 'required|integer|min:0',
        'stok_minimum' => 'required|integer|min:0',
        'harga_beli' => 'required|integer|min:0',
        'harga_jual' => 'required|integer|min:0',
        'kode_barang' => ['nullable',Rule::unique('stok_barang', 'kode_barang')],
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);
    
    $data = $request->all();
    
    // Inisialisasi namaFoto = null (sesuai konsep)
    $namaFoto = null;
    
    if ($request->hasFile('gambar')) {
        // Buat nama file: timestamp_nama_original.ext
        $namaFoto = time() . '_' . $request->file('gambar')->getClientOriginalName();
        
        // Pindahkan file ke public/uploads/gambar_barang
        $request->file('gambar')->move(public_path('uploads/gambar_barang'), $namaFoto);
        
        // Simpan hanya nama filenya ke database
        $data['gambar'] = $namaFoto;
    }
    
    StokBarang::create($data);
    
    return redirect()->route('stok')->with('success', 'Material berhasil ditambahkan!');
}
public function restoreBarangMasuk($id)
{
    // Cari data di sampah menggunakan withTrashed()
    $logMasuk = BarangMasuk::withTrashed()->findOrFail($id);
    
    // Kembalikan statusnya menjadi aktif
    $logMasuk->restore();

    // Opsional: Karena data kembali aktif, kita harus menambahkan kembali stok utama barang tersebut
    $stokBarang = StokBarang::find($logMasuk->stok_barang_id);
    if ($stokBarang) {
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
        // Validasi input form (barang berupa array)
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tujuan' => 'nullable|string',
            'barang' => 'required|array|min:1',
            'barang.*.stok_barang_id' => 'required|exists:stok_barang,id',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.harga_satuan' => 'required|numeric|min:0',
            'tanggal_keluar' => 'required|date',
        ]);

        // Gunakan Database Transaction agar aman (jika satu gagal, semua dibatalkan)
        DB::beginTransaction();

        try {
            // 1. Simpan data ke tabel induk (nota_pembeli)
            $nota = NotaPembeli::create([
                'nama_pembeli' => $request->nama_pembeli,
                'tujuan' => $request->tujuan,
                'tanggal_keluar' => $request->tanggal_keluar,
                'total_harga' => 0, // sementara diisi 0
            ]);

            $totalHarga = 0;

            // 2. Looping data array barang untuk disimpan ke tabel anak (barang_keluar)
            foreach ($request->barang as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                $totalHarga += $subtotal;

                BarangKeluar::create([
                    'nota_pembeli_id' => $nota->id,
                    'stok_barang_id' => $item['stok_barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                ]);

                // 3. (Opsional) Potong stok di gudang secara otomatis
                $stok = StokBarang::find($item['stok_barang_id']);
                $stok->decrement('stok', $item['jumlah']);
            }

            // 4. Update total_harga di tabel induk dengan hasil penjumlahan asli
            $nota->update(['total_harga' => $totalHarga]);

            DB::commit();
            return redirect()->back()->with('success', 'Nota baru berhasil diterbitkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

}