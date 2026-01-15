<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disabled_slots', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time_slot')->nullable(); // null = full day disabled
            $table->timestamps();
            
            $table->unique(['date', 'time_slot']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('disabled_slots');
    }
};