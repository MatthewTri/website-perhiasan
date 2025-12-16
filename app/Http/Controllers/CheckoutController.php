<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use \Cviebrock\EloquentSluggable\Services\SlugService;

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
            'payment_gateway' => 'required|in:manual,paypal',
            'order_notes' => 'nullable|string',
            'cart_data' => 'nullable|json', // untuk guest
        ]);

        try {
            // 1. Tentukan user_id dan guest_token
            $userId = Auth::check() ? Auth::id() : null;
            $guestToken = Str::random(64);
            $isGuest = !Auth::check();

            // 2. Ambil cart items
            if (Auth::check()) {
                // User login
                $cart = Cart::with('cartItems.product')->where('user_id', $userId)->first();
                
                if (!$cart || $cart->cartItems->isEmpty()) {
                    return back()->with('error', 'Your cart is empty');
                }
                
                $cartItems = $cart->cartItems;
                
            } else {
                // Guest user
                if (!$request->cart_data) {
                    return back()->with('error', 'Cart data is missing');
                }
                
                $guestCart = json_decode($request->cart_data, true);
                $productIds = array_keys($guestCart);
                $products = Product::whereIn('id', $productIds)->get();
                
                // Transform ke collection
                $cartItems = collect($products)->map(function ($product) use ($guestCart) {
                    return (object)[
                        'product' => $product,
                        'quantity' => $guestCart[$product->id]
                    ];
                });
            }

            // 3. Hitung subtotal
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // 4. Generate order number
            $lastOrder = Order::latest('id')->first();
            $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;
            $orderNumber = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // 5. Generate slug dari order_number
            $slug = SlugService::createSlug(Order::class, 'slug', $orderNumber);

            // 6. Tentukan currency
            $currency = $validated['payment_gateway'] === 'paypal' ? 'USD' : 'IDR';

            // 7. Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'slug' => $slug,
                'order_type' => 'CATALOG',
                'user_id' => $userId,
                'contact_email' => $validated['contact_email'],
                'guest_token' => $guestToken,
                'custom_request_id' => null,
                
                'status' => 'PENDING_REVIEW',
                'currency' => $currency,
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total_amount' => $subtotal,
                
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address_line1' => $validated['shipping_address_line1'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_province' => $validated['shipping_province'],
                'shipping_postal_code' => $validated['shipping_postal_code'],
                'shipping_country' => $validated['shipping_country'],
                
                'courier_name' => null,
                'tracking_number' => null,
                
                'payment_preference' => strtoupper($validated['payment_gateway']), // ⬅️ 'MANUAL' atau 'PAYPAL'
                'payment_gateway' => null, // ⬅️ Akan diisi admin saat create invoice
                'payment_method' => null, // ⬅️ Akan diisi 'BCA' atau 'PAYPAL' oleh admin
                'transaction_id' => null,
                'payment_url' => null,
                
                'order_notes' => $validated['order_notes'],
                'is_reviewed' => false,
            ]);

            // 8. Create Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'item_name' => $item->product->name,
                    'item_description' => $item->product->description,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // 9. Clear cart
            if (Auth::check() && isset($cart)) {
                // User login - hapus dari database
                $cart->cartItems()->delete();
            }
            // Guest cart akan di-clear via JavaScript
            $successMessage = 'Order placed successfully! Order Number: ' . $orderNumber . '. We will send payment link to your email within 24 hours.';
            
            if ($isGuest) {
                // Untuk guest, redirect dengan query parameter agar JS bisa clear localStorage
                return redirect()->route('home', ['clear_cart' => '1'])
                    ->with('success', $successMessage);
            } else {
                return redirect()->route('home')
                    ->with('success', $successMessage);
            }

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }
}