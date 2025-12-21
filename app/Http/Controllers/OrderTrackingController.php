<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

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
        $order = Order::with(['orderItems.product.images'])
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
        $order = Order::where('guest_token', $token)->firstOrFail();

        // Validasi: Cuma boleh upload kalau metode MANUAL dan status INVOICE_SENT
        if ($order->payment_preference != 'MANUAL' || $order->status != 'INVOICE_SENT') {
            return back()->with('error', 'Pesanan ini tidak membutuhkan upload bukti manual.');
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('proof_image')) {
            // Hapus file lama jika ada (buat irit storage)
            if ($order->proof_of_payment) {
                Storage::disk('public')->delete($order->proof_of_payment);
            }

            // Simpan file baru
            $path = $request->file('proof_image')->store('payment_proofs', 'public');

            // Update status jadi Waiting Verification
            $order->update([
                'proof_of_payment' => $path,
                'status' => 'WAITING_VERIFICATION'
            ]);
        }

        return back()->with('success', 'Bukti berhasil diupload! Admin akan segera memverifikasi.');
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

        // ⬅️ CREATE PAYPAL ORDER DENGAN RETRY
        try {
            $paypalResult = $this->paymentService->createPaypalOrder($order);

            if (!$paypalResult['success']) {
                return redirect()->route('order.track', $guestToken)
                               ->with('error', 'PayPal checkout failed: ' . $paypalResult['message'] . '. Please try again or contact support.');
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