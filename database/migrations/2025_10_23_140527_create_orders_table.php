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
            
            // Penanda Tipe: Apakah dari 'CATALOG' (Cart) atau 'CUSTOM' (Konsultasi)
            $table->string('order_type')->default('CATALOG'); 

            // 2. Identitas Pembeli (Support Guest & Member)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Null jika Guest
            $table->string('contact_email')->index(); // Wajib diisi untuk notifikasi/magic link
            
            // Kunci Rahasia untuk "Magic Link" (Track Order tanpa Login)
            $table->string('guest_token', 64)->unique()->nullable(); 

            // 3. Relasi ke Custom Request (Opsional)
            // Jika order_type = 'CUSTOM', ini akan terisi ID dari tabel custom_requests
            $table->foreignId('custom_request_id')->nullable()->constrained()->onDelete('set null');

            // 4. Status & Keuangan
            $table->string('status')->default('PENDING_REVIEW'); // Alur: PENDING -> INVOICE_SENT -> PAID -> SHIPPED -> COMPLETED
            $table->string('currency', 3)->default('IDR'); // 'IDR' atau 'USD'
            
            $table->decimal('subtotal', 15, 2); // Harga Barang Total
            $table->decimal('shipping_cost', 15, 2)->default(0); // Diinput Manual Admin
            $table->decimal('tax_amount', 15, 2)->default(0); // Diinput Manual/Otomatis Admin
            $table->decimal('total_amount', 15, 2); // Grand Total (Yang harus dibayar)

            // 5. Data Pengiriman (Shipping Address - Digabung di sini)
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address_line1');
            $table->string('shipping_city');
            $table->string('shipping_province')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country'); // Penting untuk Admin cek ongkir (ID/US/SG)

            // 6. Data Logistik (Diisi Admin setelah kirim barang)
            $table->string('courier_name')->nullable(); // JNE / DHL / Paxel
            $table->string('tracking_number')->nullable(); // Nomor Resi

            // 7. Data Pembayaran (Untuk keperluan Refund nanti)
            $table->string('payment_gateway')->nullable(); // 'XENDIT' atau 'PAYPAL'
            $table->string('transaction_id')->nullable(); // Capture ID dari PayPal / Invoice ID Xendit
            $table->string('payment_url')->nullable(); // Link bayar yang dikirim ke email

            // 8. Review (Pencegahan Double Review)
            $table->boolean('is_reviewed')->default(false);

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
