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
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_barang_id')->constrained('stok_barang');
            $table->enum('jenis_opname', ['penambahan', 'pengurangan']);
            $table->integer('jumlah_perubahan');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->text('keterangan_opname')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('tanggal');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
    }
};
