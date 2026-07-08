<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
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
use App\Http\Controllers\Admin\CalendarSettingController;
use App\Http\Controllers\SitemapController;


// Home Page
Route::get('/', function () {
    return view('home');
})->name('home');

// About us Page
Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Currency Switcher (Public Access)
Route::post('/currency/switch', [CurrencyController::class, 'switch'])->name('currency.switch');

// Legal pages
Route::view('/terms', 'policies.terms')->name('policies.terms');
Route::view('/privacy-policy', 'policies.privacy')->name('policies.privacy');
Route::view('/refund-policy', 'policies.refund')->name('policies.refund');
Route::view('/shipping-policy', 'policies.shipping')->name('policies.shipping');

// Categories
Route::get('/categories/checkSlug', [CategoryController::class, 'checkSlug']);
Route::resource('categories', CategoryController::class);

// Products
Route::get('/products/checkSlug', [ProductController::class, 'checkSlug']);
Route::resource('products', ProductController::class);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products/import', [ProductController::class, 'importForm'])->name('products.import');
    Route::post('/products/import', [ProductController::class, 'importStore'])->name('products.import.store');
});

// Register (Guest only - redirect to home if already logged in)
Route::get('/register', [RegisterController::class, 'index'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.store');

// Login (Guest only - redirect to home if already logged in)
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest')->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password & Password Reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');
    
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');
    
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

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

Route::get('/gemma', [GemmaController::class, 'index'])->name('gemma.index');
// Gemma AI route
Route::middleware(['auth'])->group(function () {
    // Halaman untuk menampilkan UI chat
    // Endpoint yang akan dipanggil oleh AJAX
    Route::post('/gemma/generate', [GemmaController::class, 'generate'])->name('gemma.generate');
    Route::post('/gemma/edit', [GemmaController::class, 'edit'])->name('gemma.edit');
    Route::delete('/gemma/delete/{id}', [GemmaController::class, 'deleteHistory'])->name('gemma.delete');
});

// Consultation routes
Route::get('/consultation', [CustomRequestController::class, 'index'])->name('consultation.index');
Route::get('/consultation/select-datetime', [CustomRequestController::class, 'create'])->name('consultation.create');
Route::get('/consultation/details', [CustomRequestController::class, 'showDetails'])->name('consultation.details');
Route::post('/consultation', [CustomRequestController::class, 'store'])->name('consultation.store');
Route::get('/consultation/unavailable-dates', [CustomRequestController::class, 'getUnavailableDates'])->name('consultation.unavailableDates');

// API untuk cek ketersediaan slot konsultasi
Route::get('/api/consultation/availability', [CustomRequestController::class, 'checkAvailability'])->name('consultation.availability');


// Admin routes (tambahkan middleware admin nanti)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/consultation', [App\Http\Controllers\Admin\ConsultationController::class, 'index'])->name('consultation.index');
    Route::get('/consultation/{consultation}', [App\Http\Controllers\Admin\ConsultationController::class, 'show'])->name('consultation.show');
    Route::patch('/consultation/{consultation}/status', [App\Http\Controllers\Admin\ConsultationController::class, 'updateStatus'])->name('consultation.updateStatus');
    Route::patch('/consultation/{consultation}/reschedule', [App\Http\Controllers\Admin\ConsultationController::class, 'reschedule'])->name('consultation.reschedule');
    Route::post('/consultation/bulk-action', [App\Http\Controllers\Admin\ConsultationController::class, 'bulkAction'])->name('consultation.bulkAction');
});

// Admin Order Management routes
Route::middleware(['auth' ,'admin'])->group(function () { // tambahkan middleware admin nanti
    Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{order}/upload-proof', [App\Http\Controllers\Admin\OrderController::class, 'uploadProof'])->name('admin.orders.uploadProof');
    Route::post('/admin/orders/{order}/update-shipping', [App\Http\Controllers\Admin\OrderController::class, 'updateShipping'])->name('admin.orders.updateShipping');
    Route::post('/admin/orders/{order}/process-refund', [App\Http\Controllers\Admin\OrderController::class, 'processRefund'])->name('admin.orders.processRefund');
});

// Member Orders (Must be authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/my-orders/{guestToken}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    // Route::get('/my-orders/{order:slug}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin Orders
Route::prefix('admin')->name('admin.')->middleware(['auth' ,'admin'])->group(function () {
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    Route::post('orders/{order}/send-payment-details', [AdminOrderController::class, 'sendPaymentDetails'])->name('orders.sendPaymentDetails');
    Route::post('orders/{order}/update-shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.updateShipping');
    Route::post('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verifyPayment');
    Route::post('orders/{order}/process-order', [AdminOrderController::class, 'processOrder'])->name('orders.processOrder');
    Route::post('orders/{order}/cancel', [AdminOrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('orders/{order}/complete', [AdminOrderController::class, 'completeOrder'])->name('orders.complete');
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

Route::post('/webhook/paypal', [PaymentController::class, 'paypalWebhook'])
    ->middleware('throttle:300,1')
    ->name('webhook.paypal');

// Optional: local-only health check endpoint (avoid exposing publicly in production)
if (app()->environment('local')) {
    Route::get('/webhook/paypal', function () {
        return response()->json([
            'status' => 'ok',
            'message' => 'PayPal webhook endpoint is ready',
            'timestamp' => now()
        ]);
    })->name('webhook.paypal.test');
}

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

// Admin Calendar Settings
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/calendar-settings', [CalendarSettingController::class, 'index'])->name('calendar.settings');
    Route::post('/calendar-settings/weekly-hours', [CalendarSettingController::class, 'updateWeeklyHours'])->name('calendar.settings.weeklyHours');
    Route::get('/calendar-settings/booked', [CalendarSettingController::class, 'getBookedSlots'])->name('calendar.settings.booked');
    Route::get('/calendar-settings/disabled', [CalendarSettingController::class, 'getDisabledSlots'])->name('calendar.settings.disabled');
    Route::get('/calendar-settings/unavailable-dates', [CalendarSettingController::class, 'getUnavailableDates'])->name('calendar.settings.unavailableDates');
    Route::post('/calendar-settings/toggle', [CalendarSettingController::class, 'toggleSlot'])->name('calendar.settings.toggle');
});

// Newsletter Subscription Routes (Public)
Route::post('/subscribe', [App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
Route::get('/unsubscribe', [App\Http\Controllers\SubscriptionController::class, 'unsubscribe'])->name('subscription.unsubscribe');

// Admin Newsletter Management
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Subscriber management
    Route::get('/subscribers', [App\Http\Controllers\Admin\SubscriberController::class, 'index'])->name('subscribers.index');
    Route::delete('/subscribers/{subscriber}', [App\Http\Controllers\Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy');
    
    // Broadcast management
    Route::get('/broadcasts/create', [App\Http\Controllers\Admin\BroadcastController::class, 'create'])->name('broadcasts.create');
    Route::post('/broadcasts/send', [App\Http\Controllers\Admin\BroadcastController::class, 'send'])->name('broadcasts.send');
});