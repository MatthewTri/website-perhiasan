<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentDetailsMail;

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

        $allOrders = Order::with(['user', 'orderItems'])->get();

        $orders = $query->latest()->get();
        return view('admin.orders.index', compact('orders', 'allOrders'));
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
        if ($order->status !== 'PENDING_REVIEW') {
            return back()->with('error', 'Payment details already sent or order status invalid!');
        }

        $paymentGateway = $request->input('payment_gateway_override', $order->payment_preference);

        // ✅ JIKA PAYPAL, BUAT ORDER DULU
        if ($paymentGateway === 'PAYPAL') {
            $paypalResult = $this->paymentService->createPaypalOrder($order);
            
            if (!$paypalResult['success']) {
                return back()->with('error', 'Failed to create PayPal order: ' . $paypalResult['message']);
            }

            // Update order dengan PayPal data
            $order->update([
                'payment_gateway' => 'PAYPAL',
                'payment_method' => 'PAYPAL',
                'payment_url' => $paypalResult['redirect_url'],
                'transaction_id' => $paypalResult['transaction_id'],
                'admin_notes' => $request->input('admin_notes'),
                'status' => 'INVOICE_SENT',
            ]);

            $message = 'PayPal invoice created and sent to ' . $order->contact_email;
        } 
        // ✅ JIKA BCA MANUAL
        else {
            $order->update([
                'payment_gateway' => 'MANUAL',
                'payment_method' => 'BCA',
                'admin_notes' => $request->input('admin_notes'),
                'status' => 'INVOICE_SENT',
            ]);

            $message = 'BCA payment details sent to ' . $order->contact_email;
        }

        // ✅ KIRIM EMAIL
        $magicLink = route('order.track', $order->guest_token);
        
        try {
            Mail::to($order->contact_email)->send(new PaymentDetailsMail($order, $magicLink));
            
            return back()->with('success', $message . ' | Magic Link: ' . $magicLink);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Order updated but email failed: ' . $e->getMessage());
        }
    }

        /**
     * ACTION: Verify Payment Proof (Approve / Reject)
     */
    public function verifyPayment(Request $request, Order $order)
    {
        if ($order->status !== 'WAITING_VERIFICATION') {
            return back()->with('error', 'Invalid order status for payment verification!');
        }

        $action = $request->input('action'); // 'approve' atau 'reject'

        if ($action === 'approve') {
            // Validasi shipping input
            $validated = $request->validate([
                'courier_name' => 'required|string|max:100',
                'tracking_number' => 'required|string|max:100',
            ]);

            // Update order: PAID + SHIPPED
            $order->update([
                'status' => 'SHIPPED',
                'paid_at' => now(),
                'courier_name' => $validated['courier_name'],
                'tracking_number' => $validated['tracking_number'],
            ]);

            // TODO: Kirim email shipping notification
            // Mail::to($order->contact_email)->send(new OrderShipped($order));

            return back()->with('success', 'Payment approved! Order marked as SHIPPED. Tracking: ' . $validated['tracking_number']);
        } 
        
        elseif ($action === 'reject') {
            // Update status jadi CANCELLED
            $order->update([
                'status' => 'CANCELLED',
                'proof_of_payment' => null, // Hapus proof
            ]);

            // TODO: Kirim email payment rejection
            // Mail::to($order->contact_email)->send(new PaymentRejected($order));

            return back()->with('success', 'Payment rejected. Order has been CANCELLED.');
        }

        return back()->with('error', 'Invalid action!');
    }

    /**
     * ACTION: Update Resi (Shipping) - Untuk order yang sudah PAID tapi belum shipped
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