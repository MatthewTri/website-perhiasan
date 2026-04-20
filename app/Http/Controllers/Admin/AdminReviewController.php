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
        $allowedPerPage = [5, 10, 20];
        $perPage = (int) $request->input('per_page', session('admin.reviews.per_page', 10));
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }
        session(['admin.reviews.per_page' => $perPage]);

        $query = Review::with(['user', 'product', 'order.shipment'])
            ->latest();

        // Filter by visibility
        if ($request->filled('visibility')) {
            if ($request->visibility === 'visible') {
                $query->where('is_visible', true);
            } elseif ($request->visibility === 'hidden') {
                $query->where('is_visible', false);
            }
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search by product name or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in product name
                $q->whereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                })
                // Search in order number
                ->orWhereHas('order', function($oq) use ($search) {
                    $oq->where('order_number', 'like', "%{$search}%");
                })
                // Search in customer name (via order.shipment)
                ->orWhereHas('order.shipment', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%");
                })
                // Search in review comment
                ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->paginate($perPage)->onEachSide(1)->withQueryString();

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.reviews.index', compact('reviews', 'perPage'))->render();
        }

        return view('admin.reviews.index', compact('reviews', 'perPage'));
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