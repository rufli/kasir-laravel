<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('metode_pembayaran', ['tunai', 'transfer']);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('jumlah_dibayar', 15, 2);
            $table->decimal('jumlah_kembalian', 15, 2);
            $table->unsignedBigInteger('users_id');
            $table->timestamps();

            $table->foreign('users_id')
                  ->references('id')->on('users')
                  ->onUpdate('no action')
                  ->onDelete('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualans');
    }
};
