<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stok_barang', function (Blueprint $table) {
            // Menambahkan kolom gambar setelah kolom kategori
            // nullable() digunakan agar barang lama yang belum punya foto tidak error
            $table->string('gambar')->nullable()->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_barang', function (Blueprint $table) {
            // Menghapus kembali kolom gambar jika migration di-rollback
            $table->dropColumn('gambar');
        });
    }
};