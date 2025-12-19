<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cart = Cart::with(['cartItems.product.featuredImage'])
                        ->where('user_id', Auth::id())
                        ->first();

            $cartItems = $cart ? $cart->cartItems : collect();
            
            // ⬅️ CALCULATE BOTH IDR & USD TOTAL (di backend, bukan di view)
            $totalIdr = $cartItems->sum(function ($item) {
                return $item->product->price_idr * $item->quantity;
            });
            
            $totalUsd = $cartItems->sum(function ($item) {
                return $item->product->price_usd * $item->quantity;
            });
        } else {
            $cartItems = collect();
            $totalIdr = 0;
            $totalUsd = 0;
        }

        return view('cart.index', compact('cartItems', 'totalIdr', 'totalUsd'));
    }

    public function addToCart(Request $request, $productId)
    {
        if (Auth::check()) {
            // User authenticated - simpan ke database
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $productId)
                                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => 1
                ]);
            }

            return response()->json(['success' => 'Product added to cart!']);
        } else {
            // Guest user - return product info untuk localStorage
            $product = Product::find($productId);
            
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // ⬅️ RETURN DUAL PRICES
            return response()->json([
                'success' => 'Product added to cart!',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price_idr' => $product->price_idr,
                    'price_usd' => $product->price_usd,
                    'description' => $product->description
                ]
            ]);
        }
    }

    public function removeFromCart($cartItemId)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('id', $cartItemId)
                               ->whereHas('cart', function($query) {
                                   $query->where('user_id', Auth::id());
                               })
                               ->first();

            if ($cartItem) {
                $cartItem->delete();
            }

            return redirect()->route('cart.index')->with('success', 'Item removed from cart');
        } else {
            // Guest user - redirect back, removal akan handled by JavaScript
            return redirect()->route('cart.index')->with('success', 'Item removed from cart');
        }
    }

    // ⬅️ API endpoint untuk get guest cart items (FIXED)
    public function getGuestCartItems(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        
        if (empty($productIds)) {
            return response()->json(['items' => []]);
        }

        // Tambahkan eager loading untuk featuredImage
        $products = Product::with('featuredImage')->whereIn('id', array_keys($productIds))->get();
        
        $cartItems = $products->map(function($product) use ($productIds) {
            return [
                'id' => 'guest_' . $product->id,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price_idr' => (float) $product->price_idr, // ⬅️ CAST TO FLOAT
                    'price_usd' => (float) $product->price_usd, // ⬅️ CAST TO FLOAT
                    'description' => $product->description,
                    // Tambahkan image data
                    'featured_image' => $product->featuredImage ? [
                        'image' => $product->featuredImage->image,
                        'alt' => $product->featuredImage->alt ?? $product->name
                    ] : null
                ],
                'quantity' => (int) $productIds[$product->id] // ⬅️ CAST TO INT
            ];
        });

        return response()->json(['items' => $cartItems]);
    }

    // Merge guest cart items into authenticated user's cart
    public function migrateGuestCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $guestCart = $request->all();
        
        if (empty($guestCart)) {
            return response()->json(['success' => true, 'message' => 'No items to migrate']);
        }

        // Get or create user cart
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        $migratedCount = 0;
        
        foreach ($guestCart as $productId => $quantity) {
            // Validate product exists
            if (!Product::find($productId)) {
                continue;
            }
            
            $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();
            
            if ($cartItem) {
                // Add to existing quantity
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
            
            $migratedCount++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "Successfully migrated {$migratedCount} items to your cart"
        ]);
    }
}