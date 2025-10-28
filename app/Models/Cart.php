<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = ['user_id'];

    // Relasi ke pemilik keranjang
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item di dalam keranjang
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
