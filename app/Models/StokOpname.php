<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokOpname extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'stok_barang_id',
        'jenis_opname',
        'jumlah_perubahan',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan_opname',
        'user_id',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}