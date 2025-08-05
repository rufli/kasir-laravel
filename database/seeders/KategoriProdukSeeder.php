<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $kategoris = ["Makanan", "Minuman"];
       foreach ($kategoris as $k) {
        KategoriProduk::create(["nama" => $k]);
       }
    }
}
