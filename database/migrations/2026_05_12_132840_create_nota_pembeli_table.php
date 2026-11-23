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
         Schema::create('nota_pembeli', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota', 20)->nullable()->unique();
            $table->string('nama_pembeli');
            $table->string('tujuan')->nullable(); 
            $table->foreignId('user_id')->nullable();
            $table->datetime('tanggal_keluar');
            $table->bigInteger('total_harga')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_pembeli');
    }
};
