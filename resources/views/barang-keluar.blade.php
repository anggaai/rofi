<x-sidebar>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Riwayat Barang Keluar</h1>
                <p class="text-slate-500 text-sm mt-1">Pantau distribusi material ke pelanggan atau proyek konstruksi.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('barang-keluar.create') }}" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-slate-200 transition-all flex items-center group">
                    <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    Catat Pengeluaran
                </a>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Keluar Hari Ini</p>
                        <h3 class="text-xl font-black text-slate-800">{{ $keluarHariIni }} Transaksi</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Daftar --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="font-bold text-slate-800">Daftar Pengeluaran Material</h3>
                <form
    action="{{ route('barang-keluar') }}"
    method="GET"
    class="flex items-center gap-3"
>

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari nota, pembeli, barang, kode..."
        class="w-72 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
    >


    <button
        type="submit"
        class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800"
    >
        Cari
    </button>


    @if(request('search'))

        <a
            href="{{ route('barang-keluar') }}"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
        >
            Reset
        </a>

    @endif

</form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 font-bold">No. Nota & Tanggal</th>
                            <th class="px-8 py-5 font-bold">Material</th>
                            <th class="px-8 py-5 font-bold text-center">Jumlah</th>
                            <th class="px-8 py-5 font-bold">Tujuan / Pelanggan</th>
                            <th class="px-8 py-5 font-bold text-right">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($barangKeluar as $item)
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-bold">Selesai</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $item->notaPembeli && $item->notaPembeli->tanggal_keluar ? \Carbon\Carbon::parse($item->notaPembeli->tanggal_keluar)->format('d M Y, H:i') : '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 text-slate-500 rounded-lg flex items-center justify-center group-hover:bg-rose-100 group-hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-boxes text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-slate-700">{{ $item->stokBarang->nama_barang ?? 'Material tidak ditemukan' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 bg-slate-900 text-white rounded-lg font-black text-xs">{{ $item->jumlah }} <small class="text-[9px] opacity-70">{{ $item->stokBarang->satuan ?? '' }}</small></span>
                            </td>
                            <td class="px-8 py-6 text-slate-600 font-medium">
                                {{ $item->notaPembeli->tujuan ?? 'Tidak ada tujuan' }} 
                                <br>
                                <small class="text-slate-400">Pembeli: {{ $item->notaPembeli->nama_pembeli ?? '-' }}</small>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @php $petugas = $item->notaPembeli->user->name ?? 'System'; @endphp   {{-- ← DITAMBAH --}}
                                <div class="flex items-center justify-end gap-2">
                                    <span class="text-xs font-bold text-slate-700">
                                        {{ $petugas }}                                                {{-- ← DIUBAH --}}
                                    </span>
                                    <div class="w-7 h-7 bg-indigo-500 rounded-full flex items-center justify-center text-[10px] text-white font-bold">
                                        {{ strtoupper(substr($petugas, 0, 1)) }}                      {{-- ← DIUBAH --}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400">
                                Tidak ada data barang keluar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-6 border-t border-slate-100">
                {{ $barangKeluar->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-sidebar>