<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use Sluggable;

    protected $guarded = [
        'id'
    ];

     protected $casts = [
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
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


    // Aksesor untuk cek Refund
    public function getCanRequestRefundAttribute()
    {
        if ($this->status !== 'COMPLETED') return false;
        
        // Batas 3 Hari
        $limit = Carbon::parse($this->completed_at)->addDays(3);
        return now()->lessThan($limit);
    }

    // Aksesor untuk cek Review
    public function getCanReviewAttribute()
    {
        if ($this->status !== 'COMPLETED') return false;
        
        // Batas 30 Hari
        $limit = Carbon::parse($this->completed_at)->addDays(30);
        return now()->lessThan($limit);
    }
}
