<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'barang_keluar';

    protected $fillable = ['nota_pembeli_id','stok_barang_id', 'jumlah', 'harga_satuan','deleted_at'];
    protected $casts = [
    'tanggal_keluar' => 'datetime',
    'deleted_at' => 'datetime',
    ];
    public function notaPembeli() {
        return $this->belongsTo(NotaPembeli::class);
    }
    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }
}