<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'barang_masuk';

    protected $fillable = ['stok_barang_id', 'jumlah', 'tanggal_masuk', 'supplier','deleted_at'];

    // Relasi ke tabel StokBarang
    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }
}