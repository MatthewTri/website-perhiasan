<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisabledSlot extends Model
{
    protected $fillable = ['date', 'time_slot'];
    protected $casts = ['date' => 'date'];
}