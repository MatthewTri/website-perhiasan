<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            // User authenticated - ambil dari database
            $cart = Cart::with(['cartItems.product.featuredImage'])
                        ->where('user_id', Auth::id())
                        ->first();

            $cartItems = $cart ? $cart->cartItems : collect();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $shippingCost = 0;
            $taxAmount = 0;
            $total = $subtotal + $shippingCost + $taxAmount;

            return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'taxAmount', 'total'));
        } else {
            // Guest user - akan di-load via JavaScript
            return view('checkout.index', [
                'cartItems' => collect(),
                'subtotal' => 0,
                'shippingCost' => 0,
                'taxAmount' => 0,
                'total' => 0,
                'isGuest' => true
            ]);
        }
    }

    public function store(Request $request)
    {
        // Will be implemented later for processing the order
    }
}