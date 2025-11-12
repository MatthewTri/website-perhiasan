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
        Schema::create('custom_request_images', function (Blueprint $table) {
            $table->id();
            // Relasi: Gambar ini milik konsultasi yang mana
            $table->foreignId('custom_request_id')->constrained()->onDelete('cascade');
            // URL permanen gambar (yang sudah Anda simpan di storage)
            $table->string('image_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_request_images');
    }
};
