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
    public function up(): void
    {
        Schema::table('stok_barang', function (Blueprint $table) {

                $table->string('metode_stok_minimum', 20)
                    ->default('custom')
                    ->after('stok_minimum');

                $table->unsignedInteger('stok_minimum_custom')
                    ->default(0)
                    ->after('metode_stok_minimum');
            });

            // Nilai stok minimum lama dianggap sebagai nilai custom
            DB::table('stok_barang')->update([
                'stok_minimum_custom' => DB::raw('stok_minimum')
            ]);
            }

    /**
     * Reverse the migrations.
     */
    public function down(): void
        {
            Schema::table('stok_barang', function (Blueprint $table) {
                $table->dropColumn([
                    'metode_stok_minimum',
                    'stok_minimum_custom'
                ]);
            });
        }
};
