<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with([
            'user', 
            'product:id,name,slug',
            'order:id',
            'order.shipment:id,order_id,first_name,last_name',
            'images' // Load review images
        ])
        ->where('is_visible', true)
        ->whereHas('product') // Only reviews with existing products
        ->latest()
        ->paginate(3)->onEachSide(1);

        return response()->json($reviews);
    }
    /**
     * Halaman Review via Magic Link (Support Guest & Member)
     * URL: /order/track/{token}/review
     */
    public function create($guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        // Validasi: Order harus COMPLETED
        if ($order->status !== 'COMPLETED') {
            return redirect()->route('order.track', $guestToken)
                        ->with('error', 'You can only review completed orders.');
        }

        // Validasi: Masih dalam masa review (30 hari)
        if (!$order->can_review) {
            return redirect()->route('order.track', $guestToken)
                        ->with('error', 'Review period has expired (30 days from completion).');
        }

        // Validasi: Sudah pernah review semua produk
        if ($order->is_reviewed) {
            return redirect()->route('order.track', $guestToken)
                        ->with('info', 'You have already submitted reviews for this order.');
        }

        // ✅ LOAD & GROUP BY PRODUCT
        $order->load(['orderItems.product.featuredImage', 'reviews']);
        
        // ✅ Group order items by product_id
        $groupedItems = $order->orderItems->groupBy('product_id')->map(function ($items) {
            $firstItem = $items->first();
            
            return (object)[
                'product' => $firstItem->product,
                'product_id' => $firstItem->product_id,
                'variants' => $items->map(function ($item) {
                    return [
                        'color' => $item->color,
                        'size' => $item->size,
                        'quantity' => $item->quantity
                    ];
                })->toArray(),
                'total_quantity' => $items->sum('quantity')
            ];
        })->values();

        return view('reviews.create', compact('order', 'groupedItems'));
    }

    /**
     * Simpan Review (Guest & Member sama)
     */
    public function store(Request $request, $guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        // Validasi order
        if ($order->status !== 'COMPLETED' || !$order->can_review || $order->is_reviewed) {
            return back()->with('error', 'Cannot submit review for this order.');
        }

        $validated = $request->validate([
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000',
            'reviews.*.images' => 'nullable|array|max:3', // Max 3 images
            'reviews.*.images.*' => 'image|mimes:jpeg,png,jpg|max:5120', // Each image max 5MB
            'reviews.*.anonymous' => 'nullable|boolean',
        ], [
            // Custom error messages
            'reviews.*.rating.required' => 'Please provide a rating for all products.',
            'reviews.*.rating.integer' => 'Rating must be a valid number.',
            'reviews.*.rating.min' => 'Rating must be at least 1 star.',
            'reviews.*.rating.max' => 'Rating cannot exceed 5 stars.',
            'reviews.*.comment.max' => 'Review comment cannot exceed 1000 characters.',
            'reviews.*.images.max' => 'You can upload a maximum of 3 images per product.',
            'reviews.*.images.*.image' => 'Uploaded file must be a valid image.',
            'reviews.*.images.*.mimes' => 'Images must be in JPEG, PNG, or JPG format.',
            'reviews.*.images.*.max' => 'Each image must not exceed 5MB.',
        ]);

        $reviewCount = 0;

        foreach ($validated['reviews'] as $reviewData) {
            $productId = $reviewData['product_id'];

            // Skip jika sudah pernah review produk ini di order ini
            if ($order->reviews()->where('product_id', $productId)->exists()) {
                continue;
            }

            // Validasi: Produk harus ada di order ini
            $orderItem = $order->orderItems()->where('product_id', $productId)->first();
            if (!$orderItem) {
                continue;
            }

            // Create Review
            $review = Review::create([
                'user_id' => $order->user_id, // Bisa null jika Guest
                'product_id' => $productId,
                'order_id' => $order->id,
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'] ?? null,
                'is_visible' => true,
                'anonymous' => $order->user_id ? ($reviewData['anonymous'] ?? false) : false,
            ]);

            // Handle Multiple Image Upload (Max 3)
            if (isset($reviewData['images']) && is_array($reviewData['images'])) {
                foreach ($reviewData['images'] as $index => $image) {
                    if ($index >= 3) break; // Max 3 images
                    
                    $imagePath = $image->store('reviews', 'public');
                    $review->images()->create([
                        'image' => $imagePath,
                        'order' => $index
                    ]);
                }
            }

            // ⬅️ UPDATE PRODUCT RATING AGGREGATE
            $orderItem->product->updateRatingAggregate();

            $reviewCount++;
        }

        if ($reviewCount > 0) {
            // ⬅️ MARK ORDER AS REVIEWED
            $order->update(['is_reviewed' => true]);

            return redirect()->route('order.track', $guestToken)
                           ->with('success', "Thank you! {$reviewCount} review(s) submitted successfully.");
        }

        return back()->with('error', 'Please provide at least one rating or comment.');
    }

    /**
     * Halaman Edit Review
     */
    public function edit($guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        // Validasi: Order harus COMPLETED dan sudah direview
        if ($order->status !== 'COMPLETED' || !$order->is_reviewed) {
            return redirect()->route('order.track', $guestToken)
                           ->with('error', 'No review found for this order.');
        }

        // Load order items dengan produk dan reviews
        $order->load(['orderItems.product.featuredImage', 'reviews.images']);

        return view('reviews.edit', compact('order'));
    }

    /**
     * Update Review
     */
    public function update(Request $request, $guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        // Validasi order
        if ($order->status !== 'COMPLETED' || !$order->is_reviewed) {
            return back()->with('error', 'Cannot update review for this order.');
        }

        $validated = $request->validate([
            'reviews' => 'required|array',
            'reviews.*.review_id' => 'required|exists:reviews,id',
            'reviews.*.rating' => 'nullable|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000',
            'reviews.*.images' => 'nullable|array|max:3', // Max 3 new images
            'reviews.*.images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'reviews.*.delete_images' => 'nullable|array', // Array of image IDs to delete
            'reviews.*.delete_images.*' => 'integer|exists:review_images,id',
            'reviews.*.anonymous' => 'nullable|boolean',
        ]);

        $updateCount = 0;

        foreach ($validated['reviews'] as $reviewData) {
            $review = Review::find($reviewData['review_id']);

            // Validasi: Review harus milik order ini
            if (!$review || $review->order_id !== $order->id) {
                continue;
            }

            // Skip jika tidak ada perubahan (rating & comment kosong)
            if (empty($reviewData['rating']) && empty($reviewData['comment'])) {
                continue;
            }

            // Handle Delete Images
            if (isset($reviewData['delete_images']) && is_array($reviewData['delete_images'])) {
                foreach ($reviewData['delete_images'] as $imageId) {
                    $reviewImage = $review->images()->find($imageId);
                    if ($reviewImage) {
                        Storage::disk('public')->delete($reviewImage->image);
                        $reviewImage->delete();
                    }
                }
            }

            // Handle New Image Upload (Max 3 total)
            if (isset($reviewData['images']) && is_array($reviewData['images'])) {
                $currentImageCount = $review->images()->count();
                $availableSlots = 3 - $currentImageCount;
                
                foreach ($reviewData['images'] as $index => $image) {
                    if ($index >= $availableSlots) break; // Respect max 3 total
                    
                    $imagePath = $image->store('reviews', 'public');
                    $review->images()->create([
                        'image' => $imagePath,
                        'order' => $currentImageCount + $index
                    ]);
                }
            }

            // Update review data
            $review->update([
                'rating' => $reviewData['rating'] ?? $review->rating,
                'comment' => $reviewData['comment'] ?? $review->comment,
                'anonymous' => $order->user_id ? ($reviewData['anonymous'] ?? false) : false,
            ]);

            // Update product rating aggregate
            $review->product->updateRatingAggregate();

            $updateCount++;
        }

        if ($updateCount > 0) {
            return redirect()->route('order.track', $guestToken)
                           ->with('success', "{$updateCount} review(s) updated successfully.");
        }

        return back()->with('error', 'No changes were made to your reviews.');
    }
}