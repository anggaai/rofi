<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. DATA STOK BARANG
        |--------------------------------------------------------------------------
        */

        $barang = [
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Semen Tiga Roda 50kg',
                'kategori' => 'Semen',
                'stok' => 100,
                'stok_minimum' => 20,
                'harga_beli' => 65000,
                'harga_jual' => 75000,
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Semen Padang 50kg',
                'kategori' => 'Semen',
                'stok' => 80,
                'stok_minimum' => 20,
                'harga_beli' => 63000,
                'harga_jual' => 73000,
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Besi Beton 8mm',
                'kategori' => 'Besi',
                'stok' => 150,
                'stok_minimum' => 30,
                'harga_beli' => 42000,
                'harga_jual' => 50000,
            ],
            [
                'kode_barang' => 'BRG004',
                'nama_barang' => 'Besi Beton 10mm',
                'kategori' => 'Besi',
                'stok' => 120,
                'stok_minimum' => 25,
                'harga_beli' => 65000,
                'harga_jual' => 75000,
            ],
            [
                'kode_barang' => 'BRG005',
                'nama_barang' => 'Besi Beton 12mm',
                'kategori' => 'Besi',
                'stok' => 100,
                'stok_minimum' => 20,
                'harga_beli' => 90000,
                'harga_jual' => 105000,
            ],
            [
                'kode_barang' => 'BRG006',
                'nama_barang' => 'Pasir Bangunan',
                'kategori' => 'Material',
                'stok' => 50,
                'stok_minimum' => 10,
                'harga_beli' => 250000,
                'harga_jual' => 300000,
            ],
            [
                'kode_barang' => 'BRG007',
                'nama_barang' => 'Batu Split',
                'kategori' => 'Material',
                'stok' => 40,
                'stok_minimum' => 10,
                'harga_beli' => 300000,
                'harga_jual' => 350000,
            ],
            [
                'kode_barang' => 'BRG008',
                'nama_barang' => 'Cat Tembok 5kg',
                'kategori' => 'Cat',
                'stok' => 60,
                'stok_minimum' => 15,
                'harga_beli' => 85000,
                'harga_jual' => 100000,
            ],
            [
                'kode_barang' => 'BRG009',
                'nama_barang' => 'Paku 5cm',
                'kategori' => 'Perkakas',
                'stok' => 200,
                'stok_minimum' => 50,
                'harga_beli' => 18000,
                'harga_jual' => 25000,
            ],
            [
                'kode_barang' => 'BRG010',
                'nama_barang' => 'Triplek 9mm',
                'kategori' => 'Kayu',
                'stok' => 70,
                'stok_minimum' => 15,
                'harga_beli' => 75000,
                'harga_jual' => 90000,
            ],
        ];

        foreach ($barang as $item) {
            DB::table('stok_barang')->insert([
                'kode_barang' => $item['kode_barang'],
                'nama_barang' => $item['nama_barang'],
                'kategori' => $item['kategori'],
                'stok' => $item['stok'],
                'stok_minimum' => $item['stok_minimum'],
                'harga_beli' => $item['harga_beli'],
                'harga_jual' => $item['harga_jual'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. DATA NOTA PEMBELI
        |--------------------------------------------------------------------------
        */

        $nota = [
            [
                'nama_pembeli' => 'Budi Santoso',
                'tujuan' => 'Pembangunan Rumah',
                'tanggal_keluar' => '2026-08-01 09:15:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Andi Wijaya',
                'tujuan' => 'Renovasi Rumah',
                'tanggal_keluar' => '2026-08-01 10:30:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Siti Aminah',
                'tujuan' => 'Pembangunan Ruko',
                'tanggal_keluar' => '2026-08-02 08:45:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Dedi Irawan',
                'tujuan' => 'Proyek Perumahan',
                'tanggal_keluar' => '2026-08-02 13:20:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Rudi Hartono',
                'tujuan' => 'Renovasi Toko',
                'tanggal_keluar' => '2026-08-03 09:00:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Agus Salim',
                'tujuan' => 'Pembangunan Garasi',
                'tanggal_keluar' => '2026-08-04 11:15:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Fajar Nugroho',
                'tujuan' => 'Pembangunan Rumah',
                'tanggal_keluar' => '2026-08-05 14:00:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Hendra Saputra',
                'tujuan' => 'Renovasi Gedung',
                'tanggal_keluar' => '2026-08-06 10:10:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Rina Marlina',
                'tujuan' => 'Pembangunan Rumah',
                'tanggal_keluar' => '2026-08-07 08:30:00',
                'total_harga' => 0,
            ],
            [
                'nama_pembeli' => 'Joko Susanto',
                'tujuan' => 'Pembangunan Gudang',
                'tanggal_keluar' => '2026-08-08 15:30:00',
                'total_harga' => 0,
            ],
        ];

        foreach ($nota as $item) {
            DB::table('nota_pembeli')->insert([
                'nama_pembeli' => $item['nama_pembeli'],
                'tujuan' => $item['tujuan'],
                'tanggal_keluar' => $item['tanggal_keluar'],
                'total_harga' => $item['total_harga'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. DATA BARANG MASUK
        |--------------------------------------------------------------------------
        */

        $barangMasuk = [
            [
                'stok_barang_id' => 1,
                'jumlah' => 50,
                'tanggal_masuk' => '2026-07-25',
                'supplier' => 'PT Tiga Roda',
            ],
            [
                'stok_barang_id' => 2,
                'jumlah' => 40,
                'tanggal_masuk' => '2026-07-26',
                'supplier' => 'PT Semen Padang',
            ],
            [
                'stok_barang_id' => 3,
                'jumlah' => 75,
                'tanggal_masuk' => '2026-07-27',
                'supplier' => 'CV Baja Jaya',
            ],
            [
                'stok_barang_id' => 4,
                'jumlah' => 60,
                'tanggal_masuk' => '2026-07-28',
                'supplier' => 'CV Baja Jaya',
            ],
            [
                'stok_barang_id' => 5,
                'jumlah' => 50,
                'tanggal_masuk' => '2026-07-29',
                'supplier' => 'PT Krakatau Steel',
            ],
            [
                'stok_barang_id' => 6,
                'jumlah' => 30,
                'tanggal_masuk' => '2026-07-30',
                'supplier' => 'CV Sumber Material',
            ],
            [
                'stok_barang_id' => 7,
                'jumlah' => 25,
                'tanggal_masuk' => '2026-07-31',
                'supplier' => 'CV Batu Makmur',
            ],
            [
                'stok_barang_id' => 8,
                'jumlah' => 30,
                'tanggal_masuk' => '2026-08-01',
                'supplier' => 'PT Cat Indonesia',
            ],
            [
                'stok_barang_id' => 9,
                'jumlah' => 100,
                'tanggal_masuk' => '2026-08-02',
                'supplier' => 'CV Perkakas Jaya',
            ],
            [
                'stok_barang_id' => 10,
                'jumlah' => 35,
                'tanggal_masuk' => '2026-08-03',
                'supplier' => 'CV Kayu Sejahtera',
            ],
        ];

        foreach ($barangMasuk as $item) {
            DB::table('barang_masuk')->insert([
                'stok_barang_id' => $item['stok_barang_id'],
                'jumlah' => $item['jumlah'],
                'tanggal_masuk' => $item['tanggal_masuk'],
                'supplier' => $item['supplier'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. DATA BARANG KELUAR
        |--------------------------------------------------------------------------
        */

        $barangKeluar = [
            [
                'nota_pembeli_id' => 1,
                'stok_barang_id' => 1,
                'jumlah' => 5,
                'harga_satuan' => 75000,
            ],
            [
                'nota_pembeli_id' => 2,
                'stok_barang_id' => 2,
                'jumlah' => 4,
                'harga_satuan' => 73000,
            ],
            [
                'nota_pembeli_id' => 3,
                'stok_barang_id' => 3,
                'jumlah' => 10,
                'harga_satuan' => 50000,
            ],
            [
                'nota_pembeli_id' => 4,
                'stok_barang_id' => 4,
                'jumlah' => 8,
                'harga_satuan' => 75000,
            ],
            [
                'nota_pembeli_id' => 5,
                'stok_barang_id' => 5,
                'jumlah' => 6,
                'harga_satuan' => 105000,
            ],
            [
                'nota_pembeli_id' => 6,
                'stok_barang_id' => 6,
                'jumlah' => 3,
                'harga_satuan' => 300000,
            ],
            [
                'nota_pembeli_id' => 7,
                'stok_barang_id' => 7,
                'jumlah' => 2,
                'harga_satuan' => 350000,
            ],
            [
                'nota_pembeli_id' => 8,
                'stok_barang_id' => 8,
                'jumlah' => 5,
                'harga_satuan' => 100000,
            ],
            [
                'nota_pembeli_id' => 9,
                'stok_barang_id' => 9,
                'jumlah' => 20,
                'harga_satuan' => 25000,
            ],
            [
                'nota_pembeli_id' => 10,
                'stok_barang_id' => 10,
                'jumlah' => 4,
                'harga_satuan' => 90000,
            ],
        ];

        foreach ($barangKeluar as $item) {
            DB::table('barang_keluar')->insert([
                'nota_pembeli_id' => $item['nota_pembeli_id'],
                'stok_barang_id' => $item['stok_barang_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}