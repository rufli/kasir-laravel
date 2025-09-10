<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produks';
    protected $casts = [
        'tanggal' => 'date',   // atau 'datetime' jika butuh jam
    ];
    protected $fillable = [
        'tanggal',
        'nama',
        'harga',
        'stok',
        'gambar', // Kolom untuk menyimpan nama file gambar
        'kategori_produk_id',
        'is_active', // Kolom untuk status aktif/non-aktif
    ];

    // Definisikan relasi dengan KategoriProduk
    public function kategoriProduk()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_produk_id');
    }
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset($this->gambar);
        }
        return null; // atau return URL gambar default
    }
    // Relasi yang diperbaiki, menghubungkan Produk ke DetailTransaksiPenjualan
    public function detailTransaksiPenjualan()
    {
        return $this->hasMany(DetailTransaksiPenjualan::class, 'produk_id');
    }
}
