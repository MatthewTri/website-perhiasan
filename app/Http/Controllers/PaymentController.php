<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\PaymentReceivedNotification;
use App\Mail\Admin\PaymentDeniedNotification;
use App\Mail\Admin\PaymentDisputedNotification;
use App\Mail\Order\RefundProcessedMail;

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
            'order_param' => $orderId,
            'paypal_token_suffix' => $paypalToken ? substr($paypalToken, -6) : null,
        ]);

        if (!$paypalToken) {
            Log::error('Missing PayPal token in success callback');
            return redirect()->route('home')->with('error', 'Invalid payment callback');
        }

        // ✅ SECURITY: Always bind the callback to the order by PayPal Order ID (token)
        // Never trust the `order` query param as the source of truth.
        $order = Order::where('transaction_id', $paypalToken)->latest('id')->first();

        if (!$order) {
            Log::error('PayPal success callback: token not mapped to any order', [
                'order_param' => $orderId,
                'paypal_token_suffix' => substr($paypalToken, -6),
            ]);
            return redirect()->route('home')->with('error', 'Payment session not recognized. Please contact support.');
        }

        if ($orderId && (string) $order->id !== (string) $orderId) {
            Log::warning('PayPal success callback: order param mismatch (possible tampering)', [
                'order_param' => $orderId,
                'resolved_order_id' => $order->id,
                'paypal_token_suffix' => substr($paypalToken, -6),
            ]);
        }

        Log::info('Order resolved from PayPal token', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'current_status' => $order->status,
            'transaction_id_suffix' => $order->transaction_id ? substr($order->transaction_id, -6) : null,
        ]);

        // Basic sanity checks
        if ($order->payment_gateway !== 'PAYPAL') {
            Log::warning('PayPal success callback: order gateway is not PAYPAL', [
                'order_id' => $order->id,
                'payment_gateway' => $order->payment_gateway,
            ]);
            return redirect()->route('order.track', $order->guest_token)
                        ->with('error', 'This order is not configured for PayPal payment.');
        }

        if (strtoupper((string) $order->currency) !== 'USD') {
            Log::warning('PayPal success callback: non-USD order currency', [
                'order_id' => $order->id,
                'currency' => $order->currency,
            ]);
            return redirect()->route('order.track', $order->guest_token)
                        ->with('error', 'Invalid order currency for PayPal payment.');
        }

        // Jika sudah PAID, skip
        if ($order->status === 'PAID') {
            Log::info('Order already paid, skipping capture');
            return redirect()->route('order.track', $order->guest_token)
                        ->with('success', 'Payment successful!');
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
                'paypal_token_suffix' => substr($paypalToken, -6),
                'order_id' => $order->id,
            ]);

            $captureResult = $this->paymentService->capturePaypalPayment($paypalToken);
            
            Log::info('PayPal Capture API Response', [
                'result' => $captureResult
            ]);

            if (!empty($captureResult['success'])) {
                // ✅ Verify transaction binding
                if (!empty($captureResult['transaction_id']) && $captureResult['transaction_id'] !== $paypalToken) {
                    Log::error('PayPal capture: transaction id mismatch', [
                        'order_id' => $order->id,
                        'expected_token_suffix' => substr($paypalToken, -6),
                        'returned_transaction_id_suffix' => substr((string) $captureResult['transaction_id'], -6),
                    ]);
                    return redirect()->route('order.track', $order->guest_token)
                                ->with('error', '❌ Payment verification failed (session mismatch).');
                }

                // ✅ Verify currency and amount
                $capturedCurrency = strtoupper((string) ($captureResult['currency'] ?? ''));
                $capturedAmount = (string) ($captureResult['amount'] ?? '0');
                $expectedAmount = $this->calculateExpectedPaypalTotalUsd($order);

                if ($capturedCurrency !== 'USD') {
                    Log::error('PayPal capture: currency mismatch', [
                        'order_id' => $order->id,
                        'captured_currency' => $capturedCurrency,
                    ]);
                    return redirect()->route('order.track', $order->guest_token)
                                ->with('error', '❌ Payment verification failed (currency mismatch).');
                }

                if (!$this->amountEquals($capturedAmount, $expectedAmount)) {
                    Log::error('PayPal capture: amount mismatch', [
                        'order_id' => $order->id,
                        'captured_amount' => $this->normalizeMoney($capturedAmount),
                        'expected_amount' => $expectedAmount,
                    ]);
                    return redirect()->route('order.track', $order->guest_token)
                                ->with('error', '❌ Payment verification failed (amount mismatch). Please contact support.');
                }

                // ✅ UPDATE ORDER (ini backup, webhook juga akan update)
                $order->update([
                    'status' => 'PAID',
                    'paid_at' => now(),
                    'capture_id' => $captureResult['capture_id'] ?? null,
                ]);

                Log::info('TESTING WEBHOOK: Database TIDAK diupdate oleh Controller. Menunggu Webhook...');

                Log::info('✅ Order updated to PAID with Capture ID', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'capture_id' => $captureResult['capture_id'],
                    'amount' => $captureResult['amount'] ?? 'N/A'
                ]);

                // ✅ KIRIM EMAIL KE ADMIN (PRIMARY TRIGGER)
                try {
                    // Mail::to(config('mail.admin_email'))->queue(new PaymentReceivedNotification($order, 'paypal'));
                    Log::info('✅ PayPal payment notification email sent to admin', [
                        'order_id' => $order->id,
                        'admin_email' => config('mail.admin_email')
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send PayPal payment notification email to admin', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }

                return redirect()->route('order.track', $order->guest_token)
                            ->with('success', 'Payment successful! Your order #' . $order->order_number . ' is being processed.');
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

    private function calculateExpectedPaypalTotalUsd(Order $order): string
    {
        $order->loadMissing('orderItems');

        $itemsTotal = $order->orderItems->sum(function ($item) {
            return (float) $item->price_usd * (int) $item->quantity;
        });

        $shipping = (float) $order->shipping_cost;
        $tax = (float) ($order->tax_amount ?? 0);

        return number_format($itemsTotal + $shipping + $tax, 2, '.', '');
    }

    private function normalizeMoney($value): string
    {
        return number_format((float) $value, 2, '.', '');
    }

    private function amountEquals($a, $b): bool
    {
        return $this->normalizeMoney($a) === $this->normalizeMoney($b);
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

            if (!is_array($event) || empty($event['event_type'])) {
                Log::warning('PayPal Webhook: invalid JSON payload');
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            Log::info('PayPal Webhook received', [
                'event_id' => $event['id'] ?? null,
                'event_type' => $event['event_type'],
            ]);

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

                case 'PAYMENT.CAPTURE.PENDING':
                    return $this->handlePaymentPending($event);
                    
                case 'PAYMENT.CAPTURE.DENIED':
                    return $this->handlePaymentDenied($event);
                    
                case 'PAYMENT.CAPTURE.REFUNDED':
                    return $this->handlePaymentRefunded($event);

                case 'PAYMENT.CAPTURE.REVERSED':
                    return $this->handlePaymentReversed($event);

                case 'CUSTOMER.DISPUTE.CREATED':
                    return $this->handleDisputeCreated($event);

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
        
        // Skip verification hanya untuk local/testing (jangan pernah di production)
        if (empty($webhookId)) {
            if (app()->environment(['local', 'testing'])) {
                Log::warning('PayPal Webhook: webhook_id not configured, skipping verification (local/testing only)');
                return true;
            }

            Log::critical('PayPal Webhook: webhook_id not configured in non-local environment');
            return false;
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
        // Extract IDs
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $invoiceId = $event['resource']['invoice_id'] ?? null;
        $captureId = $event['resource']['id'] ?? null;


        Log::info('PayPal Webhook: Extracted IDs', [
            'paypal_order_id' => $paypalOrderId,
            'invoice_id' => $invoiceId,
            'capture_id' => $captureId
        ]);



        // Search order
        $order = null;
        
        // 1. By transaction_id
        if ($paypalOrderId) {
            $order = Order::where('transaction_id', $paypalOrderId)->latest('id')->first();
            if ($order) {
                Log::info('✅ Order found by transaction_id', ['order_id' => $order->id]);
            }
        }
        
        // 2. By order_number (invoice_id)
        if (!$order && $invoiceId) {
            $baseInvoiceId = $this->extractBaseInvoiceId($invoiceId);
            $order = Order::where('order_number', $baseInvoiceId)->first();
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
            // Return 200 to prevent repeated retries; investigate via logs.
            return response()->json(['status' => 'ignored', 'reason' => 'order_not_found'], 200);
        }

        // Idempotency check - TAPI TETAP KIRIM EMAIL!
        if ($order->status === 'PAID') {
            Log::info('PayPal Webhook: Order already paid, sending email only', ['order_id' => $order->id]);
            
            // ✅ KIRIM EMAIL MESKIPUN SUDAH PAID
            try {
                Mail::to(config('mail.admin_email'))->queue(new PaymentReceivedNotification($order, 'paypal'));
                Log::info('✅ Payment notification sent for already-paid order');
            } catch (\Exception $e) {
                Log::error('Failed to send payment notification', ['error' => $e->getMessage()]);
            }
            
            return response()->json(['status' => 'already_processed'], 200);
        }

        // Validate amount & currency (USD only)
        $resourceAmount = $event['resource']['amount']['value'] ?? null;
        $resourceCurrency = strtoupper((string) ($event['resource']['amount']['currency_code'] ?? ''));

        if ($resourceCurrency !== 'USD') {
            Log::error('PayPal Webhook: currency mismatch', [
                'order_id' => $order->id,
                'currency' => $resourceCurrency,
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'currency_mismatch'], 200);
        }

        $expectedAmount = $this->calculateExpectedPaypalTotalUsd($order);
        if ($resourceAmount === null || !$this->amountEquals($resourceAmount, $expectedAmount)) {
            Log::error('PayPal Webhook: amount mismatch', [
                'order_id' => $order->id,
                'captured_amount' => $resourceAmount,
                'expected_amount' => $expectedAmount,
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'amount_mismatch'], 200);
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

    private function extractBaseInvoiceId(string $invoiceId): string
    {
        // PaymentService currently uses: order_number . '-' . time()
        // So invoice_id might be: INV-2025-00001-1712345678
        return preg_replace('/-\d+$/', '', $invoiceId) ?? $invoiceId;
    }

    /**
     * Handle: Payment Pending (PayPal needs verification / review)
     */
    private function handlePaymentPending(array $event)
    {
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $invoiceId = $event['resource']['invoice_id'] ?? null;

        Log::warning('PayPal Webhook: Payment pending event received', [
            'paypal_order_id_suffix' => $paypalOrderId ? substr($paypalOrderId, -6) : null,
            'invoice_id' => $invoiceId,
            'event_id' => $event['id'] ?? null,
        ]);

        // Find order (prefer PayPal order id, fallback to invoice_id)
        $order = null;
        if ($paypalOrderId) {
            $order = Order::where('transaction_id', $paypalOrderId)->latest('id')->first();
        }

        if (!$order && $invoiceId) {
            $baseInvoiceId = $this->extractBaseInvoiceId($invoiceId);
            $order = Order::where('order_number', $baseInvoiceId)->latest('id')->first();

            // If we found by invoice and have a PayPal order id, bind it for subsequent events.
            if ($order && $paypalOrderId && empty($order->transaction_id)) {
                $order->update(['transaction_id' => $paypalOrderId]);
                Log::info('PayPal Webhook: Pending - transaction_id backfilled from webhook', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'paypal_order_id_suffix' => substr($paypalOrderId, -6),
                ]);
            }
        }

        if (!$order) {
            Log::warning('PayPal Webhook: Order not found for pending payment', [
                'paypal_order_id_suffix' => $paypalOrderId ? substr($paypalOrderId, -6) : null,
                'invoice_id' => $invoiceId,
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'order_not_found'], 200);
        }

        // Idempotency - if order is already in a terminal/progress state, don't roll it back
        if (in_array($order->status, ['PAID', 'PRODUCTION', 'SHIPPED', 'COMPLETED', 'CANCELLED', 'REFUNDED'], true)) {
            Log::info('PayPal Webhook: Pending ignored due to current order status', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
            ]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        if ($order->status === 'PENDING') {
            return response()->json(['status' => 'already_processed'], 200);
        }

        // Mark as pending; keep PayPal session identifiers (order might complete later).
        $order->update([
            'status' => 'PENDING',
            'paid_at' => null,
            'capture_id' => null,
        ]);

        Log::warning('PayPal Webhook: Payment pending, order marked as PENDING', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'previous_status' => $order->getOriginal('status'),
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle: Payment Denied
     */
    private function handlePaymentDenied(array $event)
    {
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $invoiceId = $event['resource']['invoice_id'] ?? null;

        Log::warning('PayPal Webhook: Payment denied event received', [
            'paypal_order_id_suffix' => $paypalOrderId ? substr($paypalOrderId, -6) : null,
            'invoice_id' => $invoiceId,
            'event_id' => $event['id'] ?? null,
        ]);

        // Find order (prefer PayPal order id, fallback to invoice_id)
        $order = null;
        if ($paypalOrderId) {
            $order = Order::where('transaction_id', $paypalOrderId)->latest('id')->first();
        }

        if (!$order && $invoiceId) {
            $baseInvoiceId = $this->extractBaseInvoiceId($invoiceId);
            $order = Order::where('order_number', $baseInvoiceId)->latest('id')->first();

            // If we found by invoice, keep a trace in logs; do NOT set transaction_id here because denied means the session is invalid.
            if ($order) {
                Log::info('PayPal Webhook: Denied - order found by invoice_id fallback', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'base_invoice_id' => $baseInvoiceId,
                ]);
            }
        }

        if (!$order) {
            Log::warning('PayPal Webhook: Order not found for denied payment', [
                'paypal_order_id_suffix' => $paypalOrderId ? substr($paypalOrderId, -6) : null,
                'invoice_id' => $invoiceId,
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'order_not_found'], 200);
        }

        // Idempotency - if order is already in a terminal/progress state, don't roll it back
        if (in_array($order->status, ['PAID', 'PRODUCTION', 'SHIPPED', 'COMPLETED', 'CANCELLED', 'REFUNDED'], true)) {
            Log::info('PayPal Webhook: Denied ignored due to current order status', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
            ]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        if ($order->status === 'DENIED') {
            return response()->json(['status' => 'already_processed'], 200);
        }

        // ✅ Business rule: if payment is denied, mark order as DENIED
        // so admin must send a fresh invoice/link.
        $order->update([
            'status' => 'DENIED',

            // Clear stale PayPal session identifiers/URLs
            'transaction_id' => null,
            'payment_url' => null,
            'capture_id' => null,

            // Clear payment timers/state (invoice will be re-sent by admin)
            'payment_due_at' => null,
            'paid_at' => null,
        ]);

        Log::warning('PayPal Webhook: Payment denied, order marked as DENIED', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'previous_status' => $order->getOriginal('status'),
        ]);

        try {
            Mail::to(config('mail.admin_email'))->queue(new PaymentDeniedNotification($order));
        } catch (\Exception $e) {
            Log::error('Failed to send admin denied notification email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'admin_email' => config('mail.admin_email'),
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle: Payment Reversed
     */
    private function handlePaymentReversed(array $event)
    {
        $paypalOrderId = $event['resource']['supplementary_data']['related_ids']['order_id'] ?? null;
        $order = Order::where('transaction_id', $paypalOrderId)->latest('id')->first();

        if (!$order) {
            Log::error('PayPal Webhook: Order not found for reversal', [
                'paypal_order_id' => $paypalOrderId
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'order_not_found'], 200);
        }

        if (in_array($order->status, ['REVERSED', 'CANCELLED'])) {
            return response()->json(['status' => 'already_processed'], 200);
        }

        $order->update(['status' => 'REVERSED']);

        Log::warning('PayPal Webhook: Payment reversed', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        // TODO: Notify admin dan user customer bahwa ada reversal

        return response()->json(['status' => 'success'], 200);
    }

    private function handleDisputeCreated(array $event)
    {
        $disputeId = $event['resource']['dispute_id'] ?? null;
        $reason = $event['resource']['reason'] ?? 'UNKNOWN';
        $amount = $event['resource']['dispute_amount']['value'] ?? null;

        // Cari order dari disputed_transactions
        $paypalOrderId = null;
        foreach ($event['resource']['disputed_transactions'] ?? [] as $txn) {
            $paypalOrderId = $txn['buyer_transaction_id'] ?? null;
            if ($paypalOrderId) break;
        }

        $order = $paypalOrderId
            ? Order::where('transaction_id', $paypalOrderId)->latest('id')->first()
            : null;

        Log::warning('⚠️ PayPal Webhook: Dispute created', [
            'dispute_id' => $disputeId,
            'reason' => $reason,
            'amount' => $amount,
            'order_id' => $order?->id,
            'order_number' => $order?->order_number,
        ]);

        if ($order) {
            if ($order->status === 'DISPUTED') {
                return response()->json(['status' => 'already_processed'], 200);
            }

            $order->update(['status' => 'DISPUTED']);

            try {
                Mail::to(config('mail.admin_email'))->queue(
                    new PaymentDisputedNotification($order, $disputeId, (string) $reason, $amount)
                );
            } catch (\Exception $e) {
                Log::error('Failed to send admin dispute notification email', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'dispute_id' => $disputeId,
                    'admin_email' => config('mail.admin_email'),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle: Payment Refunded
     */
    private function handlePaymentRefunded(array $event)
    {
        Log::info('PayPal Webhook: Refund event received', ['event' => $event]);

        // ⬅️ STRUKTUR DATA REFUND BERBEDA DENGAN CAPTURE!
        // Refund punya structure: resource.sale_id atau resource.links
        $captureId = null;
        $refundId = $event['resource']['id'] ?? null;
        $refundAmount = $event['resource']['amount']['value'] ?? 0;
        $refundCurrency = $event['resource']['amount']['currency_code'] ?? 'USD';
        
        // Cari Capture ID dari berbagai tempat
        // 1. Dari sale_id (untuk older PayPal API)
        if (isset($event['resource']['sale_id'])) {
            $captureId = $event['resource']['sale_id'];
        }
        
        // 2. Dari links array
        if (!$captureId && isset($event['resource']['links'])) {
            foreach ($event['resource']['links'] as $link) {
                if ($link['rel'] === 'up' || $link['rel'] === 'refund') {
                    // Extract capture ID from URL: /v2/payments/captures/{capture_id}
                    preg_match('/captures\/([A-Z0-9]+)/', $link['href'], $matches);
                    if (!empty($matches[1])) {
                        $captureId = $matches[1];
                        break;
                    }
                }
            }
        }
        
        // 3. Dari invoice_id (backup)
        $invoiceId = $event['resource']['invoice_id'] ?? null;
        $baseInvoiceId = $invoiceId ? $this->extractBaseInvoiceId($invoiceId) : null;
        
        Log::info('PayPal Webhook: Extracted refund data', [
            'capture_id' => $captureId,
            'refund_id' => $refundId,
            'refund_amount' => $refundAmount,
            'currency' => $refundCurrency,
            'invoice_id' => $invoiceId,
            'base_invoice_id' => $baseInvoiceId,
            'raw_resource' => $event['resource'] // ⬅️ Untuk debugging
        ]);

        // Search order by capture_id or invoice_id
        $order = null;
        
        if ($captureId) {
            $order = Order::where('capture_id', $captureId)->first();
            if ($order) {
                Log::info('✅ Order found by capture_id', ['order_id' => $order->id]);
            }
        }
        
        if (!$order && $baseInvoiceId) {
            $order = Order::where('order_number', $baseInvoiceId)->first();
            if ($order) {
                Log::info('✅ Order found by invoice_id', ['order_id' => $order->id]);
            }
        }
        
        if (!$order) {
            Log::error('❌ PayPal Webhook: Order not found for refund', [
                'capture_id' => $captureId,
                'invoice_id' => $invoiceId,
                'base_invoice_id' => $baseInvoiceId,
                'refund_id' => $refundId
            ]);
            return response()->json(['status' => 'ignored', 'reason' => 'order_not_found'], 200);
        }

        // Idempotency check
        if ($order->status === 'REFUNDED') {
            Log::info('PayPal Webhook: Order already refunded', ['order_id' => $order->id]);
            return response()->json(['status' => 'already_processed'], 200);
        }

        // Update order status
        $order->update([
            'status' => 'REFUNDED',
            'refund_amount' => $refundAmount,
            'refunded_at' => now(),
        ]);
        
        Log::info('✅ PayPal Webhook: Refund processed successfully', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'refund_id' => $refundId,
            'refund_amount' => $refundAmount,
            'refund_currency' => $refundCurrency,
            'previous_status' => $order->getOriginal('status')
        ]);

        // Send refund confirmation email to customer
        $magicLink = route('order.track', $order->guest_token);
        try {
            Mail::to($order->contact_email)->queue(new RefundProcessedMail($order, $magicLink));
            Log::info('✅ Refund confirmation email sent to customer', [
                'order_id' => $order->id,
                'customer_email' => $order->contact_email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send refund confirmation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }
}