<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi_penjualans', function (Blueprint $table) {
            $table->id();

             // Kolom untuk menyimpan data snapshot produk
            $table->string('nama_produk');
            $table->decimal('harga_produk', 15, 2);

            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);
            $table->unsignedBigInteger('transaksi_penjualan_id');
            $table->unsignedBigInteger('produk_id');
            $table->timestamps();


            $table->foreign('transaksi_penjualan_id')
                  ->references('id')->on('transaksi_penjualans')
                  ->onUpdate('no action')
                  ->onDelete('no action');

            $table->foreign('produk_id')
                  ->references('id')->on('produks')
                  ->onUpdate('no action')
                  ->onDelete('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_penjualans');
    }
};
