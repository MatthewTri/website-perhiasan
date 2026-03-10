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

    public function sizes()
    {
        return $this->hasMany(ProductSize::class)->orderBy('sort_order');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class)->orderBy('sort_order');
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope untuk Best Sellers - produk dengan penjualan terbanyak
     * Bisa dikombinasi dengan filter rating minimal
     */
    public function scopeBestSellers($query, $minRating = null, $limit = 16)
    {
        $query->withCount(['orderItems as total_sold' => function($q) {
                // Hitung total quantity yang terjual dari orders yang sukses/completed
                $q->selectRaw('COALESCE(SUM(quantity), 0)')
                  ->whereHas('order', function($orderQuery) {
                      $orderQuery->whereIn('status', ['completed', 'delivered', 'shipped']);
                  });
            }])
            ->having('total_sold', '>', 0); // Hanya produk yang ada penjualannya
        
        // Opsional: Filter rating minimal (misal: minimal 4.0)
        if ($minRating) {
            $query->where('rating_avg', '>=', $minRating);
        }
        
        return $query->orderByDesc('total_sold')
                     ->limit($limit);
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

    /**
     * Get formatted price based on global currency
     */
    public function getFormattedPriceAttribute()
    {
        $currency = session('currency', 'IDR');
        
        if ($currency === 'USD') {
            return '$' . number_format($this->price_usd, 2);
        }
        
        return 'Rp ' . number_format($this->price_idr, 0, ',', '.');
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->rating_avg ?? 0;
    }

    /**
     * Get reviews count
     */
    public function getReviewsCountAttribute()
    {
        return $this->rating_count ?? 0;
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
