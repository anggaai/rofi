<x-sidebar>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Log Barang Masuk</h1>
                <p class="text-slate-500 text-sm mt-1">Pantau dan catat setiap material yang baru sampai dari supplier.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('modalInput').classList.remove('hidden')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Input Barang Masuk
                </button>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4 items-center">
            <div class="relative flex-1">
                <input type="text" placeholder="Cari No. Referensi atau Supplier..." class="w-full pl-10 pr-4 py-2 text-sm border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="flex items-center gap-2">
                <input type="date" class="text-sm border-slate-200 rounded-xl focus:ring-indigo-500 py-2">
                <button class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">Filter</button>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse table-auto">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100 text-slate-400 text-[10px] uppercase tracking-[0.2em]">
                    <th class="px-6 py-4.5 font-bold">Waktu & Referensi</th>
                    <th class="px-6 py-4.5 font-bold">Material</th>
                    <th class="px-6 py-4.5 font-bold text-center">Jumlah</th>
                    <th class="px-6 py-4.5 font-bold">Supplier</th>
                    <th class="px-6 py-4.5 font-bold text-center">Status</th>
                    <th class="px-6 py-4.5 font-bold text-center w-24">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100/70">
                @forelse($barangMasuk as $item)
                <tr class="group hover:bg-slate-50/50 transition-all duration-200">
                    <td class="px-6 py-5 align-middle">
                        <div class="flex flex-col">
                            <span class="text-slate-700 font-semibold text-sm leading-tight">
                                {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d F Y') }}
                            </span>
                            <span class="text-[10px] font-mono text-indigo-600 mt-1.5 bg-indigo-50/60 self-start px-2 py-0.5 rounded-md font-medium tracking-wide">
                                BM-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </td>
                    
                    <td class=" pr-5 py-5 align-middle">
                        <div class="flex items-center gap-2">
                            <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100/30 group-hover:bg-white group-hover:shadow-sm transition-all shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700 group-hover:text-indigo-600 transition-colors">
                                {{ $item->stokBarang->nama_barang ?? 'Material tidak diketahui' }}
                            </span>
                        </div>
                    </td>
                    
                    <td class="px-6 py-5 align-middle text-center">
                        <span class="text-sm font-bold text-slate-800 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                            {{ number_format($item->jumlah) }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-5 align-middle">
                        <span class="text-sm text-slate-600 font-medium">{{ $item->supplier ?? '-' }}</span>
                    </td>
                    
                    <td class="px-6 py-5 align-middle text-center">
                        <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold uppercase tracking-wider border border-emerald-200/40">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                            Ditambahkan
                        </span>
                    </td>
                    
                    <td class="px-6 py-5 align-middle text-center">
                        <div class="flex items-center justify-center">
                            <form action="{{ route('barang-masuk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat masuk ini? Stok barang akan otomatis dikurangi kembali.')" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl border border-transparent hover:border-rose-100 transition-all duration-200 flex items-center justify-center group/btn" title="Hapus Log">
                                    <svg class="w-4 h-4 transform group-hover/btn:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5M14 10h1"/>
                            </svg>
                            <p class="text-sm font-medium text-slate-500">Belum ada data barang masuk.</p>
                            <p class="text-xs text-slate-400">Silakan tambahkan data melalui tombol "Input Barang Masuk" di atas.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    </div>

    <div id="modalInput" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden transition-all" onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-xl font-bold text-slate-800">Input Barang Masuk</h2>
                <button onclick="document.getElementById('modalInput').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form action="{{ route('barang-masuk.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Material</label>
                        <select name="stok_barang_id" required class="w-full border-slate-200 rounded-xl focus:ring-indigo-500">
                            <option value="">Pilih Material</option>
                            @foreach($stokBarang as $sb)
                                <option value="{{ $sb->id }}">{{ $sb->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah" required min="1" class="w-full border-slate-200 rounded-xl focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-2">
                            Tanggal Pemesanan
                        </label>

                        <input type="date"
                            name="tanggal_pesan"
                            value="{{ old('tanggal_pesan') }}"
                            required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" required value="{{ date('Y-m-d') }}" class="w-full border-slate-200 rounded-xl focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Supplier</label>
                        <input type="text" name="supplier" class="w-full border-slate-200 rounded-xl focus:ring-indigo-500" placeholder="Nama supplier (opsional)">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalInput').classList.add('hidden')" class="px-4 py-2 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>