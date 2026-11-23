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
    Schema::create('barang_masuk', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal_pesan');
        $table->date('tanggal_masuk')->nullable();
        $table->string('supplier')->nullable();
        $table->foreignId('user_id')->nullable();
        $table->softDeletes();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
