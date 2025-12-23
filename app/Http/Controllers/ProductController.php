<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;


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
        // Kirim parent categories + semua categories (untuk mapping di frontend)
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        $allCategories = Category::with('parent')->orderBy('name')->get(); // Include parent untuk mapping
        
        return view('products.create', compact('parentCategories', 'allCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug',
            'description' => 'nullable|string',
            'price_idr' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            
            // ⬅️ VALIDASI COLORS
            'colors' => 'nullable|array',
            'colors.*.name' => 'required_with:colors|string|max:100',
            'colors.*.hex_code' => 'required_with:colors|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        try {
            DB::beginTransaction();

            // Create Product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'price_idr' => $validated['price_idr'],
                'price_usd' => $validated['price_usd'],
                'category_id' => $validated['category_id'],
            ]);

            // Upload Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'is_featured' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            // ⬅️ CREATE COLORS
            if ($request->has('colors') && is_array($request->colors)) {
                foreach ($request->colors as $index => $colorData) {
                    ProductColor::create([
                        'product_id' => $product->id,
                        'name' => $colorData['name'],
                        'hex_code' => strtoupper($colorData['hex_code']), // Normalize to uppercase
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('products.index')
                        ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()
                    ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
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
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        $allCategories = Category::with('parent')->orderBy('name')->get();
        
        // Determine parent and sub-category
        $selectedParentId = $product->category->parent_id ?? $product->category_id;
        $selectedSubCategoryId = $product->category->parent_id ? $product->category_id : null;
        
        return view('products.edit', compact('product', 'parentCategories', 'allCategories', 'selectedParentId', 'selectedSubCategoryId'));
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
            'price_idr' => $request->price_idr,
            'price_usd' => $request->price_usd,
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
