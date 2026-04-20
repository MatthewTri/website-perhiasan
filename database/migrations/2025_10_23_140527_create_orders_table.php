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
            // 1. Identitas Pesanan
            $table->id();
            $table->string('order_number')->unique(); // Contoh: INV-2025-001

            // slug per order
            $table->string('slug')->unique();
            
            // Penanda Tipe: Apakah dari 'CATALOG' (Cart) atau 'CUSTOM' (Konsultasi)
            $table->string('order_type')->default('CATALOG'); 

            // 2. Identitas Pembeli (Support Guest & Member)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Null jika Guest
            $table->string('contact_email')->index(); // Wajib diisi untuk notifikasi/magic link
            
            // Kunci Rahasia untuk "Magic Link" (Track Order tanpa Login)
            $table->string('guest_token', 64)->unique()->nullable(); 

            // 3. Relasi ke Custom Request (Opsional)
            // Jika order_type = 'CUSTOM', ini akan terisi ID dari tabel custom_requests
            // $table->foreignId('custom_request_id')->nullable()->constrained()->onDelete('set null');

            // 4. Status & Keuangan
            $table->enum('status', [
                'PENDING_REVIEW',       // Belum bayar
                'INVOICE_SENT',         // Invoice sudah dikirim ke email
                'WAITING_VERIFICATION', // Upload bukti bayar manual (khusus manual payment BCA)
                'PAID',                 // Lunas (khusus PayPal yang langsung paid)
                'PRODUCTION',           // Pesanan sedang dibuat oleh penjual
                'SHIPPED',              // Dikirim
                'COMPLETED',            // Selesai
                'CANCELLED',            // Batal (Expired/Manual Cancel)
                'REFUNDED',             // Refund selesai
            ])->default('PENDING_REVIEW')->index();

            $table->string('currency', 3)->default('IDR'); // 'IDR' atau 'USD'
            
            $table->decimal('subtotal_idr', 15, 2); // Subtotal dalam IDR
            $table->decimal('subtotal_usd', 15, 2); // Subtotal dalam USD
            $table->decimal('shipping_cost', 15, 2)->default(0); // Diinput Manual Admin
            $table->decimal('tax_amount', 15, 2)->default(0); // Diinput Manual/Otomatis Admin
            $table->decimal('total_amount', 15, 2); // Grand Total (Yang harus dibayar)

            // 5. Data Pembayaran (Untuk keperluan Refund nanti)
            $table->string('payment_preference')->default('MANUAL'); // MANUAL atau PAYPAL
            $table->string('payment_gateway')->nullable()->index();; // 'MANUAL' atau 'PAYPAL'
            $table->string('payment_method')->nullable(); // 'BCA' , 'PAYPAL'
            $table->string('transaction_id')->nullable()->index(); // Transaction ID dari PayPal
            $table->string('capture_id')->nullable()->index(); // Capture ID dari PayPal
            $table->string('payment_url')->nullable(); // Link paypal bayar yang dikirim ke email
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran berhasil
            $table->timestamp('completed_at')->nullable(); // Waktu order selesai
            $table->timestamp('cancelled_at')->nullable(); // Waktu pembatalan order
            $table->timestamp('refunded_at')->nullable(); // Waktu refund selesai

            $table->string('proof_of_payment')->nullable(); // URL/Gambar bukti transfer manual

            // 6. Catatan Pelanggan
            $table->text('order_notes')->nullable(); // Catatan dari customer saat checkout

            // 7. Catatan Admin untuk pelanggan
            $table->text('admin_notes')->nullable(); // Catatan dari admin untuk customer
            $table->text('rejection_notes')->nullable(); // Catatan alasan tolak pembayaran BCA

            // 8. Review (Pencegahan Double Review)`
            $table->boolean('is_reviewed')->default(false);

            // 9. Refund
            $table->decimal('refund_amount', 15, 2)->nullable(); // Jumlah yang direfund

            $table->index(['status', 'created_at'], 'idx_status_created');
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
