<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokBarang extends Model
{
   use HasFactory, SoftDeletes; 
    
    protected $table = 'stok_barang';
    
    protected $fillable = [
        'kode_barang', 
        'nama_barang', 
        'kategori', 
        'stok', 
        'stok_minimum',
        'metode_stok_minimum',
        'stok_minimum_custom', 
        'harga_beli', 
        'harga_jual',
        'gambar',
    ];
    protected static function boot()
{
    parent::boot();

    // Fungsi otomatis berjalan sesaat sebelum data disimpan ke database
    static::creating(function ($barang) {
        // JIKA user tidak mengisi kode_barang manual, baru kita generate otomatis
        if (empty($barang->kode_barang)) {
            
            // TAMBAHKAN withTrashed() di sini agar sadar data di tempat sampah!
            $lastBarang = self::withTrashed()
                                ->where('kode_barang', 'LIKE', 'BRG%MJ')
                                ->orderBy('id', 'desc')
                                ->first();

            if ($lastBarang) {
                // Ekstrak angka di tengah, misal 'BRG1MJ' menjadi 1
                $lastNumber = (int) str_replace(['BRG', 'MJ'], '', $lastBarang->kode_barang);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // Menghasilkan kode sesuai format awalmu: BRG1MJ, BRG2MJ, dst.
            $barang->kode_barang = 'BRG' . $nextNumber . 'MJ';
        }
    });
}
public function stokOpnames()
    {
        return $this->hasMany(StokOpname::class, 'stok_barang_id');
    }
public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'stok_barang_id');
    }
    
protected $dates = ['deleted_at']; 
}