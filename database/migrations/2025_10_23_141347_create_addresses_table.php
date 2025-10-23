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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Alamat ini milik order yg mana
            $table->string('type')->default('shipping'); // 'shipping' atau 'billing'
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address_line_1');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            $table->string('country'); // Sangat penting untuk menentukan ongkir Internasional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
