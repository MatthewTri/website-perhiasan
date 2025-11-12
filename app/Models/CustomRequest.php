<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomRequest extends Model
{
    /** @use HasFactory<\Database\Factories\CustomRequestFactory> */
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        // Sebuah CustomRequest 'hasOne' Order
        return $this->hasOne(Order::class);
    }

    public function images()
    {
        // Satu Request 'hasMany' (punya banyak) Gambar
        return $this->hasMany(CustomRequestImage::class);
    }
}
