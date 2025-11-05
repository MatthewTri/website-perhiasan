<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\GemmaController;

// Home Page
Route::get('/', function () {
    return view('home');
});

// Categories
Route::get('/categories/checkSlug', [CategoryController::class, 'checkSlug']);
Route::resource('categories', CategoryController::class);

// Products
Route::get('/products/checkSlug', [ProductController::class, 'checkSlug']);
Route::resource('products', ProductController::class);

// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');

// API untuk guest cart
Route::post('/api/cart/guest-items', [CartController::class, 'getGuestCartItems'])->name('cart.guest.items');

// Settings routes (only Admin access)
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Gemma AI route
Route::middleware(['auth'])->group(function () {
    // Halaman untuk menampilkan UI chat
    Route::get('/gemma', [GemmaController::class, 'index'])->name('gemma.index');
    // Endpoint yang akan dipanggil oleh AJAX
    Route::post('/gemma/generate', [GemmaController::class, 'generate'])->name('gemma.generate');
    Route::delete('/gemma/delete/{id}', [GemmaController::class, 'deleteHistory'])->name('gemma.delete');
});