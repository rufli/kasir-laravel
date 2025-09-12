<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'nama_usaha' => 'Usaha Kasir',
            'alamat_usaha' => 'Jl. Raya No. 1, Banyuwangi',
            'nama' => 'Administrator',
            'alamat' => 'Banyuwangi',
            'no_telepon' => '081234567890',
            'role' => 'admin'
        ]);

        // Akun Pegawai
        User::create([
            'username' => 'pegawai',
            'password' => Hash::make('pegawai123'),
            'nama_usaha' => 'Usaha Kasir',
            'alamat_usaha' => 'Jl. Raya No. 1, Banyuwangi',
            'nama' => 'Pegawai Kasir',
            'alamat' => 'Banyuwangi',
            'no_telepon' => '081298765432',
            'role' => 'pegawai'
        ]);

         $this->call([
            KategoriPengeluaranSeeder::class,
            KategoriProdukSeeder::class,
        ]);
    }
}
