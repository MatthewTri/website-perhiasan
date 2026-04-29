<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductSpecification;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProductRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Best Sellers Only)
     */
    public function index(Request $request)
    {
        $currentSort = $request->get('sort', 'featured');

        $search = trim((string) $request->get('q', ''));

        $query = Product::with([
            'category',
            'featuredImage',
            'colors:id,product_id,name,hex_code,sort_order'
        ]);

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($currentSort === 'featured') {
            // Featured: prioritize best sellers but still show products with 0 sales
            $query->withCount(['orderItems as total_sold' => function ($q) {
                    $q->selectRaw('COALESCE(SUM(quantity), 0)')
                      ->whereHas('order', function ($o) {
                          $o->whereIn('status', ['COMPLETED', 'SHIPPED', 'PAID', 'PRODUCTION', 'DELIVERED', 'completed', 'shipped', 'paid', 'production', 'delivered']);
                      });
                }])
                ->orderByDesc('total_sold')
                ->orderBy('created_at');
        } else {
            $query = $this->applySortQuery($query, $currentSort);
        }

        $products = $query->paginate(36)->withQueryString();

        $pageTitle = 'Best Sellers';
        $pageSubtitle = 'Our most loved pieces';

        return view('products.index', compact('products', 'pageTitle', 'pageSubtitle', 'currentSort', 'search'));
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
            'parent_category_id' => 'required|exists:categories,id',
            'category_id' => 'nullable|exists:categories,id',
            'images' => 'array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            
            // VALIDASI COLORS
            'colors' => 'nullable|array',
            'colors.*.name' => 'required_with:colors|string|max:100',
            'colors.*.hex_code' => 'required_with:colors|string|regex:/^#[0-9A-Fa-f]{6}$/',

            // VALIDASI SIZES
            'sizes' => 'nullable|array',
            'sizes.*.size_name' => 'required_with:sizes|string|max:50',

            // VALIDASI SPECIFICATIONS
            'specifications' => 'nullable|array',
            'specifications.*.section_title' => 'required_with:specifications|string|max:255',
            'specifications.*.items' => 'required_with:specifications|array|min:1',
            'specifications.*.items.*' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // TAMBAHKAN LOGIC: Jika tidak ada sub-category, gunakan parent sebagai category_id
            $categoryId = $validated['category_id'] ?? $validated['parent_category_id'];

            // Create Product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'price_idr' => $validated['price_idr'],
                'price_usd' => $validated['price_usd'],
                'category_id' => $categoryId,
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

            // CREATE SIZES
            if ($request->has('sizes') && is_array($request->sizes)) {
                foreach ($request->sizes as $index => $sizeData) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_name' => $sizeData['size_name'],
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
        'sizes' => fn($q) => $q->orderBy('sort_order'),
        'specifications' => fn($q) => $q->orderBy('sort_order'),
        'category'
    ]);
    
    // Get related products (max 4) - prioritize same category
    $relatedProducts = Product::where('id', '!=', $product->id)
        ->where('category_id', $product->category_id)
        ->with([
            'featuredImage',
            'colors:id,product_id,name,hex_code,sort_order'
        ])
        ->inRandomOrder()
        ->limit(4)
        ->get();
    
    // If less than 4, fill with other random products
    if ($relatedProducts->count() < 4) {
        $remaining = 4 - $relatedProducts->count();
        $excludeIds = $relatedProducts->pluck('id')->push($product->id)->toArray();
        
        $otherProducts = Product::whereNotIn('id', $excludeIds)
            ->with([
                'featuredImage',
                'colors:id,product_id,name,hex_code,sort_order'
            ])
            ->inRandomOrder()
            ->limit($remaining)
            ->get();
        
        $relatedProducts = $relatedProducts->merge($otherProducts);
    }
    
    return view('products.show', compact('product', 'relatedProducts'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        $allCategories = Category::with('parent')->orderBy('name')->get();
        
        // Load all relationships including sizes
        $product->load(['images', 'colors', 'sizes', 'specifications']);
        
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
        $originalPriceIdr = $product->price_idr;
        $originalPriceUsd = $product->price_usd;

        // Update product data
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price_idr' => $request->price_idr,
            'price_usd' => $request->price_usd,
            'category_id' => $request->category_id,
        ]);

        $priceIdrChanged = round((float) $originalPriceIdr, 2) !== round((float) $request->price_idr, 2);
        $priceUsdChanged = round((float) $originalPriceUsd, 2) !== round((float) $request->price_usd, 2);
        if ($priceIdrChanged || $priceUsdChanged) {
            $product->last_price_change_at = now();
            $product->save();
        }

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

        // HANDLE SIZE DELETION
        if ($request->has('deleted_sizes') && $request->deleted_sizes) {
            $deletedSizeIds = explode(',', $request->deleted_sizes);
            ProductSize::whereIn('id', $deletedSizeIds)
                    ->where('product_id', $product->id)
                    ->delete();
        }

        // HANDLE SIZE UPDATE & CREATE
        if ($request->has('sizes') && is_array($request->sizes)) {
            foreach ($request->sizes as $index => $sizeData) {
                if (isset($sizeData['id']) && !empty($sizeData['id'])) {
                    // UPDATE EXISTING SIZE
                    ProductSize::where('id', $sizeData['id'])
                            ->where('product_id', $product->id)
                            ->update([
                                'size_name' => $sizeData['size_name'],
                                'sort_order' => $index,
                            ]);
                } else {
                    // CREATE NEW SIZE
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_name' => $sizeData['size_name'],
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
                'is_featured' => $index === 0
            ]);
        }
    }

    private function applySortQuery($query, string $sort)
    {
        switch ($sort) {
            case 'best_selling':
                return $query->withCount(['orderItems as total_sold' => function ($q) {
                    $q->selectRaw('COALESCE(SUM(quantity), 0)')
                      ->whereHas('order', function ($o) {
                          $o->whereIn('status', ['COMPLETED', 'SHIPPED', 'PAID', 'PRODUCTION', 'completed', 'shipped']);
                      });
                }])->orderByDesc('total_sold');
            case 'alpha_asc':
                return $query->orderBy('name', 'asc');
            case 'alpha_desc':
                return $query->orderBy('name', 'desc');
            case 'price_asc':
                return $query->orderBy('price_idr', 'asc');
            case 'price_desc':
                return $query->orderBy('price_idr', 'desc');
            case 'date_asc':
                return $query->orderBy('created_at', 'asc');
            default: // date_desc
                return $query->orderBy('created_at', 'desc');
        }
    }
}
