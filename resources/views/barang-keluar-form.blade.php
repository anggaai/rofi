<x-sidebar>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
            <h2 class="text-2xl font-bold mb-6">Catat Pengeluaran Material</h2>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang-keluar.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Nama Pembeli</label>
                    <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}" class="w-full border rounded-xl p-2" required>
                    @error('nama_pembeli') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Material</label>
                    <select name="stok_barang_id" class="w-full border rounded-xl p-2" required>
                        <option value="">Pilih Material</option>
                        @foreach($stokBarang as $sb)
                            <option value="{{ $sb->id }}" {{ old('stok_barang_id') == $sb->id ? 'selected' : '' }}>
                                {{ $sb->nama_barang }} (Stok: {{ $sb->stok }} {{ $sb->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('stok_barang_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" class="w-full border rounded-xl p-2" required min="1">
                    @error('jumlah') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Tanggal Keluar</label>
                    <input type="datetime-local" name="tanggal_keluar" value="{{ old('tanggal_keluar') }}" class="w-full border rounded-xl p-2" required>
                    @error('tanggal_keluar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Tujuan / Pelanggan</label>
                    <input type="text" name="tujuan" value="{{ old('tujuan') }}" class="w-full border rounded-xl p-2" required>
                    @error('tujuan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('barang-keluar') }}" class="px-4 py-2 bg-slate-200 rounded-xl">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar>