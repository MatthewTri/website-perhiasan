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
            $validated = $request->validate([
            'contact_email' => 'required|email',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address_line1' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_gateway' => 'required|in:xendit,paypal',
            'order_notes' => 'nullable|string',
            'cart_data' => 'nullable|json', // untuk guest
        ]);

            // Proses pembuatan order di sini (baik untuk user terautentikasi maupun guest)
            // ...

            return redirect()->route('order.confirmation')->with('success', 'Order placed successfully!');
    }
}