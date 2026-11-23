<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'barang_masuk';

    protected $fillable = ['tanggal_masuk','tanggal_pesan', 'supplier','deleted_at'];

    // Relasi ke tabel Detail Barang Masuk
    public function detail()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }
    public function sudahDiterima(): bool
{
    return $this->tanggal_masuk !== null;
}
}