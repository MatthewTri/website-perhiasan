<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan Daftar Semua Pesanan Masuk.
     */
    public function index(Request $request)
    {
        // Filter Tab (Opsional)
        $status = $request->query('status');

        $query = Order::with(['user', 'orderItems']);

        if ($status) {
            $query->where('status', $status);
        } else {
            // Default: Urutkan berdasarkan prioritas status
            $query->orderByRaw("FIELD(status, 'PENDING_REVIEW', 'INVOICE_SENT', 'PAID', 'SHIPPED', 'COMPLETED', 'CANCELLED')");
        }

        $orders = $query->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Halaman Detail untuk Admin (Tempat Review & Buat Invoice).
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product', 'customRequest');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * ACTION: Update Resi (Shipping).
     */
    public function updateShipping(Request $request, Order $order)
    {
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'tracking_number' => 'required|string',
        ]);

        $order->update([
            'courier_name' => $validated['courier_name'],
            'tracking_number' => $validated['tracking_number'],
            'status' => 'SHIPPED'
        ]);

        return back()->with('success', 'Tracking information updated successfully!');
    }
}