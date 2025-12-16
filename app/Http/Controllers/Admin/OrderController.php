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
     * ACTION: Send Payment Details (BCA / PayPal)
     */
    public function sendPaymentDetails(Request $request, Order $order)
    {
        // Cek apakah order sudah dikirim payment details
        if ($order->status !== 'PENDING_REVIEW') {
            return back()->with('error', 'Payment details already sent or order status invalid!');
        }

        // Ambil payment gateway dari form atau gunakan preference customer
        $paymentGateway = $request->input('payment_gateway_override', $order->payment_preference);

        // Update order dengan payment details
        $order->update([
            'payment_gateway' => $paymentGateway,
            'payment_method' => $paymentGateway === 'MANUAL' ? 'BCA' : 'PAYPAL',
            'admin_notes' => $request->input('admin_notes'),
            'status' => 'INVOICE_SENT', // Status berubah
        ]);

        // TODO: Kirim email ke customer dengan payment details
        // if ($paymentGateway === 'MANUAL') {
        //     Mail::to($order->contact_email)->send(new BCAPaymentDetails($order));
        // } else {
        //     // Create PayPal invoice dan kirim link
        //     Mail::to($order->contact_email)->send(new PayPalInvoice($order));
        // }

        $message = $paymentGateway === 'MANUAL' 
            ? 'BCA payment details will be sent to customer email!' 
            : 'PayPal invoice will be sent to customer email!';

        return back()->with('success', $message);
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