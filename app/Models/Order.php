<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use Sluggable;

    protected $guarded = [
        'id'
    ];

     protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relasi: Order ini milik siapa (bisa jadi Guest)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Item apa saja di order ini
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Pembayaran untuk order ini
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Relasi: Pengajuan refund untuk order ini
    public function refundRequest()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function customRequest()
    {
        // Sebuah Order 'belongsTo' satu CustomRequest (tapi bisa jadi null)
        return $this->belongsTo(CustomRequest::class);
    }

    public function getRouteKeyName() {
        return 'slug';
    }

     public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'order_number'
            ]
        ];
    }
}
