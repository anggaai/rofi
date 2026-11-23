<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\MilikUser;

class BarangMasuk extends Model
{
    use HasFactory, SoftDeletes, MilikUser;
    protected $table = 'barang_masuk';

    protected $fillable = ['tanggal_masuk','tanggal_pesan', 'supplier','user_id','deleted_at'];

    // Relasi ke tabel Detail Barang Masuk
    public function detail()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }
    
public function user()
{
    return $this->belongsTo(User::class);
}
public function sudahDiterima(): bool
{
    return $this->tanggal_masuk !== null;
}
}