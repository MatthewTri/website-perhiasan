<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;
    
    protected $fillable = [
        'order_id', 'gateway', 'transaction_id', 'status', 'amount', 'payload'
    ];

    // Relasi: Payment ini milik order mana
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
