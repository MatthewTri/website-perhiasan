<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders.
     */
    public function index()
    {
        // Ambil semua order milik user yang login
        $orders = Order::with(['orderItems.product.images'])
                       ->where('user_id', Auth::id())
                       ->latest()
                       ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order (member view).
     */
    public function show(Order $order)
    {
        // Security check: Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // ⬅️ REDIRECT KE MAGIC LINK TRACKING
        return redirect()->route('order.track', $order->guest_token);
    }

    /**
     * Complete order from index page
     */
    public function complete($guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        // Security check: Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Validasi: Hanya bisa complete jika status SHIPPED
        if ($order->status !== 'SHIPPED') {
            return redirect()->route('orders.index')
                           ->with('error', 'This order cannot be completed at this time.');
        }

        // Update status ke COMPLETED
        $order->update([
            'status' => 'COMPLETED',
            'completed_at' => now(),
        ]);

        // ⬅️ REDIRECT KE HALAMAN REVIEW
        return redirect()->route('order.track.review', $order->guest_token)
                       ->with('success', 'Order completed! Please share your experience by writing a review.');
    }
}
