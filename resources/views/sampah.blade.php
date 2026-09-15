<x-sidebar>
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">🗑️ Tempat Sampah</h1>
                <p class="text-slate-500 text-sm mt-1">Data yang telah dihapus sementara dari sistem.</p>
            </div>
            <a href="{{ route('stok') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-xl shadow-indigo-200 transition-all">
                Kembali ke Katalog
            </a>
        </div>

        @if($countTrashed > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl">
            <p class="text-sm text-blue-700">Terdapat {{ $countTrashed }} riwayat/material di tempat sampah. Data dapat dipulihkan atau dihapus permanen.</p>
        </div>
        @endif

        <div class="space-y-3">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">📦 Master Stok Barang ({{ $trashedBarang->count() }})</h2>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">SKU</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">Nama Barang</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">Kategori</th>
                                <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">Tanggal Dihapus</th>
                                <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trashedBarang as $barang)
                            <tr class="hover:bg-slate-50 border-b border-slate-100">
                                <td class="px-6 py-4">{{ $barang->kode_barang }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $barang->nama_barang }}</td>
                                <td class="px-6 py-4">{{ $barang->kategori }}</td>
                                <td class="px-6 py-4 text-center">{{ $barang->deleted_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('buffer-stok.restore', $barang->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600">Pulihkan</button>
                                        </form>
                                        <form action="{{ route('buffer-stok.force-delete', $barang->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen? Data tidak bisa dikembalikan!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-bold hover:bg-red-600">Hapus Permanen</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada data master barang di tempat sampah</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-3 mt-8">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">📥 Riwayat Barang Masuk ({{ $trashedBarangMasuk->count() }})</h2>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">Nama Barang</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">Supplier</th>
                                <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">Jumlah Masuk</th>
                                <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">Tanggal Dihapus</th>
                                <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trashedBarangMasuk as $log)
                            <tr class="hover:bg-slate-50 border-b border-slate-100">
                                <td class="px-6 py-4 font-semibold">
                                    {{ $log->stokBarang ? $log->stokBarang->nama_barang : 'Barang Telah Dihapus Permanen' }}
                                </td>
                                <td class="px-6 py-4">{{ $log->supplier ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-bold text-indigo-600">{{ $log->jumlah }} unit</td>
                                <td class="px-6 py-4 text-center">{{ $log->deleted_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('barang-masuk.restore', $log->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600">Pulihkan</button>
                                        </form>
                                        <form action="{{ route('barang-masuk.force-delete', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen log masuk? Data tidak bisa dikembalikan!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-bold hover:bg-red-600">Hapus Permanen</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada riwayat barang masuk di tempat sampah</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-3 mt-8">
    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
        🧾 Nota Pembeli ({{ $trashedNotaPembeli->count() }})
    </h2>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">
                            No. Nota
                        </th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">
                            Nama Pembeli
                        </th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">
                            Tujuan
                        </th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Total Harga
                        </th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Tanggal Dihapus
                        </th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($trashedNotaPembeli as $nota)
                        <tr class="hover:bg-slate-50 border-b border-slate-100">

                            <td class="px-6 py-4 font-semibold text-indigo-600">
                                M3J#-{{ str_pad($nota->id, 6, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-6 py-4 font-semibold">
                                {{ $nota->nama_pembeli }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $nota->tujuan ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center font-bold">
                                Rp {{ number_format($nota->total_harga, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $nota->deleted_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    <form action="{{ route('nota-pembeli.restore', $nota->id) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf

                                        <button type="submit"
                                            class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600">
                                            Pulihkan
                                        </button>
                                    </form>

                                    <form action="{{ route('nota-pembeli.force-delete', $nota->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Hapus nota secara permanen? Data tidak bisa dikembalikan!')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-bold hover:bg-red-600">
                                            Hapus Permanen
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="px-6 py-8 text-center text-slate-400">
                                Tidak ada nota pembeli di tempat sampah
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="space-y-3 mt-8">

    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
        📋 Riwayat Stok Opname ({{ $trashedOpnames->count() }})
    </h2>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="bg-slate-50 border-b border-slate-200">

                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">
                            Nama Barang
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">
                            Jenis
                        </th>

                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Perubahan
                        </th>

                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Stok Sebelum
                        </th>

                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Stok Sesudah
                        </th>

                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500">
                            Keterangan
                        </th>

                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Tanggal Dihapus
                        </th>

                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($trashedOpnames as $opname)

                        <tr class="hover:bg-slate-50 border-b border-slate-100">

                            <td class="px-6 py-4 font-semibold">

                                {{ $opname->stokBarang
                                    ? $opname->stokBarang->nama_barang
                                    : 'Barang Tidak Ditemukan' }}

                            </td>


                            <td class="px-6 py-4">

                                @if($opname->jenis_opname === 'penambahan')

                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold">
                                        Penambahan
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold">
                                        Pengurangan
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-center font-bold">

                                @if($opname->jenis_opname === 'penambahan')

                                    <span class="text-emerald-600">
                                        +{{ $opname->jumlah_perubahan }}
                                    </span>

                                @else

                                    <span class="text-rose-600">
                                        -{{ $opname->jumlah_perubahan }}
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-center">
                                {{ $opname->stok_sebelum }}
                            </td>


                            <td class="px-6 py-4 text-center">
                                {{ $opname->stok_sesudah }}
                            </td>


                            <td class="px-6 py-4">
                                {{ $opname->keterangan_opname ?? '-' }}
                            </td>


                            <td class="px-6 py-4 text-center">

                                {{ $opname->deleted_at->format('d/m/Y H:i') }}

                            </td>


                            <td class="px-6 py-4 text-center">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- PULIHKAN --}}
                                    <form action="{{ route('opname.restore', $opname->id) }}"
                                        method="POST"
                                        class="inline">

                                        @csrf

                                        <button type="submit"
                                            class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600">

                                            Pulihkan

                                        </button>

                                    </form>


                                    {{-- HAPUS PERMANEN --}}
                                    <form action="{{ route('opname.force-delete', $opname->id) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Hapus riwayat stok opname secara permanen? Data tidak dapat dikembalikan.')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-bold hover:bg-red-600">

                                            Hapus Permanen

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="px-6 py-8 text-center text-slate-400">

                                Tidak ada riwayat stok opname di tempat sampah

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
    </div>
</x-sidebar>