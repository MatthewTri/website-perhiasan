<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'is_active',
        'subscribed_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
    ];

    /**
     * Scope for active subscribers only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Deactivate the subscription
     */
    public function deactivate()
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Activate the subscription
     */
    public function activate()
    {
        return $this->update(['is_active' => true]);
    }
}
