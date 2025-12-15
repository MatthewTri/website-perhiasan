<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Menampilkan Daftar Semua Pesanan Masuk.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Order::with(['user', 'orderItems']);

        if ($status) {
            $query->where('status', $status);
        } else {
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
     * ACTION: Create Invoice (Xendit/PayPal)
     */
    public function createInvoice(Request $request, Order $order)
    {
        // Cek apakah order sudah ada invoice
        if ($order->status !== 'PENDING_REVIEW') {
            return back()->with('error', 'Invoice already created or order status invalid!');
        }

        $paymentGateway = $request->input('payment_gateway_override', $order->payment_preference);

        // Update payment_gateway dan admin_notes di order
        $order->update([
            'payment_gateway' => $paymentGateway,
            'admin_notes' => $request->input('admin_notes'),
        ]);
        
        // Create invoice berdasarkan payment gateway
        if ($order->payment_gateway === 'XENDIT') {
            $result = $this->paymentService->createXenditInvoice($order);
        } else {
            // PayPal implementation (nanti)
            return back()->with('error', 'PayPal integration not implemented yet!');
        }

        // Handle response
        if ($result['success']) {
            // Update order dengan invoice data
            $order->update([
                'status' => 'INVOICE_SENT',
                'transaction_id' => $result['invoice_id'],
                'payment_url' => $result['invoice_url'],
            ]);

            // TODO: Kirim email ke customer dengan payment link
            // Mail::to($order->contact_email)->send(new InvoiceCreated($order));

            return back()->with('success', 'Invoice created successfully! Payment link: ' . $result['invoice_url']);
        } else {
            return back()->with('error', 'Failed to create invoice: ' . $result['message']);
        }
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