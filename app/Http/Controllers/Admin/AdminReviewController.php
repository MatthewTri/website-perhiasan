<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminReviewController extends Controller
{
    /**
     * Display list of all reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'order'])
            ->latest();

        // Filter by visibility
        if ($request->has('visibility')) {
            if ($request->visibility === 'visible') {
                $query->where('is_visible', true);
            } elseif ($request->visibility === 'hidden') {
                $query->where('is_visible', false);
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating !== '') {
            $query->where('rating', $request->rating);
        }

        // Search by product name or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('order', function($oq) use ($search) {
                    $oq->where('shipping_name', 'like', "%{$search}%")
                       ->orWhere('order_number', 'like', "%{$search}%");
                });
            });
        }

        $reviews = $query->paginate(1)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Toggle visibility
     */
    public function toggleVisibility(Review $review)
    {
        $review->update([
            'is_visible' => !$review->is_visible
        ]);

        // Update product rating aggregate
        $review->product->updateRatingAggregate();

        $status = $review->is_visible ? 'visible' : 'hidden';
        
        return back()->with('success', "Review has been marked as {$status}.");
    }

    /**
     * Delete review
     */
    // public function destroy(Review $review)
    // {
    //     // Delete image if exists
    //     if ($review->image) {
    //         Storage::disk('public')->delete($review->image);
    //     }

    //     $productId = $review->product_id;
    //     $review->delete();

    //     // Update product rating aggregate
    //     $product = \App\Models\Product::find($productId);
    //     if ($product) {
    //         $product->updateRatingAggregate();
    //     }

    //     return back()->with('success', 'Review deleted successfully.');
    // }
}