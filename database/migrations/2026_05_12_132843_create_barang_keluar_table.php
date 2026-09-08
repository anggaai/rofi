<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
            Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_pembeli_id')->constrained('nota_pembeli')->onDelete('cascade');
            $table->foreignId('stok_barang_id')->constrained('stok_barang')->onDelete('cascade');
            $table->integer('jumlah');
            $table->bigInteger('harga_satuan');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
