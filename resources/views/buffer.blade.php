<x-sidebar>
    <div class="space-y-8">
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Stok Material</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola seluruh ketersediaan material bangunan di gudang utama.</p>
            </div>



            <form action="{{ url('/buffer') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama barang, SKU atau kategori..." 
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm"
                    >

                </div>
                
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all flex items-center shrink-0">
                    Cari
                </button>
                
                @if(request('search'))
                <a href="{{ url('/buffer') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-2xl font-bold text-sm transition-all flex items-center shrink-0">
                    Reset
                </a>
                @endif
            </form>




        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-1 rounded-3xl shadow-lg shadow-indigo-100">
                <div class="bg-white/10 backdrop-blur-md p-5 rounded-[1.4rem] text-white">
                    <p class="text-xs font-bold opacity-80 uppercase tracking-widest">Total Produk</p>
                    <h3 class="text-3xl font-black mt-1">{{ number_format($totalProduk) }}</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($kategoriAktif) }} <span class="text-xs font-medium text-slate-400">Jenis</span></h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Nilai Aset</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm border-l-4 {{ $persentaseStokAman >= 80 ? 'border-l-emerald-500' : ($persentaseStokAman >= 50 ? 'border-l-yellow-500' : 'border-l-red-500') }}">
                <p class="text-xs font-bold {{ $persentaseStokAman >= 80 ? 'text-emerald-600' : ($persentaseStokAman >= 50 ? 'text-yellow-600' : 'text-red-600') }} uppercase tracking-widest">
                    Stok Aman
                </p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $persentaseStokAman }}%</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $stokAmanCount }} dari {{ $totalProduk }} produk</p>
            </div>
        </div>

        <!-- BUFFER STOK WARNING SECTION -->
        @if($bufferStok->count() > 0)
        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <h4 class="font-bold text-amber-800">Peringatan Buffer Stok!</h4>
                    <p class="text-sm text-amber-700">Terdapat {{ $bufferStok->count() }} material yang stoknya mendekati atau sudah di bawah batas minimum. Segera lakukan pengadaan!</p>
                </div>
            </div>
        </div>
        @endif

        <!-- HASIL PENCARIAN -->
@if($search)
<div class="flex items-center justify-between bg-indigo-50 p-3 rounded-xl">
    <div>
        <span class="text-sm text-indigo-700">
            🔍 Hasil pencarian untuk: <strong>"{{ $search }}"</strong>
        </span>
        <span class="text-xs text-indigo-500 ml-2">
            (Ditemukan {{ $stokBarang->count() }} data)
        </span>
    </div>
    <a href="{{ url('/buffer') }}" class="text-xs text-indigo-600 hover:text-indigo-800">
        ✕ Hapus filter
    </a>
</div>
@endif
<div class="flex flex-wrap items-center gap-2 mb-4">

    <span class="text-xs font-bold text-slate-500 mr-2">
        Terapkan ke semua:
    </span>

    <button type="button"
        onclick="applyMethodAll('sederhana')"
        class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl text-xs font-bold hover:bg-blue-100">

        ✓ Rekomendasi Buffer

    </button>


    <button type="button"
        onclick="applyMethodAll('statistik')"
        class="px-4 py-2 bg-violet-50 text-violet-600 border border-violet-100 rounded-xl text-xs font-bold hover:bg-violet-100">

        ✓ Safety Stock

    </button>


    <button type="button"
        onclick="applyMethodAll('custom')"
        class="px-4 py-2 bg-slate-100 text-slate-600 border border-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200">

        ✓ Custom

    </button>

</div>
        <!-- TABLE LIST VIEW -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">SKU</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stok Saat Ini</th>
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rekomendasi Buffer</th>                    
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rekomendasi Safety</th>
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Custom</th>
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Patokan Aktif</th>
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Jual</th>
                    <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($stokBarang as $barang)
                @php
                    $isBuffer = $barang->stok <= $barang->stok_minimum;
                    $stokPersen = $barang->stok_minimum > 0 ? ($barang->stok / $barang->stok_minimum) * 100 : 0;
                    $statusClass = $isBuffer ? 'bg-amber-50' : '';
                @endphp
                <tr class="hover:bg-slate-50 transition-colors {{ $statusClass }}">
                    <!-- SKU -->
                    <td class="px-6 py-4">
                        <span class="text-xs font-mono text-slate-500">{{ $barang->kode_barang }}</span>
                    </td>
                    
                    <!-- Nama Barang -->
                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-800">{{ $barang->nama_barang }}</span>
                    </td>
                    
                    <!-- Kategori -->
                    <td class="px-6 py-4">
                        @php
                            $kategoriBadge = match($barang->kategori) {
                                'Semen' => 'bg-indigo-100 text-indigo-700',
                                'Besi Beton' => 'bg-orange-100 text-orange-700',
                                'Pasir' => 'bg-yellow-100 text-yellow-700',
                                'Bata' => 'bg-red-100 text-red-700',
                                'Cat' => 'bg-blue-100 text-blue-700',
                                'Kayu' => 'bg-emerald-100 text-emerald-700',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        @endphp
                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg {{ $kategoriBadge }} uppercase tracking-wider">
                            {{ $barang->kategori }}
                        </span>
                    </td>
                    
                    <!-- Stok Saat Ini -->
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold {{ $isBuffer ? 'text-amber-600' : 'text-slate-800' }}">
                            {{ number_format($barang->stok) }}
                        </span>
                        <span class="text-xs text-slate-400">unit</span>
                    </td>
                    
                    {{-- REKOMENDASI BUFFER --}}
                        <td class="px-6 py-4 text-center">

                            @if($barang->data_buffer_tersedia)

                                <label class="flex flex-col items-center gap-2 cursor-pointer">

                                    <span class="font-black text-blue-600">
                                        {{ $barang->rekomendasi_buffer }}
                                    </span>

                                    <input type="radio"
                                        name="metode-{{ $barang->id }}"
                                        value="sederhana"

                                        {{ $barang->metode_stok_minimum === 'sederhana'
                                            ? 'checked'
                                            : '' }}

                                        onchange="updateBufferMethod(
                                            {{ $barang->id }},
                                            'sederhana'
                                        )">

                                </label>

                            @else

                                <span class="text-xs text-slate-400">
                                    Data belum cukup
                                </span>

                            @endif

                        </td>


                        {{-- REKOMENDASI SAFETY --}}
                        <td class="px-6 py-4 text-center">

                            @if($barang->data_buffer_tersedia)

                                <label class="flex flex-col items-center gap-2 cursor-pointer">

                                    <span class="font-black text-violet-600">
                                        {{ $barang->rekomendasi_safety }}
                                    </span>

                                    <input type="radio"
                                        name="metode-{{ $barang->id }}"
                                        value="statistik"

                                        {{ $barang->metode_stok_minimum === 'statistik'
                                            ? 'checked'
                                            : '' }}

                                        onchange="updateBufferMethod(
                                            {{ $barang->id }},
                                            'statistik'
                                        )">

                                </label>

                            @else

                                <span class="text-xs text-slate-400">
                                    Data belum cukup
                                </span>

                            @endif

                        </td>


                        {{-- CUSTOM --}}
                        <td class="px-6 py-4">

                            <div class="flex items-center justify-center gap-2">

                                <input type="radio"
                                    name="metode-{{ $barang->id }}"

                                    {{ $barang->metode_stok_minimum === 'custom'
                                        ? 'checked'
                                        : '' }}

                                    onchange="updateBufferMethod(
                                        {{ $barang->id }},
                                        'custom'
                                    )">


                                <input type="number"
                                    id="custom-{{ $barang->id }}"
                                    value="{{ $barang->stok_minimum_custom }}"
                                    min="0"
                                    class="w-20 px-2 py-2 text-center border border-slate-200 rounded-xl">


                                <button type="button"
                                    onclick="updateBufferMethod(
                                        {{ $barang->id }},
                                        'custom'
                                    )"
                                    class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100">

                                    ✓

                                </button>

                            </div>

                        </td>


                        {{-- PATOKAN AKTIF --}}
                        <td class="px-6 py-4 text-center">

                            <div class="font-black text-slate-800">
                                {{ $barang->stok_minimum }}
                            </div>

                            <span class="text-[10px] text-slate-400">

                                @if($barang->metode_stok_minimum === 'sederhana')

                                    Buffer Otomatis

                                @elseif($barang->metode_stok_minimum === 'statistik')

                                    Safety Stock

                                @else

                                    Custom

                                @endif

                            </span>

                        </td>
                    
                    <!-- Status -->
                    <td class="px-6 py-4 text-center">
                        @if($isBuffer)
                            <div class="flex flex-col items-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Buffer Zone
                                </span>
                                <div class="w-20 h-1.5 bg-amber-100 rounded-full mt-1.5">
                                    <div class="h-1.5 bg-amber-500 rounded-full" style="width: {{ min(100, $stokPersen) }}%"></div>
                                </div>
                            </div>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Stok Aman
                            </span>
                        @endif
                    </td>
                    
                    <!-- Harga Jual -->
                    <td class="px-6 py-4 text-right">
                        <span class="font-bold text-indigo-600">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span>
                    </td>
                    
                    <!-- Aksi -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="if(confirm('Yakin hapus {{ $barang->nama_barang }}?')) document.getElementById('delete-form-{{ $barang->id }}').submit()" 
                                    class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-red-100 hover:text-red-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            <form id="delete-form-{{ $barang->id }}" action="{{ route('buffer.destroy', $barang->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-12 text-center">
                        <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <h3 class="text-lg font-bold text-slate-600">Belum ada material</h3>
                        <p class="text-slate-400 text-sm mt-1">Silakan tambah material baru dengan klik tombol di atas</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript untuk Update Stok Minimum -->
<script>
function updateBufferMethod(id, metode)
{
    let customValue = null;

    const customInput = document.getElementById('custom-' + id);

    if (customInput) {
        customValue = customInput.value;
    }

    fetch('{{ route("buffer.update.minimum") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            id: id,
            metode: metode,
            stok_minimum_custom: customValue
        })
    })

    .then(async response => {

        const data = await response.json();

        if (!response.ok) {
            throw new Error(
                data.message ?? 'Gagal memperbarui buffer stock.'
            );
        }

        return data;
    })

    .then(data => {

        showToast(data.message, 'success');

        setTimeout(() => {
            location.reload();
        }, 700);
    })

    .catch(error => {
        showToast(error.message, 'error');
    });
}


function applyMethodAll(metode)
{
    if (!confirm('Terapkan metode ini ke seluruh barang?')) {
        return;
    }

    fetch('{{ route("buffer.apply.all") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            metode: metode
        })
    })

    .then(async response => {

        const data = await response.json();

        if (!response.ok) {
            throw new Error(
                data.message ?? 'Gagal menerapkan metode.'
            );
        }

        return data;
    })

    .then(data => {

        showToast(data.message, 'success');

        setTimeout(() => {
            location.reload();
        }, 1000);
    })

    .catch(error => {
        showToast(error.message, 'error');
    });
}

// Fungsi untuk menampilkan toast notification
function showToast(message, type = 'success') {
    // Cek apakah toast container sudah ada
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed bottom-4 right-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    // Buat toast element
    const toast = document.createElement('div');
    toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'
    }`;
    toast.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/>
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;
    
    toastContainer.appendChild(toast);
    
    // Hapus toast setelah 3 detik
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<!-- Tambahkan CSS untuk animasi loading -->
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
    </div>
</x-sidebar>