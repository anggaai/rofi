<x-sidebar>
    <div class="max-w-5xl mx-auto space-y-8 pb-12">
        @if(session('success'))
            <div id="successAlert"
                class="flex items-center justify-between gap-4 px-5 py-4
                       bg-emerald-50 border border-emerald-200
                       text-emerald-700 rounded-2xl shadow-sm
                       transition-opacity duration-300">

                <div class="flex items-center gap-3">

                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                        ✓
                    </div>

                    <div>
                        <p class="text-sm font-bold">
                            Berhasil
                        </p>

                        <p class="text-xs mt-0.5">
                            {{ session('success') }}
                        </p>
                    </div>

                </div>

                <button type="button"
                    onclick="document.getElementById('successAlert').remove()"
                    class="text-emerald-500 hover:text-emerald-700 font-bold">
                    ✕
                </button>

            </div>
        @endif
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('stok') }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700 uppercase tracking-wider transition-colors mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Katalog
                </a>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Detail & Edit Material</h1>
                <p class="text-slate-500 text-sm mt-1">Perbarui informasi, status stok, dan harga material secara akurat.</p>
            </div>
            

            <div class="shrink-0 flex items-center gap-3">
                <button type="button"
                    onclick="document.getElementById('opnameModal').showModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4"/>
                    </svg>

                    Stok Opname
                </button>

                @if($barang->stok <= $barang->stok_minimum)
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-black rounded-2xl uppercase tracking-wider shadow-sm animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Stok Kritis / Menipis
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-black rounded-2xl uppercase tracking-wider shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Stok Aman
                    </span>
                @endif

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="space-y-6">
                <div class="bg-white rounded-[2rem] border-8 border-double border-blue-950 p-6 shadow-sm flex flex-col items-center justify-center text-center group">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4 self-start">Preview Foto</p>
                    
                    <div class="relative w-full aspect-square max-w-[240px] rounded-3xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center mb-4 shadow-inner">
                        @if($barang->gambar)
                            <img src="{{ asset('uploads/gambar_barang/' . $barang->gambar) }}" 
                                 id="imagePreview"
                                 alt="{{ $barang->nama_barang }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div id="imagePlaceholder" class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 text-slate-300">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-medium text-slate-400">Belum Ada Foto</span>
                            </div>
                            <img id="imagePreview" class="hidden w-full h-full object-cover">
                        @endif
                    </div>

                    <div class="space-y-1">
                        <h4 class="font-bold text-slate-800 text-base leading-tight">{{ $barang->nama_barang }}</h4>
                        <p class="text-xs font-mono text-slate-400">SKU: {{ $barang->kode_barang }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-900 to-slate-700 rounded-[2rem] p-6 text-white shadow-xl shadow-slate-900/10 space-y-4">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Histori Log</p>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span class="opacity-60">Ditambahkan Pada</span>
                            <span class="font-medium">{{ $barang->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between pb-1">
                            <span class="opacity-60">Terakhir Diperbarui</span>
                            <span class="font-medium">{{ $barang->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-blue-50 rounded-[2rem] border border-slate-200 shadow-lg overflow-hidden">
                <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="m-0">
                    @csrf
                    @method('PUT')
                    @if ($errors->any())
                        <div class="mx-8 mt-8 px-4 py-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3">
                            <div class="text-rose-600 mt-0.5">⚠️</div>
                            <div>
                                <p class="text-xs font-bold text-rose-700">Perubahan Tidak Dapat Disimpan</p>
                                <ul class="mt-0.5 space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-xs text-rose-600">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="p-8 space-y-6">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-50 pb-2">Formulir Perubahan Informasi</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                           <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kode Barang (SKU)</label>
                                <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" readonly
                                    class="w-full px-4 py-3 bg-slate-100 text-slate-400 font-mono border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-slate-200 focus:ring-0 cursor-not-allowed transition-all">
                            </div>
                            <div class="sm:col-span-2 space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Material / Barang</label>
                                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                                    class="w-full px-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori</label>
                            <input type="text" name="kategori" value="{{ old('kategori', $barang->kategori) }}" required
                                class="w-full px-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Stok Saat Ini</label>
                                <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}"   readonly
                                    class="w-full px-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 font-bold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white">
                            </div>
                           <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Batas Stok Minimum</label>
                                <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $barang->stok_minimum) }}" readonly
                                    class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-400 font-bold focus:outline-none  focus:border-slate-200 focus:ring-0 cursor-not-allowed transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Beli</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                        <span class="text-xs font-bold text-slate-400">Rp</span>
                                    </div>
                                    <input type="number" name="harga_beli" value="{{ old('harga_beli', $barang->harga_beli) }}" required min="0"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 font-semibold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Jual</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                        <span class="text-xs font-bold text-slate-400">Rp</span>
                                    </div>
                                    <input type="number" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" required min="0"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 font-semibold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ganti Foto Barang (Opsional)</label>
                            <div class="relative border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/30 hover:bg-indigo-50/10 rounded-xl p-4 transition-all duration-300 group flex items-center justify-center cursor-pointer">
                                <input type="file" name="gambar" accept="image/*" id="editFileInput"
                                    class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                    onchange="previewAndUpdateFileName(this)">
                                <div class="text-center flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 group-hover:shadow-md transition-all shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-xs font-bold text-slate-700 transition-colors" id="editFileNameText">Pilih foto baru untuk mengganti</p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">Format PNG, JPG, JPEG (Maks. 2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('stok') }}" 
                            class="px-5 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all active:scale-98">
                            Batal
                        </a>
                        <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-98">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- ========================================================= --}}
{{-- RIWAYAT PERGERAKAN STOK --}}
{{-- ========================================================= --}}

<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-7 py-5 border-b border-slate-100">

        <div class="flex flex-col gap-3">

            <div>
                <h3 class="font-black text-slate-900">
                    Riwayat Pergerakan Stok
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Riwayat seluruh perubahan stok {{ $barang->nama_barang }}
                </p>
            </div>


            {{-- CATATAN --}}
            <div class="flex items-start gap-3 px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl">

                <div class="shrink-0 w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">

                    <svg class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                </div>

                <div>

                    <p class="text-xs font-bold text-blue-700">
                        Catatan
                    </p>

                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                        Riwayat ini mencatat seluruh pergerakan stok barang,
                        baik dari pembelian, penjualan, maupun penyesuaian stok
                        di luar transaksi pembelian dan penjualan.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TABEL --}}
    {{-- ========================================================= --}}

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-slate-50 text-xs uppercase text-slate-400">

                <tr>

                    <th class="px-6 py-4 text-left">
                        Tanggal
                    </th>

                    <th class="px-6 py-4 text-left">
                        Aktivitas
                    </th>

                    <th class="px-6 py-4 text-center">
                        Masuk
                    </th>

                    <th class="px-6 py-4 text-center">
                        Keluar
                    </th>

                    <th class="px-6 py-4 text-left">
                        Keterangan
                    </th>

                    <th class="px-6 py-4 text-left">
                        Diinput Oleh
                    </th>

                    <th class="px-6 py-4 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($riwayatStok as $item)

                    <tr class="hover:bg-slate-50">

                        {{-- TANGGAL --}}
                        <td class="px-6 py-4 whitespace-nowrap">

                            {{ $item['tanggal']->format('d M Y, H:i') }}

                        </td>


                        {{-- AKTIVITAS --}}
                        <td class="px-6 py-4">

                            @if($item['aktivitas'] === 'Pembelian')

                                <span class="px-3 py-1 rounded-full
                                    bg-blue-50 text-blue-600
                                    text-xs font-bold">

                                    Pembelian

                                </span>


                            @elseif($item['aktivitas'] === 'Penjualan')

                                <span class="px-3 py-1 rounded-full
                                    bg-violet-50 text-violet-600
                                    text-xs font-bold">

                                    Penjualan

                                </span>


                            @elseif($item['aktivitas'] === 'Opname Penambahan')

                                <span class="px-3 py-1 rounded-full
                                    bg-emerald-50 text-emerald-600
                                    text-xs font-bold">

                                    Opname +

                                </span>


                            @else

                                <span class="px-3 py-1 rounded-full
                                    bg-rose-50 text-rose-600
                                    text-xs font-bold">

                                    Opname -

                                </span>

                            @endif

                        </td>


                        {{-- MASUK --}}
                        <td class="px-6 py-4 text-center">

                            @if($item['masuk'] > 0)

                                <span class="font-bold text-emerald-600">
                                    +{{ $item['masuk'] }}
                                </span>

                            @else

                                <span class="text-slate-300">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- KELUAR --}}
                        <td class="px-6 py-4 text-center">

                            @if($item['keluar'] > 0)

                                <span class="font-bold text-rose-600">
                                    -{{ $item['keluar'] }}
                                </span>

                            @else

                                <span class="text-slate-300">
                                    -
                                </span>

                            @endif

                        </td>


                                                {{-- KETERANGAN --}}
                        <td class="px-6 py-4 text-slate-600">

                            {{ $item['keterangan'] }}

                        </td>


                        {{-- DIINPUT OLEH --}}
                        <td class="px-6 py-4 whitespace-nowrap">

                            @if($item['user'] !== '-')

                                <div class="flex items-center gap-2">

                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600
                                                flex items-center justify-center
                                                text-[11px] font-black uppercase">
                                        {{ mb_substr($item['user'], 0, 1) }}
                                    </div>

                                    <span class="text-xs font-semibold text-slate-700">
                                        {{ $item['user'] }}
                                    </span>

                                </div>

                            @else

                                <span class="text-slate-300">-</span>

                            @endif

                        </td>


                        {{-- ================================================= --}}
                        {{-- DELETE --}}
                        {{-- ================================================= --}}

                        <td class="px-6 py-4 text-center">

                            @if($item['sumber'] === 'barang_masuk')

                                <form
                                    action="{{ route('barang-masuk.destroy', $item['id']) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Hapus riwayat pembelian ini? Stok dari barang masuk ini akan disesuaikan kembali.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center justify-center
                                            w-9 h-9
                                            bg-rose-50 text-rose-600
                                            border border-rose-100
                                            rounded-xl
                                            hover:bg-rose-100
                                            transition-all"
                                        title="Hapus">

                                        <svg class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12" />

                                        </svg>

                                    </button>

                                </form>


                            @elseif($item['sumber'] === 'barang_keluar')

                                <form
                                    action="{{ route('barang-keluar.destroy', $item['id']) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Hapus riwayat penjualan ini? Stok barang akan disesuaikan kembali.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center justify-center
                                            w-9 h-9
                                            bg-rose-50 text-rose-600
                                            border border-rose-100
                                            rounded-xl
                                            hover:bg-rose-100
                                            transition-all"
                                        title="Hapus">

                                        <svg class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12" />

                                        </svg>

                                    </button>

                                </form>


                            @else

                                <form
                                    action="{{ route('opname.destroy', $item['id']) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Hapus riwayat stok opname ini? Perubahan stok dari opname ini akan dibatalkan.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center justify-center
                                            w-9 h-9
                                            bg-rose-50 text-rose-600
                                            border border-rose-100
                                            rounded-xl
                                            hover:bg-rose-100
                                            transition-all"
                                        title="Hapus">

                                        <svg class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12" />

                                        </svg>

                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td colspan="7"
                            class="px-6 py-10 text-center text-slate-400">

                            Belum ada riwayat pergerakan stok.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ========================================================= --}}
    {{-- PAGINATION --}}
    {{-- ========================================================= --}}

    @if($riwayatStok->hasPages())

        <div class="px-6 py-5 border-t border-slate-100">

            {{ $riwayatStok->links() }}

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- RINGKASAN PERGERAKAN STOK --}}
    {{-- ========================================================= --}}

    <div class="px-6 py-6 bg-slate-50 border-t border-slate-100">

        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4">
            Ringkasan Pergerakan Stok
        </p>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">


            {{-- PEMBELIAN --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4">

                <p class="text-[11px] font-bold text-slate-400 uppercase">
                    Total Pembelian
                </p>

                <p class="text-xl font-black text-blue-600 mt-2">
                    {{ number_format($totalPembelian, 0, ',', '.') }}
                </p>

                <p class="text-[10px] text-slate-400 mt-1">
                    Barang masuk dari supplier
                </p>

            </div>


            {{-- TERJUAL --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4">

                <p class="text-[11px] font-bold text-slate-400 uppercase">
                    Total Terjual
                </p>

                <p class="text-xl font-black text-violet-600 mt-2">
                    {{ number_format($totalTerjual, 0, ',', '.') }}
                </p>

                <p class="text-[10px] text-slate-400 mt-1">
                    Barang keluar dari penjualan
                </p>

            </div>


            {{-- NON PEMBELIAN --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4">

                <p class="text-[11px] font-bold text-slate-400 uppercase">
                    Masuk Non Pembelian
                </p>

                <p class="text-xl font-black text-emerald-600 mt-2">
                    {{ number_format($totalMasukNonPembelian, 0, ',', '.') }}
                </p>

                <p class="text-[10px] text-slate-400 mt-1">
                    Penambahan melalui stok opname
                </p>

            </div>


            {{-- NON PENJUALAN --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4">

                <p class="text-[11px] font-bold text-slate-400 uppercase">
                    Keluar Non Penjualan
                </p>

                <p class="text-xl font-black text-rose-600 mt-2">
                    {{ number_format($totalKeluarNonPenjualan, 0, ',', '.') }}
                </p>

                <p class="text-[10px] text-slate-400 mt-1">
                    Pengurangan melalui stok opname
                </p>

            </div>

        </div>

    </div>

</div>
            <dialog id="opnameModal"
    class="w-full max-w-lg rounded-3xl p-0 backdrop:bg-slate-900/50 shadow-2xl">

    <form id="opnameForm"action="{{ route('opname.store') }}"method="POST">
        @csrf

        <input type="hidden"
            name="stok_barang_id"
            value="{{ $barang->id }}">

        <div class="p-7">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">
                        Penyesuaian Stok
                    </p>

                    <h3 class="text-xl font-black text-slate-900 mt-1">
                        Stok Opname
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $barang->nama_barang }}
                    </p>
                </div>

                <button type="button"
                    onclick="resetOpnameModal()"
                    class="w-9 h-9 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200">
                    ✕
                </button>
            </div>


            {{-- STOK SAAT INI --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 mb-5">
                <p class="text-xs text-slate-500 font-medium">
                    Stok Saat Ini
                </p>

                <p class="text-2xl font-black text-indigo-600 mt-1">
                    {{ $barang->stok }}
                </p>
            </div>


            {{-- JENIS OPNAME --}}
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    Jenis Opname
                </label>

                <select name="jenis_opname"
                    required
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500">

                    <option value="">Pilih Jenis</option>

                    <option value="penambahan"
                        {{ old('jenis_opname') == 'penambahan' ? 'selected' : '' }}>
                        Penambahan Stok
                    </option>

                    <option value="pengurangan"
                        {{ old('jenis_opname') == 'pengurangan' ? 'selected' : '' }}>
                        Pengurangan Stok
                    </option>

                </select>
            </div>


            {{-- JUMLAH --}}
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    Jumlah Perubahan Stok
                </label>

                <input type="number"
                    name="jumlah_perubahan"
                    min="1"
                    required
                    value="{{ old('jumlah_perubahan') }}"
                    placeholder="Contoh: 5"
                    class="w-full px-4 py-3 border
                        @error('jumlah_perubahan')
                            border-rose-500
                        @else
                            border-slate-200
                        @enderror
                        rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500">

                @error('jumlah_perubahan')
                    <p id="jumlahPerubahanError"
                    class="mt-2 text-xs font-semibold text-rose-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- KETERANGAN --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    Keterangan Opname
                </label>

                <textarea name="keterangan_opname"
                rows="3"
                placeholder="Contoh: Barang rusak saat pengecekan fisik"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm resize-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500">{{ old('keterangan_opname') }}</textarea>
            </div>

        </div>


        <div class="px-7 py-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">

            <button type="button"
                onclick="resetOpnameModal()"
                class="px-5 py-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600">
                Batal
            </button>

            <button type="submit"
                class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg">
                Simpan Opname
            </button>

        </div>

    </form>
</dialog>
    </div>
</x-sidebar>

<script>
    function previewAndUpdateFileName(input) {
        const textLabel = document.getElementById('editFileNameText');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');

        if (input.files && input.files.length > 0) {
            const file = input.files[0];

            textLabel.innerText = file.name;
            textLabel.classList.remove('text-slate-700');
            textLabel.classList.add('text-indigo-600', 'font-bold');

            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');

                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            };

            reader.readAsDataURL(file);

        } else {

            textLabel.innerText = "Pilih foto baru untuk mengganti";
            textLabel.classList.remove('text-indigo-600', 'font-bold');
            textLabel.classList.add('text-slate-700');
        }
    }

    function resetOpnameModal() {

    const modal = document.getElementById('opnameModal');
    const form = document.getElementById('opnameForm');

    const jenis = form.querySelector('[name="jenis_opname"]');
    const jumlah = form.querySelector('[name="jumlah_perubahan"]');
    const keterangan = form.querySelector('[name="keterangan_opname"]');

    const errorJumlah = document.getElementById('jumlahPerubahanError');

    // Kosongkan input
    jenis.value = '';
    jumlah.value = '';
    keterangan.value = '';

    // Hilangkan warna merah pada input
    jumlah.classList.remove('border-rose-500');
    jumlah.classList.add('border-slate-200');

    // Hilangkan pesan error
    if (errorJumlah) {
        errorJumlah.remove();
    }

    // Tutup modal
    modal.close();
}
    document.addEventListener('DOMContentLoaded', function () {

        @if($errors->has('jumlah_perubahan'))
            const modal = document.getElementById('opnameModal');

            if (modal && !modal.open) {
                modal.showModal();
            }
        @endif


        const successAlert = document.getElementById('successAlert');

        if (successAlert) {
            setTimeout(function () {
                successAlert.classList.add('opacity-0');

                setTimeout(function () {
                    successAlert.remove();
                }, 300);

            }, 4000);
        }

    });
</script>