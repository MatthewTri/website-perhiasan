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

            // Detail Pengajuan
            $table->string('reason_category'); // Enum: 'Rusak', 'Salah Kirim', 'Tidak Sesuai', 'Lainnya'
            $table->text('reason_description'); // Penjelasan user panjang lebar
            $table->string('proof_image')->nullable(); // Foto bukti barang rusak
            
            // Status Refund itu sendiri
            $table->enum('status', [
                'PENDING',  // Baru diajukan
                'APPROVED', // Admin setuju (Uang kembali)
                'REJECTED'  // Admin tolak
            ])->default('PENDING');
            
            // Respon Admin
            $table->text('admin_note')->nullable(); // Alasan kenapa ditolak/diterima
            $table->decimal('refund_amount', 15, 2)->nullable(); // Nominal yang direfund (bisa full/partial)
            
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
