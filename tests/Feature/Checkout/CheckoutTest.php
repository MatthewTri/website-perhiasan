<?php

namespace Tests\Feature\Checkout;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(array $overrides = []): Category
    {
        return Category::create(array_merge([
            'name' => 'Category ' . Str::random(8),
        ], $overrides));
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = $overrides['category'] ?? $this->createCategory();
        unset($overrides['category']);

        return Product::create(array_merge([
            'name' => 'Product ' . Str::random(8),
            'description' => 'Test product description',
            'price_idr' => 10000,
            'price_usd' => 10,
            'category_id' => $category->id,
            'rating_avg' => 0,
            'rating_count' => 0,
        ], $overrides));
    }

    private function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'contact_email' => 'buyer@gmail.com',
            'shipping_first_name' => 'Buyer',
            'shipping_last_name' => 'Test',
            'shipping_phone' => '081234567890',
            'shipping_address_line1' => 'Jl. Test No. 1',
            'shipping_city' => 'Jakarta',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '12345',
            'shipping_country' => 'Indonesia',
            'payment_gateway' => 'manual',
            'order_notes' => 'Please pack securely',
        ], $overrides);
    }

    public function test_authenticated_checkout_creates_order_and_clears_cart(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $product = $this->createProduct([
            'price_idr' => 25000,
            'price_usd' => 2.5,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'color' => 'red',
            'size' => null,
        ]);

        $response = $this->actingAs($user)->post('/checkout', $this->checkoutPayload([
            'payment_gateway' => 'manual',
        ]));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');

        $order = Order::latest('id')->firstOrFail();

        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('IDR', $order->currency);
        $this->assertSame('MANUAL', $order->payment_preference);
        $this->assertEquals(50000.0, (float) $order->total_amount);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'color' => 'red',
        ]);

        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id,
            'first_name' => 'Buyer',
        ]);
    }

    public function test_guest_checkout_paypal_creates_usd_order_from_cart_data(): void
    {
        Mail::fake();

        $product = $this->createProduct([
            'price_idr' => 10000,
            'price_usd' => 12.5,
        ]);

        $cartData = json_encode([
            (string) $product->id => 3,
        ]);

        $response = $this->post('/checkout', $this->checkoutPayload([
            'payment_gateway' => 'paypal',
            'cart_data' => $cartData,
        ]));

        $response->assertRedirect(route('home', ['clear_cart' => '1']));
        $response->assertSessionHas('success');

        $order = Order::latest('id')->firstOrFail();

        $this->assertNull($order->user_id);
        $this->assertSame('USD', $order->currency);
        $this->assertSame('PAYPAL', $order->payment_preference);
        $this->assertEquals(37.5, (float) $order->total_amount);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->assertDatabaseHas('shipments', [
            'order_id' => $order->id,
            'first_name' => 'Buyer',
        ]);
    }
}
