<x-sidebar>
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Stok Material</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola seluruh ketersediaan material bangunan di gudang utama.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <form action="{{ route('stok') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                    <div class="relative flex-1 md:w-80">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari nama barang..." 
                            class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm"
                        >
                        @if(request('search'))
            <a href="{{ route('stok') }}" 
               class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-all"
               title="Reset Pencarian">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        @endif
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all shrink-0">
                        Cari
                    </button>
                </form>

                <button onclick="openModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-100 transition-all shrink-0 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Barang
                </button>
                </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-1 rounded-3xl shadow-lg shadow-indigo-100">
                <div class="bg-white/10 backdrop-blur-md p-5 rounded-[1.4rem] text-white">
                    <p class="text-xs font-bold opacity-80 uppercase tracking-widest">Total Produk</p>
                    <h3 class="text-3xl font-black mt-1">{{ number_format($totalProduk) }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $kategoriAktif }} <span class="text-xs font-medium text-slate-400">Jenis</span></h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Nilai Aset</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">Rp {{ number_format($totalNilaiAset / 1000000, 1) }}jt</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm border-l-4 {{ $persentaseStokAman < 50 ? 'border-l-rose-500' : 'border-l-emerald-500' }}">
                <p class="text-xs font-bold {{ $persentaseStokAman < 50 ? 'text-rose-600' : 'text-emerald-600' }} uppercase tracking-widest">Stok Aman</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $persentaseStokAman }}%</h3>
            </div>
        </div>

        <!-- Grid Material -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($stokBarang as $item)
                <div class="group bg-white rounded-[2rem] border-4 border-slate-200 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="relative h-44 bg-slate-50 overflow-hidden flex items-center justify-center border-b border-slate-100">
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur shadow-sm text-[10px] font-black {{ $item->stok <= $item->stok_minimum ? 'text-red-600' : 'text-indigo-600' }} rounded-lg uppercase tracking-wider">
                                {{ $item->kategori }}
                            </span>
                        </div>
                        @if($item->gambar)
                            <img src="{{ asset('uploads/gambar_barang/' . $item->gambar) }}"  
                                alt="{{ $item->nama_barang }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-[10px] text-slate-400 font-medium">No Image</span>
                            </div>
                        @endif
                        @if($item->stok <= $item->stok_minimum)
                            <div class="absolute top-4 right-4 z-10">
                                <span class="px-3 py-1 bg-red-500 text-white text-[10px] font-bold rounded-lg animate-pulse shadow-lg">
                                    Stok Kritis!
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="text-[10px] font-mono text-slate-400 mb-1">SKU: {{ $item->kode_barang }}</p>
                                <h4 class="font-bold text-slate-800 text-base group-hover:text-indigo-600 transition-colors leading-tight min-h-[2.5rem] line-clamp-2">
                                    {{ $item->nama_barang }}
                                </h4>
                            </div>
                        </div>

                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                @if($item->stok <= $item->stok_minimum)
                                    <p class="text-[10px] font-bold text-orange-500 italic mb-0.5 animate-pulse">⚠️ Mulai Menipis</p>
                                @else
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-0.5">Sisa Stok</p>
                                @endif
                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-black {{ $item->stok <= $item->stok_minimum ? 'text-red-600' : 'text-slate-900' }}">{{ number_format($item->stok) }}</span>
                                </div>
                                @if($item->stok <= $item->stok_minimum)
                                    <p class="text-[10px] text-slate-400 mt-0.5">Min: {{ $item->stok_minimum }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Harga Jual</p>
                                <p class="text-lg font-black text-indigo-600">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2">
                            <a href="{{ route('barang.show', $item->id) }}" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl text-center text-xs font-bold hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-100 transition-all duration-300">
                                Lihat Detail
                            </a>
                            
                            <form action="{{ route('buffer.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus material ini?')" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 bg-slate-100/80 text-slate-500 hover:bg-rose-600 hover:text-white rounded-xl border border-slate-200/50 hover:border-rose-600 hover:shadow-lg hover:shadow-rose-100 transition-all duration-300 flex items-center justify-center group" title="Hapus Material">
                                    <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-dashed border-slate-200">
                    <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5M14 10h1"/>
                    </svg>
                    <h3 class="text-lg font-bold text-slate-700">Tidak Ada Data Material</h3>
                    <p class="text-slate-400 text-sm mt-1">
                        @if(request('search'))
                            Material dengan kata kunci "<strong>{{ request('search') }}</strong>" tidak ditemukan.
                        @else
                            Material tidak ditemukan atau data stok di gudang masih kosong.
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('stok') }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                            ← Lihat semua material
                        </a>
                    @else
                        <button type="button" onclick="openModal()" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-all">
                            + Tambah Material Baru
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
   <div id="modalOverlay" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 transition-opacity duration-300 flex items-center justify-center p-4 sm:p-6">
    
    <div class="bg-white rounded-3xl shadow-2xl  border-blue-500 w-full max-w-2xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300 flex flex-col max-h-[90vh]" id="modalBox">
        
        <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-indigo-50/30 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-100 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Tambah Material Baru</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Masukkan detail data stok barang secara lengkap.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-all group">
                <svg class="w-5 h-5 transform group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="warningBox" class="mx-6 mt-4 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/60 flex items-start gap-3 text-amber-900 shadow-xs animate-pulse-fast transition-all duration-500 opacity-100 visible">
            <div class="p-1.5 bg-amber-100 rounded-xl text-amber-600 shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="text-xs leading-relaxed">
                <span class="font-semibold block text-amber-950 mb-0.5">Perhatian Sebelum Menambahkan</span>
                <p class="text-amber-800/90 font-medium">
                    Mohon periksa terlebih dahulu data barang yang ingin ditambahkan pada kolom pencarian agar mengurangi kemungkinan duplikasi yang tidak diinginkan.
                </p>
            </div>
        </div>

        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0 m-0">
            @csrf
            
            <div class="p-6 space-y-4.5 overflow-y-auto flex-1 content-custom-scroll bg-white">
                
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Barang</label>
                    <input type="text" name="nama_barang" required 
                        class="w-full px-4 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white" 
                        placeholder="Contoh: Semen Tiga Roda 40kg">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori</label>
                    <input type="text" name="kategori" required 
                        class="w-full px-4 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white" 
                        placeholder="Contoh: Pengikat / Semen">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Stok Awal</label>
                        <div class="relative w-full">
                            <input type="number" name="stok" required min="0" 
                                class="w-full pl-4 pr-14 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white" 
                                placeholder="0">
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Stok Minimum</label>
                        <div class="relative w-full">
                            <input type="number" name="stok_minimum" required min="0" 
                                class="w-full pl-4 pr-14 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white" 
                                placeholder="10">
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Beli</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-slate-400">Rp</span>
                            </div>
                            <input type="number" name="harga_beli" required min="0" 
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white" 
                                placeholder="0">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Jual</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-slate-400">Rp</span>
                            </div>
                            <input type="number" name="harga_jual" required min="0" 
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/60 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all focus:bg-white" 
                                placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Foto Barang</label>
                    <div class="relative border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 hover:bg-indigo-50/10 rounded-xl p-4 transition-all duration-300 group flex items-center justify-center cursor-pointer">
                        <input type="file" name="gambar" accept="image/*" id="fileInput"
                            class="absolute inset-0 opacity-0 cursor-pointer z-10"
                            onchange="updateFileName(this)">
                        <div class="text-center flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 group-hover:shadow-md transition-all shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-xs font-bold text-slate-700 transition-colors" id="fileNameText">Pilih atau Tarik Foto ke Sini</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Format PNG, JPG, JPEG (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeModal()" 
                    class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-slate-700 transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-95">
                    Simpan Material
                </button>
            </div>
        </form>
    </div>
</div>
</x-sidebar>
<script>
    function openModal() {
        const overlay = document.getElementById('modalOverlay');
        const box = document.getElementById('modalBox');
        const warningBox = document.getElementById('warningBox');
        
        // Reset warning box agar muncul dan berkedip lagi setiap modal dibuka
        if (warningBox) {
            warningBox.classList.remove('hidden', 'opacity-0');
            warningBox.classList.add('opacity-100', 'animate-pulse-fast');
        }

        overlay.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 50);
    }

    function closeModal() {
        const overlay = document.getElementById('modalOverlay');
        const box = document.getElementById('modalBox');
        
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function updateFileName(input) {
        const textLabel = document.getElementById('fileNameText');
        if (input.files && input.files.length > 0) {
            textLabel.innerText = input.files[0].name;
            textLabel.classList.remove('text-slate-700');
            textLabel.classList.add('text-indigo-600', 'font-bold');
        } else {
            textLabel.innerText = "Pilih atau Tarik Foto ke Sini";
            textLabel.classList.remove('text-indigo-600', 'font-bold');
            textLabel.classList.add('text-slate-700');
        }
    }

    // --- TAMBAHAN KODE UNTUK MENGHILANGKAN WARNING SAAT INPUT DIKLIK ---
    document.addEventListener('DOMContentLoaded', function () {
        const warningBox = document.getElementById('warningBox');
        const modalBox = document.getElementById('modalBox');

        if (modalBox && warningBox) {
            // Menggunakan event 'focusin' pada container modal.
            // Cara ini jauh lebih efisien karena otomatis mendeteksi SEMUA input
            // tanpa perlu mendata/looping input satu per satu.
            modalBox.addEventListener('focusin', function (e) {
                // Periksa apakah elemen yang difokuskan adalah elemen input/select/textarea
                if (['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) {
                    // Cek jika warningBox masih terlihat
                    if (!warningBox.classList.contains('hidden')) {
                        // 1. Hentikan animasi kelap-kelip
                        warningBox.classList.remove('animate-pulse-fast');
                        // 2. Beri efek fade-out transparan
                        warningBox.classList.remove('opacity-100');
                        warningBox.classList.add('opacity-0');

                        // 3. Hapus elemen dari layout setelah animasi fade-out selesai (500ms)
                        setTimeout(() => {
                            warningBox.classList.add('hidden');
                        }, 500);
                    }
                }
            });
        }
    });
</script>

<style>
    /* Spacing kustom agar form lebih presisi */
    .space-y-4\.5 > :not([hidden]) ~ :not([hidden]) {
        --tw-space-y-reverse: 0;
        margin-top: calc(1.125rem * calc(1 - var(--tw-space-y-reverse)));
        margin-bottom: calc(1.125rem * var(--tw-space-y-reverse));
    }
    .content-custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .content-custom-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .content-custom-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .content-custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }

    /* --- TAMBAHAN UNTUK ANIMASI WARNING --- */
    @keyframes pulse-fast {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.55;
            transform: scale(0.98);
            background-color: rgba(254, 243, 199, 0.9); /* Efek redup-terang yang halus */
        }
    }

    .animate-pulse-fast {
        animation: pulse-fast 1.4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
