<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('home');
});

Route::get('/categories/checkSlug', [CategoryController::class, 'checkSlug']);
Route::resource('categories', CategoryController::class);
