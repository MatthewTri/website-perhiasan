<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id', 'type', 'full_name', 'email', 'phone',
        'address_line_1', 'city', 'province', 'postal_code', 'country'
    ];

    // Relasi: Address ini milik order mana
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
