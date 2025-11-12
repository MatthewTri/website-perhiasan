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
        Schema::create('custom_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Wajib user login
            
            // --- Data dari Form Awal (Saat Submit) ---
            $table->string('user_name');
            $table->string('user_email');
            $table->string('country');
            $table->text('notes')->nullable();
            $table->string('status')->default('BARU'); // BARU, DISKUSI, INVOICE_SENT, LUNAS, DITOLAK

            // --- REVISI UNTUK MULTI-GATEWAY ---
            $table->string('payment_gateway')->nullable(); // Akan diisi 'xendit' atau 'paypal'
            $table->string('gateway_invoice_id')->nullable(); // ID dari Xendit/PayPal
            $table->text('payment_link')->nullable(); // URL bayar (Xendit/PayPal)
            
            // --- REVISI PENTING (Menjawab "Lebih dari 1 Produk") ---
            // Kita simpan detail invoice (yang diisi admin) sebagai JSON.
            // Ini JAUH lebih fleksibel.
            $table->json('invoice_details')->nullable(); 
            // Contoh JSON: 
            // {
            //   "currency": "USD",
            //   "items": [
            //     {"name": "Cincin Kustom Safir", "qty": 1, "price": 800},
            //     {"name": "Biaya Ongkir Internasional", "qty": 1, "price": 50}
            //   ]
            // }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_requests');
    }
};
