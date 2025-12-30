<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string', // ✅ Validasi color
        ]);
        
        $quantity = $validated['quantity'];
        $color = $validated['color'] ?? null; // ✅ Ambil color (nullable)

        if (Auth::check()) {
            // User authenticated - simpan ke database
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $productId)
                                ->where('color', $color)
                                ->first();

            if ($cartItem) {
                // Tambah quantity yang ada
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Buat baru dengan quantity dari request
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'color' => $color
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'quantity' => $quantity
            ]);
        } else {
            // Guest user - return product info untuk localStorage
            $product = Product::with('featuredImage')->find($productId);
            
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price_idr' => $product->price_idr,
                    'price_usd' => $product->price_usd,
                    'description' => $product->description,
                    'color' => $color,
                    'featured_image' => $product->featuredImage ? [
                        'image' => $product->featuredImage->image
                    ] : null
                ],
                'quantity' => $quantity
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

        $cartItems = [];

        foreach ($productIds as $key => $quantity) {
            $productId = null;
            $color = null;

            // ✅ Cek apakah key format lama (hanya angka) atau baru (productId_color)
            if (strpos($key, '_') !== false) {
                // Format baru: "123_red"
                $parts = explode('_', $key, 2);
                $productId = (int) $parts[0];
                $color = $parts[1] !== 'default' ? $parts[1] : null;
            } else {
                // Format lama: "123" (hanya productId)
                $productId = (int) $key;
                $color = null;
            }
            
            Log::info('Parsing cart key', [
                'key' => $key, 
                'productId' => $productId, 
                'color' => $color
            ]);
            
            $product = Product::with('featuredImage')->find($productId);
            
            if (!$product) continue;
            
            $cartItems[] = [
                'id' => 'guest_' . $key,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price_idr' => (float) $product->price_idr,
                    'price_usd' => (float) $product->price_usd,
                    'description' => $product->description,
                    'featured_image' => $product->featuredImage ? [
                        'image' => $product->featuredImage->image
                    ] : null
                ],
                'quantity' => (int) $quantity,
                'color' => $color // ✅ Color akan null jika format lama, atau sesuai pilihan jika format baru
            ];
        }

        Log::info('Guest cart items', ['items' => $cartItems]);

        return response()->json(['items' => $cartItems]);
    }

    // Merge guest cart items into authenticated user's cart
    public function migrateGuestCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $guestCartData = $request->all();
        
        if (empty($guestCartData)) {
            return response()->json(['success' => true, 'message' => 'No items to migrate']);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $migratedCount = 0;

        foreach ($guestCartData as $key => $quantity) {
            $productId = null;
            $color = null;

            // ✅ Parse key: support format baru "productId_color" dan format lama "productId"
            if (strpos($key, '_') !== false) {
                // Format baru: "123_red" atau "123_default"
                $parts = explode('_', $key, 2);
                $productId = (int) $parts[0];
                $color = $parts[1] !== 'default' ? $parts[1] : null;
            } else {
                // Format lama: "123" (hanya productId)
                $productId = (int) $key;
                $color = null;
            }

            if (!$productId || $quantity < 1) {
                continue; // Skip invalid data
            }

            // ✅ Cek apakah produk + color sudah ada di cart user
            $cartItem = CartItem::where('cart_id', $cart->id)
                                ->where('product_id', $productId)
                                ->where('color', $color) // ✅ Filter by color juga
                                ->first();

            if ($cartItem) {
                // ✅ Jika sudah ada, tambahkan quantity
                $cartItem->quantity += (int) $quantity;
                $cartItem->save();
            } else {
                // ✅ Jika belum ada, buat cart item baru
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => (int) $quantity,
                    'color' => $color // ✅ Simpan color
                ]);
            }

            $migratedCount++;
        }

        Log::info('Guest cart migrated', [
            'user_id' => Auth::id(),
            'items_migrated' => $migratedCount
        ]);

        return response()->json([
            'success' => true,
            'message' => "$migratedCount item(s) migrated to your cart"
        ]);
    }
}