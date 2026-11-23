<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Invoice - {{ $nota->no_nota ?? 'INV-'.str_pad($nota->id, 6, '0', STR_PAD_LEFT) }}</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        /*
        |--------------------------------------------------------------------------
        | NOTA A5 LANDSCAPE
        | Canon iP2770 menggunakan area aman sekitar 4mm
        |--------------------------------------------------------------------------
        */

        .nota-container {
        position: relative;
        width: 202mm;
        height: 140mm;
        max-width: 202mm;
        background-color: white;
        overflow: hidden;
    }

    .ttd-fixed {
        position: absolute;
        left: 5mm;
        right: 5mm;
        top: 105mm;
    }

    .baris-barang td {
        vertical-align: middle;
        line-height: 1.15;
    }

    .nama-barang {
        line-height: 1.15;
        word-break: break-word;
    }

        .nota-page {
            width: 210mm;
            height: 148mm;

            margin: 10mm auto;

            display: flex;
            justify-content: center;
            align-items: center;

            background: white;
        }

        /*
        |--------------------------------------------------------------------------
        | MODE PRINT CANON iP2770
        |--------------------------------------------------------------------------
        */

        @media print {

            .no-print {
                display: none !important;
            }

            html,
            body {
                width: 210mm !important;
                height: 148mm !important;

                margin: 0 !important;
                padding: 0 !important;

                background: white !important;
            }

            .nota-page {
                width: 210mm !important;
                height: 148mm !important;

                margin: 0 !important;
                padding: 0 !important;

                page-break-after: always;
                break-after: page;
            }

            .nota-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            .print-card {
                width: 210mm !important;
                height: 148mm !important;
                max-width: 210mm !important;

                margin: 0 !important;
                padding: 5mm !important;

                border: 2px dashed #000 !important;

                box-shadow: none !important;
                background: white !important;

                overflow: hidden;

                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: none;
                margin: 0;
            }
        }
    </style>
</head>

<body class="bg-slate-100 font-sans p-6">

    {{-- Tombol Aksi Kontrol --}}
    <div
        class="mx-auto mb-4 flex justify-between items-center no-print"
        style="width: 202mm;"
    >

        <a
            href="javascript:window.close();"
            class="text-xs font-semibold text-slate-600 bg-white border border-slate-200 px-3 py-2 rounded-xl hover:bg-slate-50 transition-all"
        >
            &larr; Tutup Tab
        </a>

        <button
            onclick="window.print()"
            class="text-xs font-bold text-white bg-slate-900 px-4 py-2 rounded-xl hover:bg-slate-800 shadow-sm transition-all cursor-pointer"
        >
            Cetak / Simpan PDF
        </button>

    </div>


    {{-- Membagi maksimal 10 jenis barang per nota --}}
    @php

        $semuaBarang = $nota->barangKeluar ?? collect();

        $halamanBarang = $semuaBarang->count() > 0
            ? $semuaBarang->chunk(10)
            : collect([collect()]);

    @endphp


    @foreach($halamanBarang as $indexHalaman => $barangHalaman)

        @php

            $jumlahBarang = $barangHalaman->count();

            /*
            |--------------------------------------------------------------------------
            | BARIS BARANG DIPERBESAR SEDIKIT
            |--------------------------------------------------------------------------
            */

           if ($jumlahBarang <= 3) {
                $tinggiBaris = 11;
                $fontBarang = 13;
            } elseif ($jumlahBarang <= 5) {
                $tinggiBaris = 9;
                $fontBarang = 12.5;
            } elseif ($jumlahBarang <= 7) {
                $tinggiBaris = 7.5;
                $fontBarang = 12;
            } elseif ($jumlahBarang <= 9) {
                $tinggiBaris = 6.5;
                $fontBarang = 11.5;
            } else {
                $tinggiBaris = 5.8;
                $fontBarang = 11;
            }

        @endphp


        <div class="nota-page">

            <div
                class="border-2 border-dashed border-slate-300 rounded-none print-card nota-container"
            >

                {{-- KOP NOTA TOKO --}}
                <div class="flex justify-between items-start border-b-2 border-black pb-2 mb-2">

                    <div>

                        <h1 class="text-xl font-black text-black tracking-tight uppercase">
                            Mustika Jaya 3
                        </h1>

                        <p class="text-[10px] font-bold text-gray-700 tracking-wider">
                            SUPPLIER MATERIAL & TOKO BANGUNAN
                        </p>

                        <p class="text-[9px] text-gray-500 leading-tight mt-1 max-w-md">

                            Jl. Raya Lintas Pantai Timur Sumatera No.16, Taman Negeri,
                            <br>

                            Kec. Way Bungur, Kab. Lampung Timur, Lampung 34373

                        </p>

                    </div>


                    <div class="text-right">

                        <h2
                            class="text-base font-black uppercase text-black tracking-widest border border-black px-2 py-0.5 inline-block"
                        >
                            NOTA
                        </h2>

                        <p class="text-[10px] font-mono font-bold text-black mt-1">

                            No:
                            {{ $nota->no_nota ?? 'INV-'.str_pad($nota->id, 6, '0', STR_PAD_LEFT) }}

                        </p>

                    </div>

                </div>


                {{-- Informasi pelanggan --}}
                <div class="grid grid-cols-2 gap-3 mb-2 text-[10px]">

                    <div>

                        <table class="w-full text-left">

                            <tr>

                                <td class="w-16 font-semibold text-gray-600 align-top">
                                    Kepada Yth.
                                </td>

                                <td class="w-2 text-gray-600 align-top">
                                    :
                                </td>

                                <td class="font-bold text-black align-top">

                                    {{ $nota->nama_pembeli }}

                                    <p class="font-normal text-gray-600 text-[9px] leading-tight mt-0.5">

                                        {{ $nota->tujuan ?? '-' }}

                                    </p>

                                </td>

                            </tr>

                        </table>

                    </div>


                    <div>

                        <table class="w-full text-left">

                            <tr>

                                <td class="w-20 font-semibold text-gray-600 text-right pr-2">
                                    Tanggal Keluar
                                </td>

                                <td class="w-2 text-gray-600">
                                    :
                                </td>

                                <td class="text-black font-medium">

                                    {{
                                        \Carbon\Carbon::parse(
                                            $nota->tanggal_keluar
                                            ?? $nota->created_at
                                        )->format('d/m/Y H:i')
                                    }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>


                {{-- Tabel Barang --}}
                <div class="mb-2">

                    <table
                        class="w-full text-left text-xs border-collapse"
                        style="table-layout: fixed;"
                    >

                        <thead>

                            <tr class="border-t-2 border-b-2 border-black font-bold uppercase text-black">

                                <th class="px-1 py-1.5 w-16 text-center text-[9px]">
                                    Banyaknya
                                </th>

                                <th class="px-2 py-1.5 text-[9px]">
                                    Nama Barang / Material
                                </th>

                                <th class="px-2 py-1.5 text-right w-32 text-[9px]">
                                    Harga Satuan
                                </th>

                                <th class="px-2 py-1.5 text-right w-36 text-[9px]">
                                    Jumlah (Rp)
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-300 text-black">

                            @if($barangHalaman->count() > 0)

                                @foreach($barangHalaman as $bk)

                                    <tr
                                        class="baris-barang"
                                        style="
                                            height: {{ $tinggiBaris }}mm;
                                            font-size: {{ $fontBarang }}px;
                                        "
                                    >

                                        <td class="px-2 text-center font-bold">

                                            {{ $bk->jumlah }}

                                        </td>


                                        <td class="px-2 font-medium nama-barang">

                                            {{
                                                $bk->stokBarang->nama_barang
                                                ?? 'Barang Terhapus'
                                            }}

                                            @if(isset($bk->stokBarang->satuan))

                                                <span class="text-[9px] text-gray-500">

                                                    ({{ $bk->stokBarang->satuan }})

                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-2 text-right font-mono">

                                            {{
                                                number_format(
                                                    $bk->harga_satuan,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}

                                        </td>


                                        <td class="px-2 text-right font-bold font-mono">

                                            {{
                                                number_format(
                                                    $bk->jumlah
                                                    * $bk->harga_satuan,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}

                                        </td>

                                    </tr>

                                @endforeach


                                {{-- Baris kosong agar tampilan nota tetap rapi --}}
                                @for ($i = count($barangHalaman); $i < 3; $i++)

                                    <tr class="opacity-0">

                                        <td class="px-2 py-1">
                                            &nbsp;
                                        </td>

                                        <td class="px-2 py-1">
                                            &nbsp;
                                        </td>

                                        <td class="px-2 py-1">
                                            &nbsp;
                                        </td>

                                        <td class="px-2 py-1">
                                            &nbsp;
                                        </td>

                                    </tr>

                                @endfor

                            @else

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-2 py-5 text-center text-gray-400 italic"
                                    >

                                        Tidak ada rincian data material yang tercatat dalam nota ini.

                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>


                {{-- Total --}}
                <div class="flex justify-between items-start border-t-2 border-black pt-2">

                    <div class="text-[8px] text-gray-500 italic max-w-sm leading-tight">

                        * Barang-barang yang sudah dibeli tidak dapat ditukar atau
                        dikembalikan kecuali ada perjanjian sebelumnya.

                    </div>


                    <div class="w-56 text-[10px] font-bold">

                        <div class="flex justify-between border-b border-black pb-1 mb-1">

                            <span class="text-black uppercase">
                                Jumlah Total
                            </span>

                            <span class="text-black font-mono">

                                Rp
                                {{
                                    number_format(
                                        $nota->total_harga,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Tanda tangan diperbesar dan dilebarkan sedikit --}}
                <div
                        class="ttd-fixed grid gap-4 text-center text-[10px] text-black"
                        style="grid-template-columns: 1fr 0.45fr 1fr;"
                    >

                    <div>

                        <p class="mb-14 mt-14" >
                            Tanda Terima Pelanggan,
                        </p>

                        <p class="font-bold border-b border-black w-52 mx-auto">

                            ( ............................ )

                        </p>

                    </div>


                    <div>
                        {{-- Sengaja dikosongkan --}}
                    </div>


                    <div>

                        <p class="mb-14 mt-14">
                            Hormat Kami (Toko),
                        </p>

                        <p class="font-bold border-b border-black w-56 mx-auto pb-1">

                            M u s t i k a &nbsp; J a y a &nbsp; 3

                        </p>

                    </div>

                </div>

            </div>

        </div>

    @endforeach


    {{-- Auto Print --}}
    <script>

        window.addEventListener('DOMContentLoaded', () => {

            setTimeout(() => {

                window.print();

            }, 500);

        });

    </script>

</body>
</html>