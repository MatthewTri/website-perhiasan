<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\GemmaController;
use App\Http\Controllers\CustomRequestController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AdminReviewController;


// Home Page
Route::get('/', function () {
    return view('home');
})->name('home');

// Currency Switcher (Public Access)
Route::post('/currency/switch', [CurrencyController::class, 'switch'])->name('currency.switch');

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
// Update quantity cart item
Route::patch('/cart/update/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');

// API untuk guest cart
Route::post('/api/cart/guest-items', [CartController::class, 'getGuestCartItems'])->name('cart.guest.items');
// Merge guest cart to user login
Route::post('/cart/migrate', [CartController::class, 'migrateGuestCart'])->name('cart.migrate');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Settings routes (only Admin access)
Route::middleware(['auth', 'admin'])->group(function () {
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

// Consultation routes
Route::get('/consultation', [CustomRequestController::class, 'index'])->name('consultation.index');
Route::get('/consultation/select-datetime', [CustomRequestController::class, 'create'])->name('consultation.create');
Route::get('/consultation/details', [CustomRequestController::class, 'showDetails'])->name('consultation.details');
Route::post('/consultation', [CustomRequestController::class, 'store'])->name('consultation.store');

// API untuk cek ketersediaan slot konsultasi
Route::get('/api/consultation/availability', [CustomRequestController::class, 'checkAvailability'])->name('consultation.availability');


// Admin routes (tambahkan middleware admin nanti)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/consultation', [App\Http\Controllers\Admin\ConsultationController::class, 'index'])->name('consultation.index');
    Route::get('/consultation/{consultation}', [App\Http\Controllers\Admin\ConsultationController::class, 'show'])->name('consultation.show');
    Route::patch('/consultation/{consultation}/status', [App\Http\Controllers\Admin\ConsultationController::class, 'updateStatus'])->name('consultation.updateStatus');
    Route::patch('/consultation/{consultation}/reschedule', [App\Http\Controllers\Admin\ConsultationController::class, 'reschedule'])->name('consultation.reschedule');
    Route::delete('/consultation/{consultation}', [App\Http\Controllers\Admin\ConsultationController::class, 'destroy'])->name('consultation.destroy');
    Route::post('/consultation/bulk-action', [App\Http\Controllers\Admin\ConsultationController::class, 'bulkAction'])->name('consultation.bulkAction');
});

// Admin Order Management routes
Route::middleware(['auth' ,'admin'])->group(function () { // tambahkan middleware admin nanti
    Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{order}/update-shipping', [App\Http\Controllers\Admin\OrderController::class, 'updateShipping'])->name('admin.orders.updateShipping');
    Route::post('/admin/orders/{order}/process-refund', [App\Http\Controllers\Admin\OrderController::class, 'processRefund'])->name('admin.orders.processRefund');
});

// Member Orders (Must be authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    // Route::get('/my-orders/{order:slug}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin Orders
Route::prefix('admin')->name('admin.')->middleware(['auth' ,'admin'])->group(function () {
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    Route::post('orders/{order}/send-payment-details', [AdminOrderController::class, 'sendPaymentDetails'])->name('orders.sendPaymentDetails');
    Route::post('orders/{order}/update-shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.updateShipping');
    Route::post('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verifyPayment');
});

// Route Magic Link (Public Access)
Route::get('/order/track/{token}', [OrderTrackingController::class, 'show'])->name('order.track');
Route::post('/order/track/{token}/upload', [OrderTrackingController::class, 'uploadProof'])->name('order.track.upload');
Route::get('/order/track/{guestToken}/paypal/checkout', [OrderTrackingController::class, 'createPaypalCheckout'])
    ->name('order.track.paypal.checkout');
Route::post('/order/track/{token}/complete', [OrderTrackingController::class, 'completeOrder'])->name('order.track.complete');


// PayPal Payment Callbacks
Route::get('/payment/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
Route::get('/payment/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('payment.paypal.cancel');

Route::post('/webhook/paypal', [PaymentController::class, 'paypalWebhook'])->name('webhook.paypal');
Route::get('/webhook/paypal', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'PayPal webhook endpoint is ready',
        'timestamp' => now()
    ]);
})->name('webhook.paypal.test');

// Revew Routes via magic link
Route::get('/order/track/{token}/review', [ReviewController::class, 'create'])->name('order.track.review');
Route::post('/order/track/{token}/review', [ReviewController::class, 'store'])->name('order.track.review.store');
Route::get('/order/track/{token}/review/edit', [ReviewController::class, 'edit'])->name('order.track.review.edit');
Route::put('/order/track/{token}/review/update', [ReviewController::class, 'update'])->name('order.track.review.update');


// Admin Review Management
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function() { // tambahkan middleware admin nanti
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/toggle', [AdminReviewController::class, 'toggleVisibility'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});