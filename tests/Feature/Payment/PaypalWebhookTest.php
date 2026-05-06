<?php

namespace Tests\Feature\Payment;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaypalWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        return Category::create([
            'name' => 'Category ' . Str::random(8),
        ]);
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = $overrides['category'] ?? $this->createCategory();
        unset($overrides['category']);

        return Product::create(array_merge([
            'name' => 'Product ' . Str::random(8),
            'description' => 'Test product',
            'price_idr' => 10000,
            'price_usd' => 12.50,
            'category_id' => $category->id,
            'rating_avg' => 0,
            'rating_count' => 0,
        ], $overrides));
    }

    public function test_paypal_webhook_capture_completed_marks_order_paid_and_binds_transaction_id_by_invoice_id(): void
    {
        $product = $this->createProduct([
            'price_usd' => 12.50,
            'price_idr' => 200000,
        ]);

        $order = Order::create([
            'order_number' => 'INV-2026-00001',
            'order_type' => 'CATALOG',
            'user_id' => null,
            'contact_email' => 'buyer@gmail.com',
            'guest_token' => Str::random(64),

            'status' => 'PENDING_REVIEW',
            'currency' => 'USD',

            'subtotal_idr' => 0,
            'subtotal_usd' => 37.50,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => 37.50,

            'payment_preference' => 'PAYPAL',
            'payment_gateway' => 'PAYPAL',
            'transaction_id' => null,
            'capture_id' => null,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'item_name' => $product->name,
            'item_description' => $product->description,
            'quantity' => 3,
            'price_idr' => $product->price_idr,
            'price_usd' => $product->price_usd,
            'color' => null,
            'size' => null,
        ]);

        $paypalOrderId = 'PAYPAL-ORDER-TEST-123';
        $captureId = 'CAPTURE-TEST-999';

        $event = [
            'id' => 'WH-TEST-1',
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'resource' => [
                'id' => $captureId,
                'invoice_id' => $order->order_number . '-1712345678',
                'amount' => [
                    'value' => '37.50',
                    'currency_code' => 'USD',
                ],
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => $paypalOrderId,
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/webhook/paypal', $event);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');

        $order->refresh();

        $this->assertSame('PAID', $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertSame($captureId, $order->capture_id);
        $this->assertSame($paypalOrderId, $order->transaction_id);
    }

    public function test_paypal_webhook_capture_completed_ignored_on_amount_mismatch(): void
    {
        $product = $this->createProduct([
            'price_usd' => 10.00,
            'price_idr' => 150000,
        ]);

        $order = Order::create([
            'order_number' => 'INV-2026-00002',
            'order_type' => 'CATALOG',
            'user_id' => null,
            'contact_email' => 'buyer@gmail.com',
            'guest_token' => Str::random(64),

            'status' => 'PENDING_REVIEW',
            'currency' => 'USD',

            'subtotal_idr' => 0,
            'subtotal_usd' => 10.00,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => 10.00,

            'payment_preference' => 'PAYPAL',
            'payment_gateway' => 'PAYPAL',
            'transaction_id' => 'PAYPAL-ORDER-EXISTING',
            'capture_id' => null,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'item_name' => $product->name,
            'item_description' => $product->description,
            'quantity' => 1,
            'price_idr' => $product->price_idr,
            'price_usd' => $product->price_usd,
            'color' => null,
            'size' => null,
        ]);

        $event = [
            'id' => 'WH-TEST-2',
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'resource' => [
                'id' => 'CAPTURE-TEST-2',
                'invoice_id' => $order->order_number . '-1712345678',
                'amount' => [
                    'value' => '9.99',
                    'currency_code' => 'USD',
                ],
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => $order->transaction_id,
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/webhook/paypal', $event);

        $response->assertOk();
        $response->assertJsonPath('status', 'ignored');
        $response->assertJsonPath('reason', 'amount_mismatch');

        $order->refresh();
        $this->assertSame('PENDING_REVIEW', $order->status);
        $this->assertNull($order->paid_at);
        $this->assertNull($order->capture_id);
    }

    public function test_paypal_webhook_capture_denied_marks_order_denied_and_clears_paypal_fields(): void
    {
        Mail::fake();

        $order = Order::create([
            'order_number' => 'INV-2026-00003',
            'order_type' => 'CATALOG',
            'user_id' => null,
            'contact_email' => 'buyer@gmail.com',
            'guest_token' => Str::random(64),

            'status' => 'PENDING_REVIEW',
            'currency' => 'USD',

            'subtotal_idr' => 0,
            'subtotal_usd' => 10.00,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => 10.00,

            'payment_preference' => 'PAYPAL',
            'payment_gateway' => 'PAYPAL',
            'transaction_id' => 'PAYPAL-ORDER-DENIED-1',
            'payment_url' => 'https://example.test/paypal/checkout',
            'capture_id' => 'CAPTURE-OLD',
            'payment_due_at' => now()->addHour(),
            'paid_at' => now(),
        ]);

        $event = [
            'id' => 'WH-TEST-DENIED-1',
            'event_type' => 'PAYMENT.CAPTURE.DENIED',
            'resource' => [
                'invoice_id' => $order->order_number . '-1712345678',
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => $order->transaction_id,
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/webhook/paypal', $event);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');

        $order->refresh();

        $this->assertSame('DENIED', $order->status);
        $this->assertNull($order->transaction_id);
        $this->assertNull($order->payment_url);
        $this->assertNull($order->capture_id);
        $this->assertNull($order->payment_due_at);
        $this->assertNull($order->paid_at);
    }

    public function test_paypal_webhook_capture_refunded_marks_order_refunded_and_sets_refund_fields(): void
    {
        Mail::fake();

        $order = Order::create([
            'order_number' => 'INV-2026-00004',
            'order_type' => 'CATALOG',
            'user_id' => null,
            'contact_email' => 'buyer@gmail.com',
            'guest_token' => Str::random(64),

            'status' => 'PAID',
            'currency' => 'USD',

            'subtotal_idr' => 0,
            'subtotal_usd' => 10.00,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => 10.00,

            'payment_preference' => 'PAYPAL',
            'payment_gateway' => 'PAYPAL',
            'transaction_id' => 'PAYPAL-ORDER-REFUND-1',
            'capture_id' => 'CAPTURE-REFUND-1',
        ]);

        $event = [
            'id' => 'WH-TEST-REFUND-1',
            'event_type' => 'PAYMENT.CAPTURE.REFUNDED',
            'resource' => [
                'id' => 'REFUND-ID-1',
                // PayPal payloads vary; this handler supports sale_id as a capture reference.
                'sale_id' => $order->capture_id,
                'invoice_id' => $order->order_number . '-1712345678',
                'amount' => [
                    'value' => '10.00',
                    'currency_code' => 'USD',
                ],
            ],
        ];

        $response = $this->postJson('/webhook/paypal', $event);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');

        $order->refresh();

        $this->assertSame('REFUNDED', $order->status);
        $this->assertEquals(10.00, (float) $order->refund_amount);
        $this->assertNotNull($order->refunded_at);
    }

    public function test_paypal_webhook_capture_pending_marks_order_pending(): void
    {
        $order = Order::create([
            'order_number' => 'INV-2026-00005',
            'order_type' => 'CATALOG',
            'user_id' => null,
            'contact_email' => 'buyer@gmail.com',
            'guest_token' => Str::random(64),

            'status' => 'PENDING_REVIEW',
            'currency' => 'USD',

            'subtotal_idr' => 0,
            'subtotal_usd' => 10.00,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => 10.00,

            'payment_preference' => 'PAYPAL',
            'payment_gateway' => 'PAYPAL',
            'transaction_id' => 'PAYPAL-ORDER-PENDING-1',
            'capture_id' => 'CAPTURE-OLD',
            'paid_at' => now(),
        ]);

        $event = [
            'id' => 'WH-TEST-PENDING-1',
            'event_type' => 'PAYMENT.CAPTURE.PENDING',
            'resource' => [
                'invoice_id' => $order->order_number . '-1712345678',
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => $order->transaction_id,
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/webhook/paypal', $event);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');

        $order->refresh();
        $this->assertSame('PENDING', $order->status);
        $this->assertNull($order->paid_at);
        $this->assertNull($order->capture_id);
    }

    public function test_paypal_webhook_capture_reversed_marks_order_reversed(): void
    {
        $order = Order::create([
            'order_number' => 'INV-2026-00006',
            'order_type' => 'CATALOG',
            'user_id' => null,
            'contact_email' => 'buyer@gmail.com',
            'guest_token' => Str::random(64),

            'status' => 'PAID',
            'currency' => 'USD',

            'subtotal_idr' => 0,
            'subtotal_usd' => 10.00,
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'total_amount' => 10.00,

            'payment_preference' => 'PAYPAL',
            'payment_gateway' => 'PAYPAL',
            'transaction_id' => 'PAYPAL-ORDER-REVERSED-1',
            'capture_id' => 'CAPTURE-REVERSED-1',
            'paid_at' => now(),
        ]);

        $event = [
            'id' => 'WH-TEST-REVERSED-1',
            'event_type' => 'PAYMENT.CAPTURE.REVERSED',
            'resource' => [
                'supplementary_data' => [
                    'related_ids' => [
                        'order_id' => $order->transaction_id,
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/webhook/paypal', $event);

        $response->assertOk();
        $response->assertJsonPath('status', 'success');

        $order->refresh();
        $this->assertSame('REVERSED', $order->status);
    }
}
