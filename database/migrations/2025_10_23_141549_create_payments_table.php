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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('gateway'); // 'xendit' atau 'paypal'
            $table->string('transaction_id')->nullable(); // ID dari Xendit/PayPal
            $table->string('status')->default('pending'); // pending, success, failed, refunded
            $table->decimal('amount', 15, 2);
            $table->text('payload')->nullable(); // Untuk menyimpan data mentah dari gateway
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
