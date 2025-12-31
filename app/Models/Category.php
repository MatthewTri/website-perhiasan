<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory,Sluggable;

    protected $guarded = ['id'];

    // RELATIONSHIP: Parent Category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // RELATIONSHIP: Sub-Categories (Children)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    // HELPER: Check if category is parent (has children)
    public function isParent()
    {
        return $this->children()->count() > 0;
    }

    // HELPER: Check if category is child (has parent)
    public function isChild()
    {
        return $this->parent_id !== null;
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function totalProductsCount()
    {
        $count = $this->products()->count();
        foreach ($this->children as $child) {
            $count += $child->totalProductsCount();
        }
        return $count;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName() {
        return 'slug';
    }
}
