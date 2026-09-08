<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokBarangFactory extends Factory
{
    public function definition(): array
    {
        $kategori = [
            'Elektronik',
            'Material',
            'Aksesoris',
            'Peralatan',
            'ATK',
            'Furniture'
        ];

        $hargaBeli = fake()->numberBetween(10000, 5000000);

        return [
            'kode_barang' => 'BRG' . fake()->unique()->numberBetween(1000, 9999),

            'nama_barang' => fake()->randomElement([
                'Laptop Asus',
                'Mouse Logitech',
                'Keyboard Mechanical',
                'Printer Epson',
                'Flashdisk Sandisk',
                'Monitor Samsung',
                'Kabel LAN',
                'Router TP-Link',
                'Kursi Kantor',
                'Meja Kerja',
                'Semen',
                'Cat Tembok',
                'Obeng',
                'Bor Listrik',
                'Kertas A4',
                'Pulpen',
                'Stapler',
                'Lem Tembak',
                'Lampu LED',
                'Harddisk External'
            ]),

            'kategori' => fake()->randomElement($kategori),

            'stok' => fake()->numberBetween(1, 200),

            'stok_minimum' => fake()->numberBetween(1, 20),

            'harga_beli' => $hargaBeli,

            'harga_jual' => $hargaBeli + fake()->numberBetween(5000, 500000),
        ];
    }
}