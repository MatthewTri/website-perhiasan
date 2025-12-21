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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_idr', 15, 2); // Harga produk dalam IDR
            $table->decimal('price_usd', 15, 2); // Harga produk dalam USD
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            $table->decimal('rating_avg', 3, 2)->default(0); // Contoh: 4.55
            $table->integer('rating_count')->default(0);     // Contoh: 150 ulasan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
