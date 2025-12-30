<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductColor;
use App\Models\ProductSpecification;
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
        $products = Product::with([
            'category', 
            'featuredImage', 
            'colors:id,product_id,name,hex_code,sort_order'  // ✅ Select only needed columns
        ])->latest()->get();
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
            'images' => 'array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            
            // VALIDASI COLORS
            'colors' => 'nullable|array',
            'colors.*.name' => 'required_with:colors|string|max:100',
            'colors.*.hex_code' => 'required_with:colors|string|regex:/^#[0-9A-Fa-f]{6}$/',

            // VALIDASI SPECIFICATIONS
            'specifications' => 'nullable|array',
            'specifications.*.section_title' => 'required_with:specifications|string|max:255',
            'specifications.*.items' => 'required_with:specifications|array|min:1',
            'specifications.*.items.*' => 'required|string|max:500',
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
                    $path = $file->store('product-images', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'is_featured' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            // CREATE COLORS
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

            // CREATE SPECIFICATIONS
            if ($request->has('specifications') && is_array($request->specifications)) {
                foreach ($request->specifications as $index => $specData) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'section_title' => $specData['section_title'],
                        'items' => array_filter($specData['items']), // Remove empty items
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
    $product->load([
        'images' => fn($q) => $q->orderBy('sort_order'),
        'colors' => fn($q) => $q->orderBy('sort_order'),
        'specifications' => fn($q) => $q->orderBy('sort_order'),
        'category'
    ]);
    
    return view('products.show', compact('product'));
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

        // ⬅️ HANDLE COLOR DELETION
        if ($request->has('deleted_colors') && $request->deleted_colors) {
            $deletedColorIds = explode(',', $request->deleted_colors);
            ProductColor::whereIn('id', $deletedColorIds)
                       ->where('product_id', $product->id)
                       ->delete();
        }

        // ⬅️ HANDLE COLOR UPDATE & CREATE
        if ($request->has('colors') && is_array($request->colors)) {
            foreach ($request->colors as $index => $colorData) {
                if (isset($colorData['id']) && !empty($colorData['id'])) {
                    // ⬅️ UPDATE EXISTING COLOR
                    ProductColor::where('id', $colorData['id'])
                               ->where('product_id', $product->id)
                               ->update([
                                   'name' => $colorData['name'],
                                   'hex_code' => strtoupper($colorData['hex_code']),
                                   'sort_order' => $index,
                               ]);
                } else {
                    // ⬅️ CREATE NEW COLOR
                    ProductColor::create([
                        'product_id' => $product->id,
                        'name' => $colorData['name'],
                        'hex_code' => strtoupper($colorData['hex_code']),
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        // ⬅️ HANDLE SPECIFICATION DELETION
        if ($request->has('deleted_specifications') && $request->deleted_specifications) {
            $deletedSpecIds = explode(',', $request->deleted_specifications);
            ProductSpecification::whereIn('id', $deletedSpecIds)
                               ->where('product_id', $product->id)
                               ->delete();
        }

        // ⬅️ HANDLE SPECIFICATION UPDATE & CREATE
        if ($request->has('specifications') && is_array($request->specifications)) {
            foreach ($request->specifications as $index => $specData) {
                if (isset($specData['id']) && !empty($specData['id'])) {
                    // UPDATE EXISTING SPECIFICATION
                    ProductSpecification::where('id', $specData['id'])
                                       ->where('product_id', $product->id)
                                       ->update([
                                           'section_title' => $specData['section_title'],
                                           'items' => array_filter($specData['items']),
                                           'sort_order' => $index,
                                       ]);
                } else {
                    // CREATE NEW SPECIFICATION
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'section_title' => $specData['section_title'],
                        'items' => array_filter($specData['items']),
                        'sort_order' => $index,
                    ]);
                }
            }
        }

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
        // ⬅️ TERIMA ID PARAMETER (untuk edit mode)
        $id = $r->id ?? null;
        
        // ⬅️ GENERATE SLUG dengan ignore ID yang sedang diedit
        $slug = SlugService::createSlug(Product::class, 'slug', $r->name, ['unique' => true, 'ignoreId' => $id]);
        
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
