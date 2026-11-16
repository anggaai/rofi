<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('stok_barang', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique();
        $table->string('nama_barang');
        $table->string('kategori');
        $table->integer('stok')->default(0);
        $table->integer('stok_minimum')->default(0);
        $table->string('metode_stok_minimum', 20)->default('custom');
        $table->unsignedInteger('stok_minimum_custom')->default(0);
        $table->decimal('harga_beli', 12, 2);
        $table->decimal('harga_jual', 12, 2);
        $table->string('gambar')->nullable();
        $table->timestamps();
        });
         DB::table('stok_barang')->update([
                'stok_minimum_custom' => DB::raw('stok_minimum')
            ]);
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
