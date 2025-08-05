<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->date("tanggal");
            $table->string("nama", 45);
            $table->decimal("harga", 10, 2);
            $table->integer("stok");

            $table->foreignId('kategori_produk_id')->constrained('kategori_produks')->onDelete('cascade');
            // foreignId adalah shortcut untuk unsignedBigInteger,
            // constrained('kategori_produks') otomatis membuat foreign key ke tabel kategori_produks
            // onDelete('cascade') berarti jika kategori dihapus, produk yang terkait juga dihapus.
            // Anda bisa mengubahnya menjadi 'set null' atau 'restrict' sesuai kebutuhan.


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
