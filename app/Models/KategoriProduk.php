<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = "kategori_produks";
    protected $fillable = ["nama"];
    public $timestamps = true;
}
