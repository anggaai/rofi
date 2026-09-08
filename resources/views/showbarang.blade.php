<x-sidebar>
    <div class="max-w-5xl mx-auto space-y-8 pb-12">
        
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

            <div class="shrink-0">
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
                                <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}" required min="0"
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

            // Proses pembacaan file gambar untuk live preview
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if(placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        } else {
            textLabel.innerText = "Pilih foto baru untuk mengganti";
            textLabel.classList.remove('text-indigo-600', 'font-bold');
            textLabel.classList.add('text-slate-700');
        }
    }
</script>