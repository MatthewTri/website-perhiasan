<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use Sluggable;

    protected $fillable = ['name', 'slug', 'description', 'price_idr', 'price_usd', 'image', 'category_id',  'rating_avg', 'rating_count'];
    protected $with = ['category'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        // Satu Produk 'hasMany' (punya banyak) Gambar
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Shortcut untuk mengambil HANYA gambar utama (thumbnail).
     */
    public function featuredImage()
    {
        // Ambil satu gambar yang ditandai 'is_featured'
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_visible', true)->latest();
    }

    public function updateRatingAggregate()
    {
        // HANYA HITUNG REVIEW YANG VISIBLE
        $avgRating = $this->reviews()
            ->where('is_visible', true)
            ->avg('rating') ?? 0;
            
        $countRating = $this->reviews()
            ->where('is_visible', true)
            ->count();

        $this->update([
            'rating_avg' => round($avgRating, 2),
            'rating_count' => $countRating
        ]);
    }
    
    public function getRouteKeyName() {
        return 'slug';
    }

     public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
