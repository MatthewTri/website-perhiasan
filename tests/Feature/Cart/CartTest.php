<?php

namespace Tests\Feature\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartTest extends TestCase
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

    public function test_authenticated_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct([
            'price_idr' => 25000,
            'price_usd' => 2.5,
        ]);

        $response = $this->actingAs($user)->postJson(route('cart.add', $product->id), [
            'quantity' => 2,
            'color' => 'gold',
            'size' => 'M',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $cart = Cart::where('user_id', $user->id)->firstOrFail();

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'color' => 'gold',
            'size' => 'M',
        ]);
    }

    public function test_adding_same_variant_increments_quantity(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user)->postJson(route('cart.add', $product->id), [
            'quantity' => 1,
            'color' => 'silver',
            'size' => null,
        ])->assertOk();

        $this->actingAs($user)->postJson(route('cart.add', $product->id), [
            'quantity' => 3,
            'color' => 'silver',
            'size' => null,
        ])->assertOk();

        $cart = Cart::where('user_id', $user->id)->firstOrFail();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('color', 'silver')
            ->whereNull('size')
            ->firstOrFail();

        $this->assertSame(4, $cartItem->quantity);
    }

    public function test_decrement_from_one_removes_item(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $cart = Cart::create(['user_id' => $user->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'color' => null,
            'size' => null,
        ]);

        $response = $this->actingAs($user)->patch(route('cart.update', $cartItem->id), [
            'action' => 'decrement',
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
