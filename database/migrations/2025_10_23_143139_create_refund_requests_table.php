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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Refund untuk order yg mana
            $table->foreignId('user_id')->nullable()->constrained(); // User yg request (jika login)
            $table->string('guest_email')->nullable(); // Email (jika guest)

            // --- Data dari Form Refund ---
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->string('proof_image_url')->nullable(); // Link ke foto bukti

            // --- Status Alur Refund ---
            $table->string('status')->default('PENGAJUAN'); // PENGAJUAN, DISETUJUI, DITOLAK, PROSES_REFUND, DIKEMBALIKAN
            
            $table->string('refund_id_gateway')->nullable(); // ID Refund dari Xendit/PayPal (jika ada)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
