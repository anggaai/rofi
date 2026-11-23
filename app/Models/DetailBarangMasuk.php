<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailBarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'detail_barang_masuk';

    protected $fillable = ['barang_masuk_id', 'stok_barang_id', 'jumlah', 'harga_beli'];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }

    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }
}