<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InventorySeeder extends Seeder
{
    public function run(): void
    {

            /*
            |--------------------------------------------------------------------------
            | 1. BERSIHKAN DATA INVENTORI
            |--------------------------------------------------------------------------
            | Seeder ini dibuat untuk data simulasi / development.
            | Jangan dijalankan pada database produksi yang berisi data penting.
            */
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            if (Schema::hasTable('stok_opnames')) {
                DB::table('stok_opnames')->truncate();
            }

            DB::table('barang_keluar')->truncate();
            DB::table('nota_pembeli')->truncate();
            DB::table('detail_barang_masuk')->truncate();
            DB::table('barang_masuk')->truncate();
            DB::table('stok_barang')->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');


            /*
            |--------------------------------------------------------------------------
            | 2. MASTER BARANG
            |--------------------------------------------------------------------------
            | jumlah_terjual mengikuti tabel penjualan Agustus 2026.
            | harga_jual mengikuti tabel sumber.
            | harga_beli, stok akhir, stok minimum, kategori, dan supplier
            | merupakan data simulasi pendukung agar seluruh fitur sistem berjalan.
            */
            $barang = [
                [
                    'kode_barang' => 'BRG1MJ',
                    'nama_barang' => 'Semen 50 kg',
                    'kategori' => 'Semen',
                    'stok' => 250,
                    'stok_minimum' => 100,
                    'harga_beli' => 62000,
                    'harga_jual' => 69500,
                    'jumlah_terjual' => 1327,
                    'supplier' => 'PT Semen Nusantara',
                ],
                [
                    'kode_barang' => 'BRG2MJ',
                    'nama_barang' => 'Pasir',
                    'kategori' => 'Material',
                    'stok' => 20,
                    'stok_minimum' => 10,
                    'harga_beli' => 290000,
                    'harga_jual' => 329000,
                    'jumlah_terjual' => 63,
                    'supplier' => 'CV Sumber Material',
                ],
                [
                    'kode_barang' => 'BRG3MJ',
                    'nama_barang' => 'Batu Bata Merah',
                    'kategori' => 'Material',
                    'stok' => 1200,
                    'stok_minimum' => 500,
                    'harga_beli' => 750,
                    'harga_jual' => 950,
                    'jumlah_terjual' => 7820,
                    'supplier' => 'CV Bata Makmur',
                ],
                [
                    'kode_barang' => 'BRG4MJ',
                    'nama_barang' => 'Besi 10 mm',
                    'kategori' => 'Besi',
                    'stok' => 70,
                    'stok_minimum' => 30,
                    'harga_beli' => 70000,
                    'harga_jual' => 78500,
                    'jumlah_terjual' => 252,
                    'supplier' => 'CV Baja Jaya',
                ],
                [
                    'kode_barang' => 'BRG5MJ',
                    'nama_barang' => 'Besi 8 mm',
                    'kategori' => 'Besi',
                    'stok' => 60,
                    'stok_minimum' => 30,
                    'harga_beli' => 57000,
                    'harga_jual' => 64000,
                    'jumlah_terjual' => 214,
                    'supplier' => 'CV Baja Jaya',
                ],
                [
                    'kode_barang' => 'BRG6MJ',
                    'nama_barang' => 'Cat Tembok 5 kg',
                    'kategori' => 'Cat',
                    'stok' => 40,
                    'stok_minimum' => 20,
                    'harga_beli' => 88000,
                    'harga_jual' => 99500,
                    'jumlah_terjual' => 117,
                    'supplier' => 'PT Warna Indah',
                ],
                [
                    'kode_barang' => 'BRG7MJ',
                    'nama_barang' => 'Keramik 40x40',
                    'kategori' => 'Keramik',
                    'stok' => 50,
                    'stok_minimum' => 25,
                    'harga_beli' => 66000,
                    'harga_jual' => 74500,
                    'jumlah_terjual' => 154,
                    'supplier' => 'CV Keramik Sejahtera',
                ],
                [
                    'kode_barang' => 'BRG8MJ',
                    'nama_barang' => 'Triplek 9 mm',
                    'kategori' => 'Kayu',
                    'stok' => 25,
                    'stok_minimum' => 10,
                    'harga_beli' => 115000,
                    'harga_jual' => 127500,
                    'jumlah_terjual' => 78,
                    'supplier' => 'CV Kayu Sejahtera',
                ],
                [
                    'kode_barang' => 'BRG9MJ',
                    'nama_barang' => 'Paralon 3 inch',
                    'kategori' => 'Plumbing',
                    'stok' => 30,
                    'stok_minimum' => 15,
                    'harga_beli' => 82000,
                    'harga_jual' => 92500,
                    'jumlah_terjual' => 68,
                    'supplier' => 'PT Pipa Sentosa',
                ],
                [
                    'kode_barang' => 'BRG10MJ',
                    'nama_barang' => 'Seng Gelombang',
                    'kategori' => 'Atap',
                    'stok' => 35,
                    'stok_minimum' => 15,
                    'harga_beli' => 77000,
                    'harga_jual' => 86500,
                    'jumlah_terjual' => 96,
                    'supplier' => 'CV Atap Jaya',
                ],
                [
                    'kode_barang' => 'BRG11MJ',
                    'nama_barang' => 'Paku',
                    'kategori' => 'Perkakas',
                    'stok' => 60,
                    'stok_minimum' => 25,
                    'harga_beli' => 19000,
                    'harga_jual' => 22500,
                    'jumlah_terjual' => 98,
                    'supplier' => 'CV Perkakas Jaya',
                ],
                [
                    'kode_barang' => 'BRG12MJ',
                    'nama_barang' => 'Kawat Bendrat',
                    'kategori' => 'Perkakas',
                    'stok' => 50,
                    'stok_minimum' => 20,
                    'harga_beli' => 20000,
                    'harga_jual' => 23500,
                    'jumlah_terjual' => 97,
                    'supplier' => 'CV Baja Jaya',
                ],
                [
                    'kode_barang' => 'BRG13MJ',
                    'nama_barang' => 'Closet Jongkok',
                    'kategori' => 'Sanitasi',
                    'stok' => 12,
                    'stok_minimum' => 8,
                    'harga_beli' => 168000,
                    'harga_jual' => 187500,
                    'jumlah_terjual' => 38,
                    'supplier' => 'CV Sanitasi Mandiri',
                ],
                [
                    'kode_barang' => 'BRG14MJ',
                    'nama_barang' => 'Kran Air',
                    'kategori' => 'Sanitasi',
                    'stok' => 25,
                    'stok_minimum' => 10,
                    'harga_beli' => 42000,
                    'harga_jual' => 48500,
                    'jumlah_terjual' => 77,
                    'supplier' => 'CV Sanitasi Mandiri',
                ],
                [
                    'kode_barang' => 'BRG15MJ',
                    'nama_barang' => 'Lem PVC',
                    'kategori' => 'Plumbing',
                    'stok' => 20,
                    'stok_minimum' => 10,
                    'harga_beli' => 24000,
                    'harga_jual' => 28500,
                    'jumlah_terjual' => 76,
                    'supplier' => 'PT Pipa Sentosa',
                ],
                [
                    'kode_barang' => 'BRG16MJ',
                    'nama_barang' => 'Pintu Plywood',
                    'kategori' => 'Pintu',
                    'stok' => 10,
                    'stok_minimum' => 5,
                    'harga_beli' => 405000,
                    'harga_jual' => 452500,
                    'jumlah_terjual' => 48,
                    'supplier' => 'CV Kayu Sejahtera',
                ],
                [
                    'kode_barang' => 'BRG17MJ',
                    'nama_barang' => 'Plafon PVC',
                    'kategori' => 'Plafon',
                    'stok' => 30,
                    'stok_minimum' => 15,
                    'harga_beli' => 62000,
                    'harga_jual' => 70500,
                    'jumlah_terjual' => 101,
                    'supplier' => 'CV Interior Jaya',
                ],
            ];


            foreach ($barang as $item) {
                DB::table('stok_barang')->insert([
                    'kode_barang' => $item['kode_barang'],
                    'nama_barang' => $item['nama_barang'],
                    'kategori' => $item['kategori'],
                    'stok' => $item['stok'],
                    'stok_minimum' => $item['stok_minimum'],
                    'metode_stok_minimum' => 'custom',
                    'stok_minimum_custom' => $item['stok_minimum'],
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'gambar' => null,
                    'created_at' => '2026-07-01 08:00:00',
                    'updated_at' => '2026-08-31 17:00:00',
                ]);
            }


            $barangId = DB::table('stok_barang')
                ->pluck('id', 'kode_barang');


            /*
            |--------------------------------------------------------------------------
            | 3. DATA BARANG MASUK
            |--------------------------------------------------------------------------
            | Jumlah barang masuk dibuat sama dengan:
            | jumlah terjual Agustus + stok akhir.
            |
            | Dengan begitu data stok lebih mudah dijelaskan:
            | Barang masuk - barang terjual = stok akhir.
            |
            | tanggal_pesan dan tanggal_masuk dipakai untuk lead time.
            */
            $leadTimes = [2, 3, 2, 2, 3, 2, 2, 3, 2, 2, 1, 2, 3, 2, 2, 3, 2];

            foreach ($barang as $index => $item) {

                $tanggalMasuk = Carbon::create(2026, 7, 20)
                    ->addDays($index % 9);

                $tanggalPesan = $tanggalMasuk
                    ->copy()
                    ->subDays($leadTimes[$index]);

                $barangMasukId = DB::table('barang_masuk')->insertGetId([
                    'tanggal_pesan' => $tanggalPesan->format('Y-m-d'),
                    'tanggal_masuk' => $tanggalMasuk->format('Y-m-d'),
                    'supplier' => $item['supplier'],
                    'created_at' => $tanggalMasuk->copy()->setTime(8, 0),
                    'updated_at' => $tanggalMasuk->copy()->setTime(8, 0),
                    'deleted_at' => null,
                ]);

                DB::table('detail_barang_masuk')->insert([
                    'barang_masuk_id' => $barangMasukId,
                    'stok_barang_id'  => $barangId[$item['kode_barang']],
                    'jumlah'          => $item['jumlah_terjual'] + $item['stok'],
                    'harga_beli'      => $item['harga_beli'],
                    'created_at' => $tanggalMasuk->copy()->setTime(8, 0),
                    'updated_at' => $tanggalMasuk->copy()->setTime(8, 0),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | 4. ALOKASI PENJUALAN PER PERIODE AGUSTUS
            |--------------------------------------------------------------------------
            | Matriks ini mempertahankan:
            |
            | 1-7 Agustus  = Rp57.865.000
            | 8-14 Agustus = Rp60.438.500
            | 15-21 Agustus = Rp47.925.000
            | 22-28 Agustus = Rp55.614.500
            | 29-31 Agustus = Rp26.021.500
            |
            | Total Agustus = Rp247.864.500
            |
            | Total kuantitas tiap barang juga tetap sama dengan tabel sumber.
            */
            $alokasi = [
                'BRG1MJ'  => [145, 712, 137, 118, 215],
                'BRG2MJ'  => [14, 0, 49, 0, 0],
                'BRG3MJ'  => [750, 780, 4420, 1080, 790],
                'BRG4MJ'  => [0, 0, 0, 252, 0],
                'BRG5MJ'  => [32, 42, 0, 115, 25],
                'BRG6MJ'  => [36, 1, 0, 68, 12],
                'BRG7MJ'  => [27, 1, 101, 0, 25],
                'BRG8MJ'  => [78, 0, 0, 0, 0],
                'BRG9MJ'  => [40, 11, 8, 9, 0],
                'BRG10MJ' => [14, 0, 17, 54, 11],
                'BRG11MJ' => [9, 14, 9, 56, 10],
                'BRG12MJ' => [13, 65, 0, 19, 0],
                'BRG13MJ' => [22, 3, 4, 5, 4],
                'BRG14MJ' => [44, 11, 7, 8, 7],
                'BRG15MJ' => [0, 8, 0, 59, 9],
                'BRG16MJ' => [27, 7, 5, 5, 4],
                'BRG17MJ' => [14, 0, 68, 0, 19],
            ];


            $periode = [
                [
                    'mulai' => Carbon::create(2026, 8, 1),
                    'selesai' => Carbon::create(2026, 8, 7),
                ],
                [
                    'mulai' => Carbon::create(2026, 8, 8),
                    'selesai' => Carbon::create(2026, 8, 14),
                ],
                [
                    'mulai' => Carbon::create(2026, 8, 15),
                    'selesai' => Carbon::create(2026, 8, 21),
                ],
                [
                    'mulai' => Carbon::create(2026, 8, 22),
                    'selesai' => Carbon::create(2026, 8, 28),
                ],
                [
                    'mulai' => Carbon::create(2026, 8, 29),
                    'selesai' => Carbon::create(2026, 8, 31),
                ],
            ];


            $hargaJual = collect($barang)
                ->pluck('harga_jual', 'kode_barang')
                ->all();


            $namaPembeli = [
                'Budi Santoso',
                'Andi Wijaya',
                'Siti Aminah',
                'Dedi Irawan',
                'Rudi Hartono',
                'Agus Salim',
                'Fajar Nugroho',
                'Hendra Saputra',
                'Rina Marlina',
                'Joko Susanto',
                'Dian Pratama',
                'Wahyu Setiawan',
                'Eko Saputra',
                'Maya Lestari',
                'Rizky Ramadhan',
            ];


            /*
            | Kolom `tujuan` pada nota_pembeli dipakai aplikasi sebagai
            | "Alamat / Tujuan Pengiriman" (tampil di bawah nama pembeli
            | pada nota cetak), jadi isinya harus berupa alamat/lokasi,
            | bukan alasan pembelian.
            */
            $alamatTujuan = [
                'Jl. Teuku Umar No. 45, Kedaton, Bandar Lampung',
                'Jl. Imam Bonjol No. 12, Kemiling, Bandar Lampung',
                'Jl. Soekarno-Hatta No. 88, Rajabasa, Bandar Lampung',
                'Jl. Pramuka No. 21, Way Halim, Bandar Lampung',
                'Jl. Ratu Dibalau No. 7, Tanjung Senang, Bandar Lampung',
                'Jl. Zainal Abidin Pagar Alam No. 30, Labuhan Ratu, Bandar Lampung',
            ];


            /*
            | Nomor nota berformat huruf + angka (INV-XXXXXX), sama dengan
            | format otomatis di model NotaPembeli. Seeder memakai DB::table()
            | sehingga event creating() milik model tidak berjalan; karena itu
            | nomor dibuat di sini dan dijamin unik.
            */
            $karakterNota = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $nomorNotaTerpakai = [];

            $buatNomorNota = function () use ($karakterNota, &$nomorNotaTerpakai) {
                do {
                    $kode = '';

                    for ($i = 0; $i < 6; $i++) {
                        $kode .= $karakterNota[random_int(0, strlen($karakterNota) - 1)];
                    }

                    $kode = 'INV-' . $kode;
                } while (isset($nomorNotaTerpakai[$kode]));

                $nomorNotaTerpakai[$kode] = true;

                return $kode;
            };


            /*
            |--------------------------------------------------------------------------
            | 5. BUAT TRANSAKSI HARIAN
            |--------------------------------------------------------------------------
            | Kuantitas tiap periode dibagi ke tanggal-tanggal dalam periode tersebut.
            | Hasil akhirnya tetap mempertahankan total bulanan dan total per periode.
            */
            $nomorHariGlobal = 0;

            foreach ($periode as $periodeIndex => $rentang) {

                $jumlahHari = $rentang['mulai']->diffInDays($rentang['selesai']) + 1;

                $dataPerHari = array_fill(0, $jumlahHari, []);


                foreach ($barang as $barangIndex => $item) {

                    $kode = $item['kode_barang'];
                    $jumlahPeriode = $alokasi[$kode][$periodeIndex];

                    if ($jumlahPeriode <= 0) {
                        continue;
                    }

                    $rata = intdiv($jumlahPeriode, $jumlahHari);
                    $sisa = $jumlahPeriode % $jumlahHari;


                    for ($hari = 0; $hari < $jumlahHari; $hari++) {

                        // Offset membuat distribusi sisa tidak selalu jatuh pada hari pertama.
                        $posisi = ($hari + $barangIndex) % $jumlahHari;

                        $jumlahHariIni = $rata + ($posisi < $sisa ? 1 : 0);

                        if ($jumlahHariIni > 0) {
                            $dataPerHari[$hari][] = [
                                'kode_barang' => $kode,
                                'jumlah' => $jumlahHariIni,
                                'harga_satuan' => $hargaJual[$kode],
                            ];
                        }
                    }
                }


                for ($hari = 0; $hari < $jumlahHari; $hari++) {

                    if (empty($dataPerHari[$hari])) {
                        continue;
                    }

                    $tanggal = $rentang['mulai']
                        ->copy()
                        ->addDays($hari)
                        ->setTime(9 + ($hari % 7), 15);


                    $totalNota = collect($dataPerHari[$hari])
                        ->sum(function ($detail) {
                            return $detail['jumlah'] * $detail['harga_satuan'];
                        });


                    $notaId = DB::table('nota_pembeli')->insertGetId([
                        'no_nota' => $buatNomorNota(),
                        'nama_pembeli' => $namaPembeli[
                            $nomorHariGlobal % count($namaPembeli)
                        ],
                        'tujuan' => $alamatTujuan[
                            $nomorHariGlobal % count($alamatTujuan)
                        ],
                        'tanggal_keluar' => $tanggal,
                        'total_harga' => $totalNota,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                        'deleted_at' => null,
                    ]);


                    $detailInsert = [];

                    foreach ($dataPerHari[$hari] as $detail) {

                        $detailInsert[] = [
                            'nota_pembeli_id' => $notaId,
                            'stok_barang_id' => $barangId[$detail['kode_barang']],
                            'jumlah' => $detail['jumlah'],
                            'harga_satuan' => $detail['harga_satuan'],
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                            'deleted_at' => null,
                        ];
                    }


                    DB::table('barang_keluar')->insert($detailInsert);

                    $nomorHariGlobal++;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 6. VALIDASI TOTAL
            |--------------------------------------------------------------------------
            | Seeder akan berhenti jika omzet tidak sama dengan tabel acuan.
            */
            $totalOmzet = DB::table('nota_pembeli')
                ->whereBetween('tanggal_keluar', [
                    '2026-08-01 00:00:00',
                    '2026-08-31 23:59:59',
                ])
                ->sum('total_harga');


            if ((int) $totalOmzet !== 247864500) {
                throw new \RuntimeException(
                    'Total omzet hasil seeder tidak sesuai. Hasil: Rp'
                    . number_format($totalOmzet, 0, ',', '.')
                );
            }
    }
}