<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaPembeli extends Model
{
    protected $table = 'nota_pembeli';
    use SoftDeletes;

    protected $fillable = [
        'nama_pembeli',
        'tujuan',
        'tanggal_keluar',
        'total_harga',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
        'total_harga' => 'integer',
        'deleted_at' => 'datetime',
    ];
    public function barangKeluar()
{
    return $this->hasMany(BarangKeluar::class, 'nota_pembeli_id'); 
}
}