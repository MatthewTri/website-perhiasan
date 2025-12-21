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
}
