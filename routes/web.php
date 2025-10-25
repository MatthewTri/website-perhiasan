<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('home');
});

Route::get('/categories/checkSlug', [CategoryController::class, 'checkSlug']);
Route::resource('categories', CategoryController::class);

Route::get('/products/checkSlug', [ProductController::class, 'checkSlug']);
