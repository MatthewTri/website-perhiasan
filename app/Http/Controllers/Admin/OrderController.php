<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\Order\PaymentDetailsMail;
use App\Mail\Order\OrderShippedMail;
use App\Mail\Order\PaymentRejectedMail;
use App\Mail\Order\OrderCancelledMail;
use App\Mail\Order\OrderCompletedMail;
use App\Mail\Order\RefundProcessedMail;
use App\Mail\Order\OrderInProductionMail;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


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
        $paypalEnabled = Setting::isPaypalEnabled();

        $allowedPerPage = [5, 10, 20];
        $perPage = (int) $request->input('per_page', session('admin.orders.per_page', 10));
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }
        session(['admin.orders.per_page' => $perPage]);

        $query = Order::query()
            ->with(['user', 'shipment'])
            ->withSum('orderItems as items_quantity', 'quantity');
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment gateway
        if ($request->has('payment_gateway') && $request->payment_gateway != '') {
            $query->where('payment_gateway', $request->payment_gateway);
        }
        
        // Search by order number, customer name, or email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('contact_email', 'like', '%' . $search . '%')
                  ->orWhereHas('shipment', function($sq) use ($search) {
                      $sq->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Stats only needed for full page render (AJAX only updates the table)
        $stats = null;
        if (!$request->ajax()) {
            $stats = Order::query()
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN status = 'PENDING_REVIEW' THEN 1 ELSE 0 END) as pending")
                ->selectRaw($paypalEnabled
                    ? "SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as paypal_pending"
                    : "0 as paypal_pending"
                )
                ->selectRaw("SUM(CASE WHEN status = 'INVOICE_SENT' THEN 1 ELSE 0 END) as invoiced")
                ->selectRaw("SUM(CASE WHEN status = 'WAITING_VERIFICATION' THEN 1 ELSE 0 END) as verifying")
                ->selectRaw("SUM(CASE WHEN status = 'DENIED' THEN 1 ELSE 0 END) as denied")
                ->selectRaw("SUM(CASE WHEN status = 'REVERSED' THEN 1 ELSE 0 END) as reversed")
                ->selectRaw("SUM(CASE WHEN status = 'DISPUTED' THEN 1 ELSE 0 END) as disputed")
                ->selectRaw("SUM(CASE WHEN status = 'PAID' THEN 1 ELSE 0 END) as paid")
                ->selectRaw("SUM(CASE WHEN status = 'PRODUCTION' THEN 1 ELSE 0 END) as production")
                ->selectRaw("SUM(CASE WHEN status = 'SHIPPED' THEN 1 ELSE 0 END) as shipped")
                ->selectRaw("SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed")
                ->selectRaw("SUM(CASE WHEN status IN ('CANCELLED', 'REFUNDED') THEN 1 ELSE 0 END) as cancelled_or_ref")
                ->first();
        }
        
        // Order by priority if no specific status filter
        if (!$request->has('status') || $request->status == '') {
            $query->orderByRaw($paypalEnabled
                ? "FIELD(status, 'PENDING_REVIEW', 'PENDING', 'WAITING_VERIFICATION', 'INVOICE_SENT', 'DENIED', 'DISPUTED', 'REVERSED', 'PAID', 'PRODUCTION', 'SHIPPED', 'COMPLETED', 'CANCELLED', 'REFUNDED')"
                : "FIELD(status, 'PENDING_REVIEW', 'WAITING_VERIFICATION', 'INVOICE_SENT', 'DENIED', 'DISPUTED', 'REVERSED', 'PAID', 'PRODUCTION', 'SHIPPED', 'COMPLETED', 'CANCELLED', 'REFUNDED')"
            );
        }
        
        $orders = $query->latest()->paginate($perPage)->onEachSide(1)->withQueryString();
        
        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.orders.partials.table', compact('orders'))->render();
            $paginationHtml = view('admin.orders.partials.pagination', compact('orders'))->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $paginationHtml
            ]);
        }

        return view('admin.orders.index', compact('orders', 'stats', 'perPage', 'paypalEnabled'));
    }

    /**
     * Halaman Detail untuk Admin (Tempat Review & Buat Invoice).
     */
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product','shipment');
        $paypalEnabled = Setting::isPaypalEnabled();
        $paypalStatus = null;
        
        // ⬅️ HANYA FETCH PAYPAL STATUS JIKA ADA TRANSACTION_ID
        if ($paypalEnabled && $order->payment_gateway === 'PAYPAL' && $order->transaction_id) {
            try {
                $paypalStatus = $this->paymentService->getPaypalOrderStatus($order->transaction_id);
                
                // ⬅️ TAMBAHAN: Jika masih gagal, set null aja (jangan error)
                if (!$paypalStatus['success']) {
                    Log::warning('PayPal Status Fetch Failed (Non-critical)', [
                        'order_id' => $order->id,
                        'message' => $paypalStatus['message']
                    ]);
                    $paypalStatus = null; // Set null biar view tetap bisa tampil
                }
            } catch (\Exception $e) {
                Log::error('PayPal Status Exception in Admin', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                $paypalStatus = null;
            }
        }
        
        return view('admin.orders.show', compact('order', 'paypalStatus', 'paypalEnabled'));
    }

    /**
     * ACTION: Send Payment Details (BCA / PayPal)
     */
    public function sendPaymentDetails(Request $request, Order $order)
    {
        $paypalEnabled = Setting::isPaypalEnabled();

        if (!in_array($order->status, ['PENDING_REVIEW', 'DENIED'], true)) {
            return back()->with('error', 'Payment details already sent or order status invalid!');
        }

        $validated = $request->validate([
            'payment_gateway_override' => 'required|' . ($paypalEnabled ? 'in:MANUAL,PAYPAL' : 'in:MANUAL'),
            'admin_notes' => 'nullable|string|max:1000',
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $paymentGateway = $validated['payment_gateway_override'];
        $shippingCost = $validated['shipping_cost'];

        // ⬅️ TENTUKAN CURRENCY BERDASARKAN PAYMENT GATEWAY
        $newCurrency = $paymentGateway === 'PAYPAL' && $paypalEnabled ? 'USD' : 'IDR';
        
        // ⬅️ AMBIL SUBTOTAL SESUAI CURRENCY BARU
        $subtotal = $newCurrency === 'USD' ? $order->subtotal_usd : $order->subtotal_idr;
        
        // ⬅️ HITUNG ULANG TOTAL
        $newTotalAmount = $subtotal + $shippingCost;

        $order->update([
            'payment_gateway' => $paymentGateway,
            'payment_method' => $paymentGateway === 'PAYPAL' && $paypalEnabled ? 'PAYPAL' : 'BCA',
            'admin_notes' => $validated['admin_notes'],
            
            // ⬅️ UPDATE CURRENCY & AMOUNTS
            'currency' => $newCurrency,
            'shipping_cost' => $shippingCost, 
            'total_amount' => $newTotalAmount, 
            
            'status' => 'INVOICE_SENT',
            'payment_due_at' => now()->addHours(72),
        ]);

        $magicLink = route('order.track', $order->guest_token);
        
        try {
            Mail::to($order->contact_email)->queue(new PaymentDetailsMail($order, $magicLink));
            
            $message = $paymentGateway === 'PAYPAL' && $paypalEnabled
                ? 'PayPal invoice sent to ' . $order->contact_email 
                : 'BCA payment details sent to ' . $order->contact_email;
            
            return back()->with('success', $message);
            // return back()->with('success', $message . ' | Magic Link: ' . $magicLink);
            
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
            // UPDATE ORDER STATUS ke PRODUCTION
            $order->update([
                'status' => 'PRODUCTION',
                'paid_at' => now(),
                'rejection_notes' => null,
            ]);

            // Send production notification email
            $magicLink = route('order.track', $order->guest_token);
            try {
                Mail::to($order->contact_email)->queue(new OrderInProductionMail($order, $magicLink));
            } catch (\Exception $e) {
                Log::error('Failed to send production notification email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            return back()->with('success', 'Payment approved! Order is now in PRODUCTION. Please fill shipping details when ready.');
        } 
        
        elseif ($action === 'reject') {

            $validated = $request->validate([
                'rejection_notes' => 'required|string|max:500',
            ]);
            // Update status kembali jadi invoice sent
            $order->update([
                'status' => 'INVOICE_SENT',
                'payment_due_at' => now()->addHours(72),
                'rejection_notes' => $validated['rejection_notes'],
                'proof_of_payment' => null, // Hapus bukti yang ditolak
            ]);

            // Kirim email payment rejection
            $magicLink = route('order.track', $order->guest_token);
            try {
                Mail::to($order->contact_email)->queue(new PaymentRejectedMail($order, $magicLink));
            } catch (\Exception $e) {
                Log::error('Failed to send payment rejection email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            return back()->with('success', 'Payment rejected. Customer can re-upload new proof.');
        }

        return back()->with('error', 'Invalid action!');
    }

    /**
     * ACTION: Process Order (PAID → PRODUCTION)
     * For PayPal orders that are already paid, move to production when ready
     */
    public function processOrder(Order $order)
    {
        if ($order->status !== 'PAID') {
            return back()->with('error', 'Only PAID orders can be processed to PRODUCTION!');
        }

        $order->update([
            'status' => 'PRODUCTION',
        ]);

        // Send production notification email
        $magicLink = route('order.track', $order->guest_token);
        try {
            Mail::to($order->contact_email)->queue(new OrderInProductionMail($order, $magicLink));
        } catch (\Exception $e) {
            Log::error('Failed to send production notification email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'Order moved to PRODUCTION! You can now fill shipping details when ready.');
    }

    /**
     * ACTION: Update Resi (Shipping) - Untuk order yang sudah PRODUCTION
     */
    public function updateShipping(Request $request, Order $order)
    {
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'tracking_number' => 'required|string',
        ]);

        // Ensure shipment exists
        if (!$order->shipment) {
            return back()->with('error', 'Shipment record not found for this order.');
        }

        // Delivery SLA: domestic vs international
        $deliveryDays = $order->shipment->country === 'Indonesia' ? 7 : 21;

        $now = now();

        // shipped_at: set once when transitioning PRODUCTION -> SHIPPED.
        // If order is already SHIPPED, keep shipped_at stable (but backfill if missing).
        $newShippedAt = $order->status === 'PRODUCTION'
            ? $now
            : ($order->shipment->shipped_at ?? $now);

        // Reset auto-complete timer on every admin edit (as requested).
        // - When shipping starts (PRODUCTION -> SHIPPED): due = shipped_at + SLA
        // - When already shipped and admin edits courier/tracking: due = now + SLA
        $newAutoCompleteDueAt = $order->status === 'PRODUCTION'
            ? $newShippedAt->copy()->addDays($deliveryDays)
            : $now->copy()->addDays($deliveryDays);
                
        // UPDATE SHIPMENT
        $order->shipment->update([
            'courier_name' => $validated['courier_name'],
            'tracking_number' => $validated['tracking_number'],
            'shipped_at' => $newShippedAt,
            'auto_complete_due_at' => $newAutoCompleteDueAt,
        ]);

        // UPDATE ORDER STATUS jika masih PRODUCTION
        if ($order->status === 'PRODUCTION') {
            $order->update([
                'status' => 'SHIPPED',
            ]);

            // Kirim email shipping notification
            $magicLink = route('order.track', $order->guest_token);
            try {
                Mail::to($order->contact_email)->queue(new OrderShippedMail($order, $magicLink));
            } catch (\Exception $e) {
                Log::error('Failed to send shipping notification email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            return back()->with('success', 'Order marked as SHIPPED with tracking: ' . $validated['tracking_number']);
        }

        return back()->with('success', 'Shipping information updated successfully!');
    }

    public function uploadProof(Request $request, Order $order)
    {
        // Lock row untuk mencegah concurrent update
        $order = Order::where('id', $order->id)->lockForUpdate()->first();
        
        // Validasi: hanya boleh upload jika INVOICE_SENT dan MANUAL payment
        if ($order->status !== 'INVOICE_SENT' || $order->payment_gateway !== 'MANUAL') {
            return back()->with('error', 'Cannot upload proof for this order status or payment method.');
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('proof_image')) {
            DB::beginTransaction();
            try {
                // Delete old proof if exists
                $oldProof = $order->proof_of_payment;
                
                // Store new proof dengan timestamp untuk avoid collision
                $filename = 'admin_' . time() . '_' . $order->id . '.' . $request->file('proof_image')->extension();
                $path = $request->file('proof_image')->storeAs('payment_proofs', $filename, 'public');

                // Update order
                $order->update([
                    'proof_of_payment' => $path,
                    'status' => 'WAITING_VERIFICATION',
                    'rejection_notes' => null,
                ]);

                // Delete old proof setelah order updated (untuk avoid storage leak)
                if ($oldProof && $oldProof !== $path) {
                    Storage::disk('public')->delete($oldProof);
                }

                DB::commit();
                return back()->with('success', 'Payment proof uploaded by admin successfully! Order status changed to WAITING_VERIFICATION.');
                
            } catch (\Exception $e) {
                DB::rollBack();
                // Delete uploaded file jika transaction failed
                if (isset($path)) {
                    Storage::disk('public')->delete($path);
                }
                return back()->with('error', 'Failed to upload proof: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'No file uploaded.');
    }

    /**
     * Cancel Order
     */
    public function cancelOrder(Order $order)
    {
        // Only allow cancellation for certain statuses
        $cancellableStatuses = ['PENDING_REVIEW', 'INVOICE_SENT', 'WAITING_VERIFICATION'];
        
        if (!in_array($order->status, $cancellableStatuses)) {
            return back()->with('error', 'Cannot cancel order with status: ' . $order->status);
        }

        $order->update([
            'status' => 'CANCELLED',
        ]);

        // Send cancellation email to customer
        $magicLink = route('order.track', $order->guest_token);
        try {
            Mail::to($order->contact_email)->queue(new OrderCancelledMail($order, $magicLink));
        } catch (\Exception $e) {
            Log::error('Failed to send order cancellation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Complete Order (Admin marks order as completed)
     */
    public function completeOrder(Order $order)
    {
        if ($order->status !== 'SHIPPED') {
            return back()->with('error', 'Only SHIPPED orders can be marked as completed.');
        }

        $order->update([
            'status' => 'COMPLETED',
            'completed_at' => now(),
        ]);

        // Send complete email to customer (agar diarahkan review)
        $magicLink = route('order.track', $order->guest_token);
        try {
            Mail::to($order->contact_email)->queue(new OrderCompletedMail($order, $magicLink));
        } catch (\Exception $e) {
            Log::error('Failed to send order completion email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order has been marked as COMPLETED successfully.');
    }

    /**
     * Process Manual Refund (BCA)
     */
    public function processRefund(Request $request, Order $order)
    {
        if ($order->status !== 'COMPLETED') {
            return back()->with('error', 'Only COMPLETED orders can be refunded.');
        }

        if (!$order->can_request_refund) {
            return back()->with('error', 'Refund window has expired (14 days limit).');
        }

        if ($order->payment_gateway === 'PAYPAL') {
            return back()->with('error', 'PayPal refunds must be processed from PayPal Dashboard.');
        }

        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $order->total_amount,
        ]);

        $order->update([
            'status' => 'REFUNDED',
            'refund_amount' => $validated['refund_amount'],
            'refunded_at' => now(),
        ]);

        // Send refund confirmation email to customer
        $magicLink = route('order.track', $order->guest_token);
        try {
            Mail::to($order->contact_email)->queue(new RefundProcessedMail($order, $magicLink));
        } catch (\Exception $e) {
            Log::error('Failed to send refund confirmation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Refund processed successfully. Order marked as REFUNDED.');
    }
}