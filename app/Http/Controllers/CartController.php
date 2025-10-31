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
        // Ambil cart user yang sedang login
        $cart = Cart::with(['cartItems.product.featuredImage'])
                    ->where('user_id', Auth::id())
                    ->first();

        $cartItems = $cart ? $cart->cartItems : collect();
        
        // Hitung total
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, $productId)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        // Cari atau buat cart untuk user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Cek apakah produk sudah ada di cart
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambah quantity
            $cartItem->increment('quantity');
        } else {
            // Jika belum ada, buat cart item baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return response()->json(['success' => 'Product added to cart!']);
    }

    public function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::where('id', $cartItemId)
                           ->whereHas('cart', function($query) {
                               $query->where('user_id', Auth::id());
                           })
                           ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
}