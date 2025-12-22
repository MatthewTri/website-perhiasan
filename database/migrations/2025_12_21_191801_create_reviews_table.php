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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // 1. Relasi (Siapa, Beli Apa, Di Transaksi Mana)
            // Nullable user_id disiapkan untuk Guest checkout ingin dibolehkan review
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Produk yang direview
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Bukti Transaksi (PENTING untuk label "Verified Purchase")
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // 2. Isi Review
            $table->unsignedTinyInteger('rating'); // Angka 1 sampai 5
            $table->text('comment')->nullable();   // Komentar teks (Boleh kosong kalau cuma kasih bintang)
            $table->string('image')->nullable();   // Path foto (Upload bukti barang)
            $table->boolean('anonymous')->default(false); // Pilihan tampilkan nama atau tidak

            // 3. Status Moderasi
            // Default true. Admin bisa ubah jadi false jika review mengandung kata kasar/spam.
            $table->boolean('is_visible')->default(true);

            $table->timestamps();

            // 4. Constraint Anti-Spam
            // Kombinasi order_id dan product_id harus unik.
            // Artinya: Di Order #INV-001, User tidak bisa review "Baju Merah" 2 kali.
            $table->unique(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
