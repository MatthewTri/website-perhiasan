<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\Admin\PaymentReceivedNotification;

class OrderTrackingController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * 1. Menampilkan Halaman Magic Link
     * URL: /order/track/{token}
     */
    public function show($token)
    {
        // Cari order berdasarkan token (Guest/Member sama saja)
        $order = Order::with(['orderItems.product.images' , 'shipment'])
                  ->where('guest_token', $token)
                  ->firstOrFail();

        // Tampilkan View Tracking
        return view('tracking.show', compact('order'));
    }

    /**
     * 2. Proses Upload Bukti Transfer (Khusus Manual BCA)
     * URL: /order/track/{token}/upload
     */
    public function uploadProof(Request $request, $token)
    {
        $order = Order::where('guest_token', $token)->lockForUpdate()->firstOrFail();

        // Validasi: Cuma boleh upload kalau metode MANUAL dan status INVOICE_SENT
        if ($order->payment_gateway !== 'MANUAL' || $order->status !== 'INVOICE_SENT') {
            return back()->with('error', 'Pesanan ini tidak membutuhkan upload bukti manual.');
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('proof_image')) {
            DB::beginTransaction();
            try {
                $oldProof = $order->proof_of_payment;

                // Simpan file dengan prefix customer dan timestamp
                $filename = 'customer_' . time() . '_' . $order->id . '.' . $request->file('proof_image')->extension();
                $path = $request->file('proof_image')->storeAs('payment_proofs', $filename, 'public');

                // Update status jadi Waiting Verification
                $order->update([
                    'proof_of_payment' => $path,
                    'status' => 'WAITING_VERIFICATION'
                ]);

                // Delete old proof after update
                if ($oldProof && $oldProof !== $path) {
                    Storage::disk('public')->delete($oldProof);
                }

                DB::commit();

                // Send email notification to admin
                try {
                    Mail::to(config('mail.admin_email'))->queue(new PaymentReceivedNotification($order, 'manual'));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment proof notification email to admin', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }

                return back()->with('success', 'Payment proof successfully uploaded. Our team will verify it shortly.');
                
            } catch (\Exception $e) {
                DB::rollBack();
                if (isset($path)) {
                    Storage::disk('public')->delete($path);
                }
                return back()->with('error', 'Upload failed: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'No file uploaded.');
    }

    public function createPaypalCheckout($guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        if (!in_array($order->status, ['INVOICE_SENT', 'WAITING_VERIFICATION'])) {
            return redirect()->route('order.track', $guestToken)
                           ->with('error', 'This order is not eligible for payment.');
        }

        if ($order->payment_gateway !== 'PAYPAL') {
            return redirect()->route('order.track', $guestToken)
                           ->with('error', 'This order is set for BCA bank transfer.');
        }

        if (strtoupper((string) $order->currency) !== 'USD') {
            return redirect()->route('order.track', $guestToken)
                           ->with('error', 'This order is not in USD.');
        }

        // ✅ Idempotency: if we already created a PayPal order, reuse the same approval URL.
        // This prevents losing webhook mapping by overwriting transaction_id.
        if (!empty($order->transaction_id) && !empty($order->payment_url)) {
            try {
                $paypalStatus = $this->paymentService->getPaypalOrderStatus($order->transaction_id);

                if (!empty($paypalStatus['success'])) {
                    $status = strtoupper((string) ($paypalStatus['status'] ?? ''));

                    // Still payable → reuse
                    if (in_array($status, ['CREATED', 'APPROVED', 'PAYER_ACTION_REQUIRED'], true)) {
                        return redirect()->away($order->payment_url);
                    }

                    // Already completed → don't recreate, just inform user
                    if ($status === 'COMPLETED') {
                        return redirect()->route('order.track', $guestToken)
                                       ->with('success', 'Payment already completed. Please wait a moment while we process your order.');
                    }

                    // Voided/expired → safe to recreate because webhook will not complete
                    if ($status === 'VOIDED') {
                        $order->update([
                            'transaction_id' => null,
                            'payment_url' => null,
                        ]);
                    } else {
                        // Unknown status: safest is to reuse and avoid overwriting mapping
                        return redirect()->away($order->payment_url);
                    }
                } else {
                    // If status check fails, reuse to avoid overwriting mapping.
                    return redirect()->away($order->payment_url);
                }
            } catch (\Exception $e) {
                Log::warning('PayPal status check failed, reusing existing payment_url', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                return redirect()->away($order->payment_url);
            }
        }

        // ⬅️ CREATE PAYPAL ORDER DENGAN RETRY
        try {
            $paypalResult = $this->paymentService->createPaypalOrder($order);

            if (!$paypalResult['success']) {
                return redirect()->route('order.track', $guestToken)
                               ->with('error', 'PayPal checkout failed: ' . $paypalResult['message'] . '. Please try again or contact support.');
            }

            // Safety: PayPal Order IDs should be unique
            $tokenAlreadyUsed = Order::where('transaction_id', $paypalResult['transaction_id'])
                ->where('id', '!=', $order->id)
                ->exists();

            if ($tokenAlreadyUsed) {
                Log::error('PayPal checkout: transaction_id already used by another order', [
                    'order_id' => $order->id,
                    'transaction_id_suffix' => substr((string) $paypalResult['transaction_id'], -6),
                ]);

                return redirect()->route('order.track', $guestToken)
                               ->with('error', 'PayPal session error. Please contact support.');
            }

            // Update order dengan PayPal data
            $order->update([
                'payment_url' => $paypalResult['redirect_url'],
                'transaction_id' => $paypalResult['transaction_id'],
            ]);

            return redirect()->away($paypalResult['redirect_url']);

        } catch (\Exception $e) {
            Log::error('PayPal Checkout Exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('order.track', $guestToken)
                           ->with('error', 'An error occurred while connecting to PayPal. Please try again in a few moments.');
        }
    }

    /**
     * USER CONFIRM ORDER RECEIVED (SHIPPED → COMPLETED)
     * URL: /order/track/{token}/complete
     */
    public function completeOrder($guestToken)
    {
        $order = Order::where('guest_token', $guestToken)->firstOrFail();

        // Validasi: Hanya bisa complete jika status SHIPPED
        if ($order->status !== 'SHIPPED') {
            return redirect()->route('order.track', $guestToken)
                           ->with('error', 'This order cannot be completed at this time.');
        }

        // Update status ke COMPLETED
        $order->update([
            'status' => 'COMPLETED',
            'completed_at' => now(),
        ]);

        // ⬅️ REDIRECT LANGSUNG KE HALAMAN REVIEW
        return redirect()->route('order.track.review', $guestToken)
                       ->with('success', 'Order completed! Please share your experience by writing a review.');
    }
}