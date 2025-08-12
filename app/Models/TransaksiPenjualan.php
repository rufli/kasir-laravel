<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualans';

    protected $fillable = [
        'tanggal',
        'total_harga',
        'metode_pembayaran',
        'jumlah_dibayar',
        'jumlah_kembalian',
        'users_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Relasi ke DetailTransaksiPenjualan
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksiPenjualan::class, 'transaksi_penjualan_id');
    }
}
