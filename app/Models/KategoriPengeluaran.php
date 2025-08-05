<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengeluaran';

    protected $fillable = [
        'nama',
    ];

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_pengeluaran_id');
    }
}
