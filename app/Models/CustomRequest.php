<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CustomRequest extends Model
{
    /** @use HasFactory<\Database\Factories\CustomRequestFactory> */
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function order()
    // {
    //     return $this->hasOne(Order::class);
    // }

    public function images()
    {
        // Satu Request 'hasMany' (punya banyak) Gambar
        return $this->hasMany(CustomRequestImage::class);
    }

    // Helper untuk mendapatkan full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Helper untuk format appointment datetime
    public function getFormattedAppointmentAttribute()
    {
        return Carbon::parse($this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time)->format('l, F j, Y \a\t g:i A');
    }
}
