<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WebhookController;



Route::post('/webhook/xendit', [WebhookController::class, 'handleXendit']);

Route::get('/reviews', [ReviewController::class, 'index']);

Route::get('/product/{product}/images', function (App\Models\Product $product) {
    // ✅ Debug: cek apakah ada images
    $images = $product->images()->orderBy('sort_order')->get();
    
    Log::info('Product Images Debug', [
        'product_id' => $product->id,
        'images_count' => $images->count(),
        'images_data' => $images->toArray()
    ]);
    
    // ✅ Jika tidak ada images, kembalikan featured image saja
    if ($images->isEmpty()) {
        $imageUrls = $product->featuredImage 
            ? [asset('storage/' . $product->featuredImage->image)]
            : [];
    } else {
        $imageUrls = $images->pluck('image')->map(function($image) {
            return asset('storage/' . $image);
        })->toArray();
    }
    
    return response()->json([
        'images' => $imageUrls,
        'count' => count($imageUrls)
    ]);
});
