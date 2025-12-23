<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
                              ->with('children')
                              ->orderBy('name')
                              ->get();
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->orderBy('name')
                                   ->get();
        
        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')
                       ->with('success', 'Category successfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->where('id', '!=', $category->id)
                                   ->orderBy('name')
                                   ->get();
        
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // VALIDASI: Prevent circular reference
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent && $parent->parent_id == $category->id) {
                return back()->withErrors(['parent_id' => 'Cannot create circular reference']);
            }
        }

        $category->update($request->validated());

        return redirect()->route('categories.index')
                       ->with('success', 'Category successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // VALIDASI: Check if has children
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with sub-categories. Delete sub-categories first.');
        }

        // VALIDASI: Check if has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Move or delete products first.');
        }

        $category->delete();
        
        return redirect()->route('categories.index')
                       ->with('success', 'Category successfully deleted!');
    }

    public function checkSlug(Request $r) {
        $id = $r->id ?? null;
        // slug akan berisi dari title dan check di database dari Post::class apakah slugnya unique atau tidak
        $slug = SlugService::createSlug(Category::class, 'slug', $r->name, ['unique' => true, 'ignoreId' => $id]);
        return response()->json(['slug' => $slug]);
    }
}
