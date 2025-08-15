<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 60);
            $table->string('username', 45)->unique();
            $table->char('password', 60);
            $table->string('nama_usaha', 100)->nullable();
            $table->string('alamat_usaha', 150)->nullable();
            $table->string('alamat', 60)->nullable();
            $table->string('img_profile', 255)->nullable();
            $table->string('no_telepon', 20)->unique()->nullable();
            $table->enum('role', ['admin', 'pegawai']);
            $table->boolean('is_active')->default(true); // Kolom baru untuk status aktif/non-aktif
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
