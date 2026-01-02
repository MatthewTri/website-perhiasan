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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            // Consultation Type
            $table->enum('consultation_type', ['short', 'design'])->default('short');
            
            // Appointment DateTime
            $table->date('appointment_date');
            $table->time('appointment_time');
            
            // User Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('user_email');
            $table->string('user_phone');
            
            // Appointment Notes
            $table->text('appointment_notes')->nullable();
            
            // Admin Notes (for cancellation reason, etc)
            $table->text('admin_notes')->nullable();
            
            // Status
            $table->string('status')->default('SCHEDULED'); // SCHEDULED, COMPLETED, CANCELLED, RESCHEDULED
            
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
