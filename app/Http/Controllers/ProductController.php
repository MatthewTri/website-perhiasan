<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'featuredImage'])->latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Create product
        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('product-images', $imageName, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_featured' => $index === 0, // First image as featured
                    'sort_order' => $index
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product successfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Update product data
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        // Handle deleted images (from frontend)
        if ($request->has('deleted_images') && $request->deleted_images) {
            $deletedImageIds = explode(',', $request->deleted_images);
            
            foreach ($deletedImageIds as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($image->image)) {
                        Storage::disk('public')->delete($image->image);
                    }
                    // Delete from database
                    $image->delete();
                }
            }
        }

        // Handle new images upload (ADD, not REPLACE)
        if ($request->hasFile('images')) {
            // Get current max sort_order
            $maxSortOrder = $product->images()->max('sort_order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . ($maxSortOrder + 1 + $index) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('product-images', $imageName, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_featured' => false, // New images are not featured by default
                    'sort_order' => $maxSortOrder + 1 + $index
                ]);
            }
        }

        // Reorder images and set featured image
        $this->reorderProductImages($product);

        return redirect()->route('products.index')->with('success', 'Product successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated images first
        if ($product->images()->exists()) {
            foreach ($product->images as $image) {
                // Delete file from storage
                if (Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                }
                // Delete from database
                $image->delete();
            }
        }
        
        // Delete the product
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Product successfully deleted!');
    }

    public function checkSlug(Request $r) {
        // slug akan berisi dari title dan check di database dari Post::class apakah slugnya unique atau tidak
        $slug = SlugService::createSlug(Product::class, 'slug', $r->name);
        return response()->json(['slug' => $slug]);
    }

    private function reorderProductImages(Product $product)
    {
        $images = $product->images()->orderBy('sort_order')->get();
        
        foreach ($images as $index => $image) {
            $image->update([
                'sort_order' => $index,
                'is_featured' => $index === 0 // First image is featured
            ]);
        }
    }
}
