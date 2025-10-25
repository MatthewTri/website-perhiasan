<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use Sluggable;

    protected $fillable = ['name', 'slug', 'description', 'price', 'image', 'stock'];
    protected $with = ['category'];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
