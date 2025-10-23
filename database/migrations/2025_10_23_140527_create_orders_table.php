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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); 

            // --- Untuk Guest Checkout (Katalog) ---
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // Jika user tidak login, kita simpan emailnya di sini
            $table->string('contact_email')->nullable(); 
            // Kita tambahkan index agar pencarian "Track Order" cepat , untuk optimalisasi database performance
            $table->index('contact_email');

            // --- Untuk Menghubungkan ke Invoice Kustom (Opsional) ---
            // Jika order ini dibuat dari alur kustom, kita catat ID-nya.
            $table->foreignId('custom_request_id')->nullable()->constrained();

            $table->string('status')->default('pending'); // pending, paid, shipped, refunded, etc.
            
            // --- REVISI DARI PERTANYAAN ANDA ---
            $table->string('currency', 10); // IDR atau USD (Ditentukan sistem, bukan pilihan user)

            // --- Rincian Finansial (Total Akhir) ---
            $table->decimal('subtotal', 15, 2);
            $table->decimal('shipping_cost', 15, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
