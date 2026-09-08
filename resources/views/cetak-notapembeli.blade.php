<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - INV-{{ str_pad($nota->id, 6, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        /* Gaya Watermark Cetak Teks Berulang di Latar Belakang Kertas */
        .nota-container {
            position: relative;
            background-color: white;
            /* Watermark SVG "Mustika Jaya 3" berulang secara diagonal */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='150' viewBox='0 0 300 150'%3E%3Ctext x='30' y='80' fill='%23000' font-family='sans-serif' font-weight='bold' font-size='15' opacity='0.04' transform='rotate(-25 150 75)'%3EMUSTIKA JAYA 3%3C/text%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        /* Aturan khusus untuk mode cetak fisik / simpan PDF */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
                color: black;
                padding: 0;
            }
            .print-card {
                border: 2px dashed #000 !important; /* Batas potong nota jilid standar */
                box-shadow: none !important;
                padding: 1.5rem !important;
                background-color: white !important;
                -webkit-print-color-adjust: exact; /* Memaksa background cetak keluar di Chrome/Edge */
                print-color-adjust: exact;
            }
            /* Menghilangkan margin bawaan browser pada kertas cetak */
            @page {
                size: A4 10mm; 
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans p-6">

    {{-- Tombol Aksi Kontrol (Disembunyikan Saat Cetak) --}}
    <div class="max-w-3xl mx-auto mb-4 flex justify-between items-center no-print">
        <a href="javascript:window.close();" class="text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-3 py-2 rounded-xl hover:bg-slate-50 transition-all">
            &larr; Tutup Tab
        </a>
        <button onclick="window.print()" class="text-xs font-bold text-white bg-slate-900 px-4 py-2 rounded-xl hover:bg-slate-800 shadow-sm transition-all cursor-pointer">
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- Kertas Lembar Nota Utama --}}
    <div class="max-w-3xl mx-auto border-2 border-dashed border-slate-300 p-6 rounded-none print-card nota-container">
        
        {{-- KOP NOTA TOKO --}}
        <div class="flex justify-between items-start border-b-2 border-black pb-4 mb-4">
            <div>
                <h1 class="text-2xl font-black text-black tracking-tight uppercase">Mustika Jaya 3</h1>
                <p class="text-xs font-bold text-gray-700 tracking-wider">SUPPLIER MATERIAL & TOKO BANGUNAN</p>
                <p class="text-[10px] text-gray-500 leading-tight mt-1 max-w-md">
                    Jl. Raya Lintas Pantai Timur Sumatera No.16, Taman Negeri, <br>
                    Kec. Way Bungur, Kab. Lampung Timur, Lampung 34373
                </p>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-black uppercase text-black tracking-widest border border-black px-2 py-0.5 inline-block">NOTA</h2>
                <p class="text-xs font-mono font-bold text-black mt-1">No: INV-{{ str_pad($nota->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        {{-- Data Informasi Pelanggan & Tanggal --}}
        <div class="grid grid-cols-2 gap-4 mb-4 text-xs">
            <div>
                <table class="w-full text-left">
                    <tr>
                        <td class="w-20 font-semibold text-gray-600 align-top">Kepada Yth.</td>
                        <td class="w-2 text-gray-600 align-top">:</td>
                        <td class="font-bold text-black align-top">
                            {{ $nota->nama_pembeli }}
                            <p class="font-normal text-gray-600 text-[11px] leading-tight mt-0.5">{{ $nota->tujuan ?? '-' }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="w-full text-left">
                    <tr>
                        <td class="w-24 font-semibold text-gray-600 text-right pr-2">Tanggal Keluar</td>
                        <td class="w-2 text-gray-600">:</td>
                        <td class="text-black font-medium">
                            {{ \Carbon\Carbon::parse($nota->tanggal_keluar ?? $nota->created_at)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tabel Item Belanjaan (Model Kolom Nota Fisik) --}}
        <div class="mb-4">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-t-2 border-b-2 border-black font-bold uppercase text-black">
                        <th class="px-2 py-1.5 w-12 text-center">Banyaknya</th>
                        <th class="px-2 py-1.5">Nama Barang / Material</th>
                        <th class="px-2 py-1.5 text-right w-36">Harga Satuan</th>
                        <th class="px-2 py-1.5 text-right w-40">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 text-black">
                    @if($nota->barangKeluar && $nota->barangKeluar->count() > 0)
                        @foreach($nota->barangKeluar as $bk)
                            <tr>
                                <td class="px-2 py-2 text-center font-bold">
                                    {{ $bk->jumlah }}
                                </td>
                                <td class="px-2 py-2 font-medium">
                                    {{ $bk->stokBarang->nama_barang ?? 'Barang Terhapus' }}
                                    @if(isset($bk->stokBarang->satuan))
                                        <span class="text-[10px] text-gray-500">({{ $bk->stokBarang->satuan }})</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2 text-right font-mono">
                                    {{ number_format($bk->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-2 py-2 text-right font-bold font-mono">
                                    {{ number_format($bk->jumlah * $bk->harga_satuan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                        
                        {{-- Mengisi baris kosong agar estetika nota manual terjaga --}}
                        @for ($i = count($nota->barangKeluar); $i < 3; $i++)
                            <tr class="opacity-0">
                                <td class="px-2 py-1.5">&nbsp;</td>
                                <td class="px-2 py-1.5">&nbsp;</td>
                                <td class="px-2 py-1.5">&nbsp;</td>
                                <td class="px-2 py-1.5">&nbsp;</td>
                            </tr>
                        @endfor
                    @else
                        <tr>
                            <td colspan="4" class="px-2 py-6 text-center text-gray-400 italic">
                                Tidak ada rincian data material yang tercatat dalam nota ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Akumulasi Total Biaya --}}
        <div class="flex justify-between items-start border-t-2 border-black pt-3">
            <div class="text-[11px] text-gray-500 italic max-w-sm leading-tight">
                * Barang-barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian sebelumnya.
            </div>
            <div class="w-64 text-xs font-bold">
                <div class="flex justify-between border-b border-black pb-1 mb-1">
                    <span class="text-black uppercase">Jumlah Total</span>
                    <span class="text-black font-mono">Rp {{ number_format($nota->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Area Kolom Tanda Tangan Nota Manual --}}
        <div class="grid grid-cols-3 gap-2 mt-8 text-center text-[11px] text-black">
            <div>
                <p class="mb-12">Tanda Terima Pelanggan,</p>
                <p class="font-bold border-b border-black w-32 mx-auto pb-0.5">( ............................ )</p>
            </div>
            <div>
                {{-- Sengaja dikosongkan tengahnya agar rapi --}}
            </div>
            <div>
                <p class="mb-12">Hormat Kami (Toko),</p>
                <p class="font-bold border-b border-black w-32 mx-auto pb-0.5">M u s t i k a &nbsp; J a y a &nbsp; 3</p>
            </div>
        </div>

    </div>

    {{-- Auto Print Trigger --}}
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>