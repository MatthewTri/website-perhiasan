<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = ['user_id'];

    protected $casts = [
        'last_prices_checked_at' => 'datetime',
    ];

    // Relasi ke pemilik keranjang
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item di dalam keranjang
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
