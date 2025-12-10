<?php

namespace App\Http\Controllers\Admin; // Perhatikan Namespace ini

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
        // Filter Tab (Opsional tapi bagus)
        $status = $request->query('status');

        $query = Order::query();

        if ($status) {
            $query->where('status', $status);
        } else {
            // Default: Tampilkan yang butuh perhatian (Pending Review) dulu
            $query->orderByRaw("FIELD(status, 'PENDING_REVIEW', 'INVOICE_SENT', 'PAID', 'SHIPPED', 'COMPLETED')");
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Halaman Detail untuk Admin (Tempat Review & Buat Invoice).
     */
    public function show(Order $order)
    {
        // Admin boleh melihat order milik SIAPA SAJA
        // Load relasi user dan items
        $order->load('user', 'items', 'customRequest');

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

        // (Opsional) Kirim Email "Paket Dikirim" di sini

        return back()->with('success', 'Resi berhasil diupdate!');
    }
    
    // Method createInvoice() dll bisa ditaruh di sini juga atau di controller khusus
}