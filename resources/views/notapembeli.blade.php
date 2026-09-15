<x-sidebar>
    <div class="space-y-6">
        {{-- Header Atas --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Nota Pembeli</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola, cetak, dan catat invoice penjualan material kepada pembeli.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('modalNota').classList.remove('hidden')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center group">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Nota Baru
                </button>
            </div>
        </div>

        {{-- Row Statistik Singkat --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Nota Terbit</p>
                    <h3 class="text-xl font-black text-slate-800">{{ $notaPembeli->total() }} Invoice</h3>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-xl font-black text-emerald-600">Rp {{ number_format($notaPembeli->sum('total_harga')) }}</h3>
                </div>
            </div>
        </div>

        {{-- Filter & Pencarian --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <form method="GET" action="{{ route('nota-pembeli.index') }}" class="w-full flex flex-col md:flex-row gap-3 flex-1">
                <div class="relative flex-1">
                    <input type="text" name="search" placeholder="Cari Nama Pembeli atau Nomor Nota..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                    <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all">Filter</button>
                    @if(request('search'))
                        <a href="{{ route('nota-pembeli.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-200">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Wadah Tabel Utama --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-[0.2em]">
                            <th class="px-6 py-4.5 font-bold">No. Nota & Pembeli</th>
                            <th class="px-6 py-4.5 font-bold">Material / Barang Yang Dibeli</th>
                            <th class="px-6 py-4.5 font-bold text-right">Total Tagihan</th>
                            <th class="px-6 py-4.5 font-bold text-center w-24">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/70 text-sm">
                        @forelse($notaPembeli as $item)
                        <tr class="group hover:bg-slate-50/50 transition-all duration-200">
                            {{-- Kolom Nota & Identitas Pembeli --}}
                            <td class="px-6 py-5 align-top">
                                <div class="flex flex-col">
                                    <span class="text-xs font-mono text-indigo-600 bg-indigo-50/60 self-start px-2 py-0.5 rounded-md font-bold tracking-wide mb-1">
                                        MJ3#-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="text-slate-800 font-bold leading-tight">{{ $item->nama_pembeli }}</span>
                                    <span class="text-slate-400 text-xs mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $item->tujuan ?? 'Tidak ada alamat' }}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400 mt-1">{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y H:i') }}</span>
                                </div>
                            </td>

                            {{-- Kolom Banyak Material (Looping Detail Anak) --}}
                            <td class="px-6 py-5 align-top">
                                <div class="space-y-2">
                                    @foreach($item->barangKeluar as $bk)
                                        <div class="flex items-center justify-between text-xs bg-slate-50 p-2 rounded-xl border border-slate-100/50">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-700">{{ $bk->stokBarang->nama_barang ?? 'Barang Terhapus' }}</span>
                                                <span class="text-[10px] font-extrabold bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded-md">{{ $bk->jumlah }} Unit</span>
                                            </div>
                                            <span class="text-slate-400 font-mono">@ Rp {{ number_format($bk->harga_satuan) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Kolom Total Harga Nota --}}
                            <td class="px-6 py-5 align-top text-right font-black text-slate-800 text-base">
                                Rp {{ number_format($item->total_harga) }}
                            </td>

                            {{-- Kolom Tindakan --}}
                            <td class="px-6 py-5 align-top text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('nota-pembeli.cetak', $item->id) }}" 
                                        target="_blank" 
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" 
                                        title="Cetak Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                    </a>
                                    
                                    <form action="{{ route('nota-pembeli.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data nota beserta seluruh daftar rincian barang keluar di dalamnya?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium text-slate-500">Belum ada riwayat transaksi nota.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Bagian Pagination --}}
            @if($notaPembeli->hasPages())
            <div class="p-5 border-t border-slate-100 bg-slate-50/50">
                {{ $notaPembeli->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- MODAL INPUT NOTA BARU (MULTIPLE ITEMS WITH LINE TOTAL) --}}
    <div id="modalNota" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 transition-all {{ $errors->any() ? '' : 'hidden' }}" onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-3xl w-full max-w-3xl p-6 shadow-2xl transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                <h2 class="text-lg font-black text-slate-800">Buat Nota Pembeli Baru</h2>
                <button onclick="document.getElementById('modalNota').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">&times;</button>
            </div>
            
            <form action="{{ route('nota-pembeli.store') }}" method="POST" id="formNota">
                @csrf
                 @if ($errors->has('stok'))
                    <div class="whitespace-pre-line mb-4 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold">
                        {{ $errors->first('stok') }}
                    </div>
                @endif
                <div class="space-y-4">
                    {{-- Nama Pembeli --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" required class="w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5" placeholder="Masukkan nama pembeli">
                    </div>

                    {{-- Alamat Pembeli --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Alamat / Tujuan Pengiriman</label>
                        <textarea name="tujuan" rows="2" class="w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2.5" placeholder="Alamat lengkap tujuan pengiriman (opsional)"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Tanggal Pembelian
                        </label>

                        <input
                            type="datetime-local"
                            name="tanggal_keluar"
                            value="{{ now()->format('Y-m-d\TH:i') }}"
                            required
                            class="w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5">
                    </div>

                    {{-- Bagian Pembelian Barang Dinamis --}}
                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Daftar Material Yang Dibeli</label>
                            <button type="button" id="btnTambahBarang" class="text-xs bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold px-3 py-1.5 rounded-lg transition-all">+ Tambah Item</button>
                        </div>
                        
                        {{-- Wrapper untuk Baris Barang Dinamis --}}
                        <div id="wrapperBarang" class="space-y-3">
                            <div
                                id="stokSubmitError"
                                class="hidden whitespace-pre-line mt-3 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold">
                            </div>
                            {{-- BARIS PERTAMA (DEFAULT) --}}
                            <div class="grid grid-cols-12 gap-2 item-barang items-center bg-slate-50/50 p-2 rounded-xl border border-slate-100">
                                {{-- Pilih Barang (Kolom 4) --}}
                                <div class="col-span-4">
                                    <select name="barang[0][stok_barang_id]" required class="select-barang w-full border-slate-200 rounded-xl text-xs py-2 focus:ring-1 focus:ring-indigo-500">
                                        <option value="">-- Pilih Material --</option>
                                        @foreach($stokBarang as $sb)
                                            <option value="{{ $sb->id }}"
                                                    data-harga="{{ $sb->harga_jual }}"
                                                    data-stok="{{ $sb->stok }}">
                                                    {{ $sb->nama_barang }} (Sisa: {{ $sb->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Qty (Kolom 2) --}}
                                <div class="col-span-2">
                                    <input type="number"
                                        name="barang[0][jumlah]"
                                        required
                                        min="1"
                                        placeholder="Qty"
                                        class="input-jumlah w-full border-slate-200 rounded-xl text-xs py-2 text-center focus:ring-1 focus:ring-indigo-500">

                                    <p class="qty-error hidden mt-1 px-2 py-1 text-[10px] leading-tight font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg">
                                    </p>
                                </div>
                                {{-- Harga Satuan (Kolom 3) --}}
                                <div class="col-span-3">
                                    <input type="number" name="barang[0][harga_satuan]" required min="0" placeholder="Harga (Rp)" class="input-harga w-full border-slate-200 rounded-xl text-xs py-2 bg-white focus:ring-1 focus:ring-indigo-500">
                                </div>
                                {{-- Total Per Macam Barang (Kolom 2) --}}
                                <div class="col-span-2">
                                    <input type="text" readonly placeholder="Total" class="input-total-item w-full border-slate-100 bg-slate-100/80 text-slate-600 rounded-xl text-xs py-2 text-right font-bold focus:outline-none">
                                </div>
                                {{-- Tombol Hapus (Kolom 1) --}}
                                <div class="col-span-1 text-center">
                                    <button type="button" class="btn-hapus-baris text-rose-400 hover:text-rose-600 text-lg font-bold transition-colors">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kalkulasi Live Subtotal Keseluruhan Nota --}}
                    <div class="p-4 bg-indigo-50/50 border border-indigo-100 rounded-2xl flex justify-between items-center mt-2">
                        <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Subtotal Tagihan Nota</span>
                        <span class="text-base font-black text-indigo-600" id="liveSubtotal">Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
                    <button type="button" onclick="document.getElementById('modalNota').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-100 transition-all">Simpan & Terbitkan</button>
                </div>
            </form>
        </div>
    </div>

    <style>
    .searchable-select {
        position: relative;
        width: 100%;
    }

    .searchable-trigger {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        font-size: 12px;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .searchable-trigger:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 1px #6366f1;
    }

    .searchable-dropdown-panel {
        position: absolute;
        top: auto;
        bottom: calc(100% + 5px);
        left: 0;
        width: 100%;
        min-width: 280px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        z-index: 9999;
        overflow: hidden;
    }

    .searchable-dropdown-panel.hidden {
        display: none;
    }

    /* PENCARIAN TETAP DI ATAS */
    .searchable-search-wrapper {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
        padding: 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .searchable-search {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        outline: none;
    }

    .searchable-search:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 1px #6366f1;
    }

    /* YANG DISCROLL HANYA DAFTAR BARANG */
    .searchable-options {
        max-height: 220px;
        overflow-y: auto;
    }

    .searchable-option {
        width: 100%;
        display: block;
        padding: 9px 12px;
        text-align: left;
        font-size: 12px;
        background: white;
        border: none;
        cursor: pointer;
    }

    .searchable-option:hover {
        background: #eef2ff;
        color: #4f46e5;
    }

    .searchable-no-result {
        padding: 12px;
        text-align: center;
        font-size: 12px;
        color: #94a3b8;
    }
</style>

    {{-- JavaScript Interaktif untuk Perhitungan Berjenjang --}}
    <script>
document.addEventListener('DOMContentLoaded', function () {

    const wrapperBarang = document.getElementById('wrapperBarang');
    const btnTambahBarang = document.getElementById('btnTambahBarang');
    const liveSubtotal = document.getElementById('liveSubtotal');
    const formNota = document.getElementById('formNota');
    const stokSubmitError = document.getElementById('stokSubmitError');

    let itemIndex = 1;

function buatSearchableSelect(select) {

    // Jangan dibuat dua kali
    if (select.dataset.searchable === 'true') {
        return;
    }

    select.dataset.searchable = 'true';

    // Sembunyikan select asli
    select.style.display = 'none';


    // Wrapper
    const wrapper = document.createElement('div');
    wrapper.className = 'searchable-select';


    // Tombol dropdown
    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'searchable-trigger';

    trigger.innerHTML = `
        <span class="searchable-selected">
            -- Pilih Material --
        </span>

        <span>⌄</span>
    `;


    // Panel dropdown
    const panel = document.createElement('div');
    panel.className =
        'searchable-dropdown-panel hidden';


    // Search
    const searchWrapper =
        document.createElement('div');

    searchWrapper.className =
        'searchable-search-wrapper';


    const searchInput =
        document.createElement('input');

    searchInput.type = 'text';

    searchInput.className =
        'searchable-search';

    searchInput.placeholder =
        'Cari material...';


    searchWrapper.appendChild(searchInput);


    // Daftar pilihan
    const optionsContainer =
        document.createElement('div');

    optionsContainer.className =
        'searchable-options';


    panel.appendChild(searchWrapper);
    panel.appendChild(optionsContainer);


    wrapper.appendChild(trigger);
    wrapper.appendChild(panel);


    // Masukkan setelah select asli
    select.insertAdjacentElement(
        'afterend',
        wrapper
    );


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN DAFTAR BARANG
    |--------------------------------------------------------------------------
    */
    function tampilkanOptions(keyword = '') {

        optionsContainer.innerHTML = '';

        const keywordKecil =
            keyword.toLowerCase();

        let jumlahHasil = 0;


        Array.from(select.options).forEach(option => {

            // Lewati placeholder
            if (!option.value) {
                return;
            }


            const nama =
                option.textContent.trim();


            if (
                !nama
                    .toLowerCase()
                    .includes(keywordKecil)
            ) {
                return;
            }


            jumlahHasil++;


            const tombol =
                document.createElement('button');

            tombol.type = 'button';

            tombol.className =
                'searchable-option';

            tombol.textContent =
                nama;


            tombol.addEventListener(
                'click',
                function () {

                    /*
                    |--------------------------------------------------------------------------
                    | SET VALUE KE SELECT ASLI
                    |--------------------------------------------------------------------------
                    */

                    select.value =
                        option.value;


                    trigger.querySelector(
                        '.searchable-selected'
                    ).textContent =
                        nama;


                    panel.classList.add(
                        'hidden'
                    );


                    searchInput.value = '';


                    /*
                    |--------------------------------------------------------------------------
                    | PENTING
                    |--------------------------------------------------------------------------
                    |
                    | Jalankan event change milik kode lama.
                    | Jadi harga otomatis dan validasi stok
                    | tetap bekerja.
                    |
                    */

                    select.dispatchEvent(
                        new Event(
                            'change',
                            {
                                bubbles: true
                            }
                        )
                    );

                }
            );


            optionsContainer.appendChild(
                tombol
            );

        });


        if (jumlahHasil === 0) {

            const kosong =
                document.createElement('div');

            kosong.className =
                'searchable-no-result';

            kosong.textContent =
                'Material tidak ditemukan';

            optionsContainer.appendChild(
                kosong
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | BUKA DROPDOWN
    |--------------------------------------------------------------------------
    */
    trigger.addEventListener(
        'click',
        function () {

            // Tutup dropdown lainnya
            document
                .querySelectorAll(
                    '.searchable-dropdown-panel'
                )
                .forEach(item => {

                    if (item !== panel) {
                        item.classList.add(
                            'hidden'
                        );
                    }

                });


            panel.classList.toggle(
                'hidden'
            );


            if (
                !panel.classList.contains(
                    'hidden'
                )
            ) {

                tampilkanOptions();

                setTimeout(() => {
                    searchInput.focus();
                }, 50);
            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SEARCH LANGSUNG SAAT DIKETIK
    |--------------------------------------------------------------------------
    */
    searchInput.addEventListener(
        'input',
        function () {

            tampilkanOptions(
                this.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | JANGAN TUTUP SAAT SEARCH DIKLIK
    |--------------------------------------------------------------------------
    */
    searchInput.addEventListener(
        'click',
        function (e) {

            e.stopPropagation();

        }
    );


    // Tampilkan data awal
    tampilkanOptions();
}
document
    .querySelectorAll('.select-barang')
    .forEach(select => {

        buatSearchableSelect(select);

    });

    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL HARGA
    |--------------------------------------------------------------------------
    */
    function kalkulasiKasir() {

        let subtotalGlobal = 0;

        const rows = wrapperBarang.querySelectorAll('.item-barang');

        rows.forEach(row => {

            const qty =
                parseFloat(row.querySelector('.input-jumlah').value) || 0;

            const harga =
                parseFloat(row.querySelector('.input-harga').value) || 0;

            const totalItem = qty * harga;

            row.querySelector('.input-total-item').value =
                totalItem.toLocaleString('id-ID');

            subtotalGlobal += totalItem;
        });

        liveSubtotal.innerText =
            'Rp ' + subtotalGlobal.toLocaleString('id-ID');
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI STOK
    |--------------------------------------------------------------------------
    |
    | Fungsi ini akan:
    |
    | 1. Membaca semua baris barang.
    | 2. Menggabungkan barang dengan ID yang sama.
    | 3. Menjumlahkan seluruh Qty.
    | 4. Membandingkan dengan stok asli.
    |
    */
    function validasiStok(tampilkanNotifikasiSubmit = false) {

        const rows =
            wrapperBarang.querySelectorAll('.item-barang');

        const barangGabungan = {};

        let adaError = false;

        let daftarError = [];


        /*
        |--------------------------------------------------------------------------
        | RESET ERROR SEBELUM MENGHITUNG ULANG
        |--------------------------------------------------------------------------
        */
        rows.forEach(row => {

            const inputQty =
                row.querySelector('.input-jumlah');

            const bubble =
                row.querySelector('.qty-error');

            inputQty.classList.remove(
                'border-rose-500',
                'ring-1',
                'ring-rose-300'
            );

            if (bubble) {

                bubble.classList.add('hidden');

                bubble.innerText = '';
            }
        });


        /*
        |--------------------------------------------------------------------------
        | KELOMPOKKAN BARANG BERDASARKAN ID
        |--------------------------------------------------------------------------
        */
        rows.forEach(row => {

            const selectBarang =
                row.querySelector('.select-barang');

            const inputQty =
                row.querySelector('.input-jumlah');

            const barangId =
                selectBarang.value;

            const qty =
                parseInt(inputQty.value) || 0;


            // Barang belum dipilih
            if (!barangId) {
                return;
            }


            const selectedOption =
                selectBarang.options[
                    selectBarang.selectedIndex
                ];


            const stok =
                parseInt(
                    selectedOption.getAttribute('data-stok')
                ) || 0;


            const namaBarang =
                selectedOption.textContent
                    .replace(/\(Sisa:.*?\)/g, '')
                    .trim();


            /*
            |--------------------------------------------------------------------------
            | Jika barang belum pernah ditemukan
            |--------------------------------------------------------------------------
            */
            if (!barangGabungan[barangId]) {

                barangGabungan[barangId] = {

                    nama: namaBarang,

                    stok: stok,

                    totalQty: 0,

                    rows: []
                };
            }


            /*
            |--------------------------------------------------------------------------
            | TAMBAHKAN QTY
            |--------------------------------------------------------------------------
            |
            | Misalnya:
            |
            | Semen = 5
            | Semen = 4
            |
            | totalQty = 9
            |
            */
            barangGabungan[barangId].totalQty += qty;

            barangGabungan[barangId].rows.push(row);
        });


        /*
        |--------------------------------------------------------------------------
        | BANDINGKAN TOTAL QTY DENGAN STOK
        |--------------------------------------------------------------------------
        */
        Object.values(barangGabungan).forEach(barang => {

            if (barang.totalQty > barang.stok) {

                adaError = true;


                const pesanBubble =
                    `Qty ${barang.totalQty} melebihi stok ${barang.stok}`;


                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN BUBBLE DI SEMUA BARIS BARANG YANG SAMA
                |--------------------------------------------------------------------------
                */
                barang.rows.forEach(row => {

                    const inputQty =
                        row.querySelector('.input-jumlah');

                    const bubble =
                        row.querySelector('.qty-error');


                    inputQty.classList.add(
                        'border-rose-500',
                        'ring-1',
                        'ring-rose-300'
                    );


                    if (bubble) {

                        bubble.innerText =
                            pesanBubble;

                        bubble.classList.remove('hidden');
                    }
                });


                daftarError.push(
                    `${barang.nama}: Qty ${barang.totalQty}, sedangkan stok hanya ${barang.stok}.`
                );
            }
        });


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI SAAT TOMBOL SIMPAN DITEKAN
        |--------------------------------------------------------------------------
        */
        if (adaError) {

            if (
                tampilkanNotifikasiSubmit ||
                !stokSubmitError.classList.contains('hidden')
            ) {

                stokSubmitError.innerText =
                    "Stok tidak mencukupi. Periksa kembali jumlah barang:\n\n" +
                    daftarError.join('\n');

                stokSubmitError.classList.remove('hidden');
            }

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA SUDAH VALID
        |--------------------------------------------------------------------------
        */
        stokSubmitError.classList.add('hidden');

        stokSubmitError.innerText = '';

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | SAAT BARANG DIPILIH
    |--------------------------------------------------------------------------
    */
    wrapperBarang.addEventListener('change', function (e) {

        if (e.target.classList.contains('select-barang')) {

            const selectedOption =
                e.target.options[e.target.selectedIndex];

            const hargaJual =
                selectedOption.getAttribute('data-harga');

            const row =
                e.target.closest('.item-barang');

            const inputHarga =
                row.querySelector('.input-harga');


            if (hargaJual) {

                inputHarga.value = hargaJual;

            } else {

                inputHarga.value = '';
            }


            kalkulasiKasir();

            validasiStok();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | SAAT QTY / HARGA DIKETIK
    |--------------------------------------------------------------------------
    */
    wrapperBarang.addEventListener('input', function (e) {

        if (
            e.target.classList.contains('input-jumlah') ||
            e.target.classList.contains('input-harga')
        ) {

            kalkulasiKasir();
        }


        /*
        |--------------------------------------------------------------------------
        | QTY LANGSUNG DIVALIDASI SAAT DIKETIK
        |--------------------------------------------------------------------------
        */
        if (e.target.classList.contains('input-jumlah')) {

            validasiStok();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH BARANG BARU
    |--------------------------------------------------------------------------
    */
    btnTambahBarang.addEventListener('click', function () {

        const barisBaru = `

        <div class="grid grid-cols-12 gap-2 item-barang items-center bg-slate-50/50 p-2 rounded-xl border border-slate-100">

            <div class="col-span-4">

                <select
                    name="barang[${itemIndex}][stok_barang_id]"
                    required
                    class="select-barang w-full border-slate-200 rounded-xl text-xs py-2 focus:ring-1 focus:ring-indigo-500">

                    <option value="">
                        -- Pilih Material --
                    </option>

                    @foreach($stokBarang as $sb)
                        <option 
                            value="{{ $sb->id }}" 
                            data-harga="{{ $sb->harga_jual }}"
                            data-stok="{{ $sb->stok }}">
                            
                            {{ $sb->nama_barang }} (Sisa: {{ $sb->stok }})
                        </option>
                    @endforeach

                </select>

            </div>


            <div class="col-span-2">
                <input 
                    type="number"
                    name="barang[${itemIndex}][jumlah]"
                    required
                    min="1"
                    placeholder="Qty"
                    class="input-jumlah w-full border-slate-200 rounded-xl text-xs py-2 text-center focus:ring-1 focus:ring-indigo-500">

                <p class="qty-error hidden mt-1 px-2 py-1 text-[10px] leading-tight font-semibold text-rose-600 bg-rose-50 border border-rose-100 rounded-lg">
                </p>
            </div>


            <div class="col-span-3">

                <input
                    type="number"
                    name="barang[${itemIndex}][harga_satuan]"
                    required
                    min="0"
                    placeholder="Harga (Rp)"
                    class="input-harga w-full border-slate-200 rounded-xl text-xs py-2 bg-white focus:ring-1 focus:ring-indigo-500"
                >

            </div>


            <div class="col-span-2">

                <input
                    type="text"
                    readonly
                    placeholder="Total"
                    class="input-total-item w-full border-slate-100 bg-slate-100/80 text-slate-600 rounded-xl text-xs py-2 text-right font-bold focus:outline-none"
                >

            </div>


            <div class="col-span-1 text-center">

                <button
                    type="button"
                    class="btn-hapus-baris text-rose-400 hover:text-rose-600 text-lg font-bold transition-colors">
                    &times;
                </button>

            </div>

        </div>

        `;


        wrapperBarang.insertAdjacentHTML(
            'beforeend',
            barisBaru
        );

const barisTerbaru =
    wrapperBarang.lastElementChild;

const selectTerbaru =
    barisTerbaru.querySelector(
        '.select-barang'
    );

buatSearchableSelect(
    selectTerbaru
);

        itemIndex++;
    });


    /*
    |--------------------------------------------------------------------------
    | HAPUS BARIS
    |--------------------------------------------------------------------------
    */
    wrapperBarang.addEventListener('click', function (e) {

        if (
            e.target.classList.contains('btn-hapus-baris')
        ) {

            const totalBaris =
                wrapperBarang.querySelectorAll('.item-barang').length;


            if (totalBaris > 1) {

                e.target
                    .closest('.item-barang')
                    .remove();


                /*
                |--------------------------------------------------------------------------
                | HITUNG ULANG SETELAH BARANG DIHAPUS
                |--------------------------------------------------------------------------
                */
                kalkulasiKasir();

                validasiStok();


            } else {

                alert(
                    'Minimal harus ada 1 material di dalam nota!'
                );
            }
        }
    });


    /*
    |--------------------------------------------------------------------------
    | VALIDASI KEDUA SAAT SIMPAN NOTA
    |--------------------------------------------------------------------------
    */
    formNota.addEventListener('submit', function (e) {

        const valid =
            validasiStok(true);


        if (!valid) {

            /*
            |--------------------------------------------------------------------------
            | JANGAN KIRIM FORM
            |--------------------------------------------------------------------------
            */
            e.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | ARAHKAN KE NOTIFIKASI
            |--------------------------------------------------------------------------
            */
            stokSubmitError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });


            /*
            |--------------------------------------------------------------------------
            | FOKUS KE QTY YANG BERMASALAH
            |--------------------------------------------------------------------------
            */
            const inputError =
                wrapperBarang.querySelector(
                    '.input-jumlah.border-rose-500'
                );


            if (inputError) {

                inputError.focus();
            }

            return false;
        }
    });

});
</script>
</x-sidebar>