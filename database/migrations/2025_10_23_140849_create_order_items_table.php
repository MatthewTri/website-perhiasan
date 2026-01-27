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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // 1. Untuk Produk Katalog
            // (Bisa null jika ini adalah item dari pesanan kustom)
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            
            // 2. Untuk Produk Kustom
            // (Akan diisi manual dari form invoice admin)
            $table->string('item_name'); // (Akan di-copy dari product->name ATAU diisi manual)
            $table->text('item_description')->nullable(); // (Deskripsi dari invoice kustom)

            $table->integer('quantity');
            $table->decimal('price_idr', 15, 2); // Harga per unit dalam IDR
            $table->decimal('price_usd', 15, 2); // Harga per unit dalam USD
            $table->string('color')->nullable(); // Opsi warna (jika ada)
            $table->string('size')->nullable(); // Opsi ukuran (jika ada)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
