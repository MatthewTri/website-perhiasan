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
    
    // Helper untuk format appointment yang lebih baik
    public function getFormattedAppointmentAttribute()
    {
        $date = Carbon::parse($this->appointment_date);
        $startTime = Carbon::parse($this->appointment_time);
        
        // Hitung end time berdasarkan consultation type
        $duration = $this->consultation_type === 'design' ? 60 : 30;
        $endTime = $startTime->copy()->addMinutes($duration);
        
        // Format: 4 January 2026, 9:30 AM - 10:00 AM
        return $date->format('j F Y') . ', ' . 
               $startTime->format('g:i A') . ' - ' . 
               $endTime->format('g:i A');
    }
}
