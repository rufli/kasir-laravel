<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'nama' => 'Administrator',
            'alamat' => 'Banyuwangi',
            'no_telepon' => '081234567890',
            'role' => 'admin'
        ]);

         $this->call([
            KategoriPengeluaranSeeder::class,
        ]);
    }
}
