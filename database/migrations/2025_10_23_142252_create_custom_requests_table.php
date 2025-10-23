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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Wajib user login
            
            // --- Data dari Form Awal (Saat Submit) ---
            $table->string('user_name');
            $table->string('user_email');
            $table->string('country');
            $table->text('notes')->nullable();
            $table->string('generated_image_url');
            $table->string('status')->default('BARU'); // BARU, DISKUSSI, INVOICE_SENT, LUNAS, DITOLAK

            // --- Diisi Admin Saat Buat Invoice ---
            $table->string('paypal_invoice_id')->nullable(); // ID dari PayPal (untuk dicocokkan Webhook)
            
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
