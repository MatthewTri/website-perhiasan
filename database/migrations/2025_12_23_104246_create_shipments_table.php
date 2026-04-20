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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Orders (One-to-One)
            $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
            
            // Recipient Information (Penerima)
            $table->string('first_name')->index(); // Nama depan
            $table->string('last_name')->index();  // Nama belakang
            $table->string('phone');
            
            // Address Details
            $table->text('address_line1'); // Alamat utama
            $table->string('city', 100);
            $table->string('province', 100)->nullable(); // State/Province
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100); // Negara
            
            // Logistics Information (Diisi Admin setelah kirim)
            $table->string('courier_name')->nullable(); // JNE / RPX / Paxel
            $table->string('tracking_number')->nullable(); // Nomor Resi
            
            // Timestamps untuk tracking
            $table->timestamp('shipped_at')->nullable(); // Kapan dikirim
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
