<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaPembeli extends Model
{
    protected $table = 'nota_pembeli';
    use SoftDeletes;

    protected $fillable = [
        'no_nota',
        'nama_pembeli',
        'tujuan',
        'user_id',
        'tanggal_keluar',
        'total_harga',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
        'total_harga' => 'integer',
        'deleted_at' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();

        // Buat nomor invoice otomatis: huruf + angka, dijamin unik
        static::creating(function ($nota) {
            if (empty($nota->no_nota)) {
                do {
                    $kode = 'INV-' . self::buatKodeAcak();
                } while (self::where('no_nota', $kode)->exists());

                $nota->no_nota = $kode;
            }
        });
    }

    private static function buatKodeAcak(int $panjang = 6): string
    {
        // Tanpa 0, O, 1, I supaya tidak tertukar saat dibaca di kertas
        $karakter = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $kode = '';

        for ($i = 0; $i < $panjang; $i++) {
            $kode .= $karakter[random_int(0, strlen($karakter) - 1)];
        }

        return $kode;
    }

public function user()
{
    return $this->belongsTo(User::class);
}

    public function barangKeluar()
{
    return $this->hasMany(BarangKeluar::class, 'nota_pembeli_id'); 
}
}