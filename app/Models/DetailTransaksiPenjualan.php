<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_penjualans';

    protected $fillable = [
        'jumlah',
        'subtotal',
        'transaksi_penjualan_id',
        'produk_id',
    ];

    // Relasi ke TransaksiPenjualan
    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_penjualan_id');
    }

    // Relasi ke Produk
    public function produks()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
