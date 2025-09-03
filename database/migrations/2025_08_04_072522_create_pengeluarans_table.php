<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama', 45);
            $table->string('satuan', 10);
            $table->decimal('jumlah', 15, 2); // precision & scale
            $table->string('catatan', 60)->nullable();

            $table->foreignId('kategori_pengeluaran_id')
                  ->constrained('kategori_pengeluaran')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('user_id') // ganti dari users_id ke user_id
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
