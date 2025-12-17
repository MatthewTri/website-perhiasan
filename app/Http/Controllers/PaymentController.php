<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * PayPal Success Callback
     */
    public function paypalSuccess(Request $request)
    {
        $orderId = $request->query('order');
        $paypalOrderId = $request->query('token');

        if (!$orderId || !$paypalOrderId) {
            return redirect()->route('home')->with('error', 'Invalid payment callback');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found');
        }

        // Capture Payment
        $result = $this->paymentService->capturePaypalPayment($paypalOrderId);

        if ($result['success']) {
            $order->update([
                'status' => 'PAID',
                'paid_at' => now(),
                'transaction_id' => $result['transaction_id'],
            ]);

            // TODO: Kirim email payment confirmed
            // Mail::to($order->contact_email)->send(new PaymentConfirmed($order));

            return redirect()->route('order.track', $order->guest_token)
                           ->with('success', 'Payment successful! Your order is now being processed.');
        }

        return redirect()->route('order.track', $order->guest_token)
                       ->with('error', 'Payment verification failed. Please contact support.');
    }

    /**
     * PayPal Cancel Callback
     */
    public function paypalCancel(Request $request)
    {
        $orderId = $request->query('order');

        if ($orderId) {
            $order = Order::find($orderId);
            
            if ($order) {
                return redirect()->route('order.track', $order->guest_token)
                               ->with('error', 'Payment cancelled. You can try again anytime.');
            }
        }

        return redirect()->route('home')->with('error', 'Payment cancelled');
    }
}