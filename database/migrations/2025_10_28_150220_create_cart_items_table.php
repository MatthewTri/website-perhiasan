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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Relasi: Item ini milik keranjang yang mana
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');

            // Relasi: Produk apa yang ditambahkan
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Jumlah barang
            $table->integer('quantity')->default(1);

            // Tambahkan ini agar 1 produk tidak muncul 2x di keranjang yang sama
            // (Lebih baik qty-nya ditambah, bukan buat baris baru)
            $table->unique(['cart_id', 'product_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
