<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'section_title', 'items', 'sort_order'];
    
    // ⬅️ CAST items sebagai array (JSON)
    protected $casts = [
        'items' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}