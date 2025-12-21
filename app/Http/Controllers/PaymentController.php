<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    /**
     * PayPal Success Callback (CAPTURE PAYMENT DI SINI!)
     */
    public function paypalSuccess(Request $request)
    {
        $orderId = $request->query('order');
        $paypalToken = $request->query('token'); // PayPal Order ID

        Log::info('=== PayPal Success Callback START ===', [
            'order_id' => $orderId,
            'paypal_token' => $paypalToken,
            'full_query' => $request->all()
        ]);

        if (!$orderId || !$paypalToken) {
            Log::error('Missing parameters', [
                'order_id' => $orderId,
                'paypal_token' => $paypalToken
            ]);
            return redirect()->route('home')->with('error', 'Invalid payment callback');
        }

        $order = Order::find($orderId);

        if (!$order) {
            Log::error('Order not found', ['order_id' => $orderId]);
            return redirect()->route('home')->with('error', 'Order not found');
        }

        Log::info('Order found', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'current_status' => $order->status,
            'transaction_id' => $order->transaction_id
        ]);

        // Jika sudah PAID, skip
        if ($order->status === 'PAID') {
            Log::info('Order already paid, skipping capture');
            return redirect()->route('order.track', $order->guest_token)
                        ->with('success', '✅ Payment successful!');
        }

        if ($order->status === 'CANCELLED') {
            Log::warning('Attempted payment on cancelled order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
            
            return redirect()->route('order.track', $order->guest_token)
                        ->with('error', 'This order has been cancelled due to payment timeout. Please create a new order.');
        }

        // ⬅️ CAPTURE PAYMENT DI SINI (INI YANG TRIGGER WEBHOOK!)
        try {
            Log::info('Attempting to CAPTURE PayPal payment', [
                'paypal_order_id' => $paypalToken
            ]);

            $captureResult = $this->paymentService->capturePaypalPayment($paypalToken);
            
            Log::info('PayPal Capture API Response', [
                'result' => $captureResult
            ]);

            if ($captureResult['success']) {
                // ✅ UPDATE ORDER (ini backup, webhook juga akan update)
                // $order->update([
                //     'status' => 'PAID',
                //     'paid_at' => now(),
                //     'capture_id' => $captureResult['capture_id'] ?? null,
                // ]);

                Log::info('TESTING WEBHOOK: Database TIDAK diupdate oleh Controller. Menunggu Webhook...');

                Log::info('✅ Order updated to PAID with Capture ID', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'capture_id' => $captureResult['capture_id'],
                    'amount' => $captureResult['amount'] ?? 'N/A'
                ]);

                return redirect()->route('order.track', $order->guest_token)
                            ->with('success', '✅ Payment successful! Your order #' . $order->order_number . ' is being processed.');
            } else {
                Log::error('❌ PayPal Capture FAILED', [
                    'result' => $captureResult
                ]);
                
                return redirect()->route('order.track', $order->guest_token)
                            ->with('error', '❌ Payment verification failed: ' . ($captureResult['message'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            Log::error('💥 PayPal Capture EXCEPTION', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('order.track', $order->guest_token)
                        ->with('error', '❌ Payment processing error. Please contact support.');
        }
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

    /**
     * PayPal Webhook Handler (BACKUP - Jika user tidak return)
     */
    public function paypalWebhook(Request $request)
    {
        try {
            $body = $request->getContent();
            $event = json_decode($body, true);

            // --- TAMBAHKAN LOG DEBUGGING INI ---
            Log::info('--- DEBUG WEBHOOK START ---');
            
            // 1. Cek ID apa yang dibaca Laravel dari .env
            $configId = config('paypal.webhook_id');
            Log::info('Webhook ID di Config Laravel:', ['id' => $configId]);

            // 2. Cek apakah Header PayPal masuk atau hilang?
            $headers = $request->headers->all();
            Log::info('Headers yang diterima:', [
                'paypal-transmission-id' => $headers['paypal-transmission-id'][0] ?? 'MISSING',
                'paypal-transmission-sig' => $headers['paypal-transmission-sig'][0] ?? 'MISSING',
                'paypal-algo' => $headers['paypal-auth-algo'][0] ?? 'MISSING',
            ]);
            // ------------------------------------

            // Cek Verifikasi
            $isValid = $this->verifyPayPalWebhook($request, $event);
            
            if (!$isValid) {
                Log::error('❌ Verifikasi GAGAL. Signature tidak cocok.');
                // Biar kita tau gagalnya kenapa, return 400 tetep jalan
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            Log::info('✅ Verifikasi BERHASIL!');

            // 3. Handle berbagai event type
            switch ($event['event_type']) {
                case 'PAYMENT.CAPTURE.COMPLETED':
                    return $this->handlePaymentCompleted($event);
                    
                case 'PAYMENT.CAPTURE.DENIED':
                    return $this->handlePaymentDenied($event);
                    
                case 'PAYMENT.CAPTURE.REFUNDED':
                    return $this->handlePaymentRefunded($event);
                    
                default:
                    Log::info('PayPal Webhook: Unhandled event type', ['type' => $event['event_type']]);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('PayPal Webhook Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Verify PayPal Webhook Signature
     */
    private function verifyPayPalWebhook(Request $request, array $event): bool
    {
        $webhookId = config('paypal.webhook_id');
        
        // Skip verification untuk development
        if (empty($webhookId)) {
            Log::warning('PayPal Webhook: webhook_id not configured, skipping verification');
            return true;
        }

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $headers = $request->headers->all();
            $verificationData = [
                'auth_algo' => $headers['paypal-auth-algo'][0] ?? '',
                'cert_url' => $headers['paypal-cert-url'][0] ?? '',
                'transmission_id' => $headers['paypal-transmission-id'][0] ?? '',
                'transmission_sig' => $headers['paypal-transmission-sig'][0] ?? '',
                'transmission_time' => $headers['paypal-transmission-time'][0] ?? '',
                'webhook_id' => $webhookId,
                'webhook_event' => $event
            ];

            $verified = $provider->verifyWebHook($verificationData);
            
            return isset($verified['verification_status']) && 
                $verified['verification_status'] === 'SUCCESS';

        } catch (\Exception $e) {
            Log::error('PayPal Webhook Verification Error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Handle: Payment Completed
     */
    private function handlePaymentCompleted(array $event)
    {
        Log::info('PayPal Webhook: Full event received', ['event' => $event]);

        // Extract IDs
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $invoiceId = $event['resource']['invoice_id'] ?? null;
        $captureId = $event['resource']['id'] ?? null;


        Log::info('PayPal Webhook: Extracted IDs', [
            'paypal_order_id' => $paypalOrderId,
            'invoice_id' => $invoiceId,
            'capture_id' => $captureId
        ]);



        // ⬅️ TAMBAHKAN LOG DATABASE DEBUG
        try {
            $totalOrders = Order::count();
            $allOrders = Order::all(['id', 'order_number', 'transaction_id', 'status']);
            $invoiceSentOrders = Order::where('status', 'INVOICE_SENT')->get(['id', 'order_number', 'transaction_id']);
            
            Log::info('PayPal Webhook: Database Status', [
                'total_orders' => $totalOrders,
                'all_orders' => $allOrders->toArray(),
                'invoice_sent_orders' => $invoiceSentOrders->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal Webhook: Database query error', ['error' => $e->getMessage()]);
        }

        // Search order
        $order = null;
        
        // 1. By transaction_id
        if ($paypalOrderId) {
            $order = Order::where('transaction_id', $paypalOrderId)->first();
            if ($order) {
                Log::info('✅ Order found by transaction_id', ['order_id' => $order->id]);
            }
        }
        
        // 2. By order_number (invoice_id)
        if (!$order && $invoiceId) {
            $order = Order::where('order_number', $invoiceId)->first();
            if ($order) {
                Log::info('✅ Order found by order_number', ['order_id' => $order->id]);
                
                // Update transaction_id jika kosong
                if (empty($order->transaction_id) && $paypalOrderId) {
                    $order->update(['transaction_id' => $paypalOrderId]);
                    Log::info('Updated transaction_id from webhook', ['new_id' => $paypalOrderId]);
                }
            }
        }

        if (!$order) {
            Log::error('❌ PayPal Webhook: Order not found after search', [
                'searched_transaction_id' => $paypalOrderId,
                'searched_order_number' => $invoiceId,
            ]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Idempotency check
        if ($order->status === 'PAID') {
            Log::info('PayPal Webhook: Order already paid', ['order_id' => $order->id]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        // Update order
        $order->update([
            'status' => 'PAID',
            'paid_at' => now(),
            'capture_id' => $captureId,
        ]);

        Log::info('✅ PayPal Webhook: Payment completed successfully', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'capture_id' => $captureId,
            'amount' => $event['resource']['amount']['value'] ?? 'unknown'
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle: Payment Denied
     */
    private function handlePaymentDenied(array $event)
    {
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $order = Order::where('transaction_id', $paypalOrderId)->first();
        
        if ($order && $order->status !== 'CANCELLED') {
            $order->update(['status' => 'CANCELLED']);
            
            Log::warning('PayPal Webhook: Payment denied', ['order_id' => $order->id]);
            
            // TODO: Kirim email payment failed
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle: Payment Refunded
     */
    private function handlePaymentRefunded(array $event)
    {
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $order = Order::where('transaction_id', $paypalOrderId)->first();
        
        if ($order) {
            $order->update(['status' => 'REFUNDED']); // Perlu tambah status ini di enum
            
            Log::warning('PayPal Webhook: Payment refunded', ['order_id' => $order->id]);
            
            // TODO: Kirim email refund notification
        }

        return response()->json(['status' => 'success'], 200);
    }
}