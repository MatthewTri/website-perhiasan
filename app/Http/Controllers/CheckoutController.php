<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class CheckoutController extends Controller
{
    public function index()
    {
        session()->forget('currency');

        if (Auth::check()) {
            // Authenticated user - get cart from database
            $cart = Cart::with(['cartItems.product.featuredImage'])
                        ->where('user_id', Auth::id())
                        ->first();

            $cartItems = $cart ? $cart->cartItems : collect();
            
            // ⬅️ CALCULATE BOTH IDR & USD TOTALS
            $totalIdr = $cartItems->sum(function ($item) {
                return $item->product->price_idr * $item->quantity;
            });
            
            $totalUsd = $cartItems->sum(function ($item) {
                return $item->product->price_usd * $item->quantity;
            });

            // Check if cart is empty
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                               ->with('error', 'Your cart is empty');
            }
        } else {
            // Guest user - cart will be loaded via JavaScript
            $cartItems = collect();
            $totalIdr = 0;
            $totalUsd = 0;
        }

        return view('checkout.index', compact('cartItems', 'totalIdr', 'totalUsd'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email',
            'shipping_first_name' => 'required|string|max:100',
            'shipping_last_name' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address_line1' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_gateway' => 'required|in:manual,paypal',
            'order_notes' => 'nullable|string',
            'cart_data' => 'nullable|json',
        ]);

        try {
            DB::beginTransaction();

            $userId = Auth::check() ? Auth::id() : null;
            $guestToken = Str::random(64);
            $isGuest = !Auth::check();

            // ⬅️ TENTUKAN CURRENCY DARI PAYMENT PREFERENCE
            $paymentPreference = strtoupper($validated['payment_gateway']); // 'MANUAL' atau 'PAYPAL'
            $selectedCurrency = $paymentPreference === 'PAYPAL' ? 'USD' : 'IDR';

            // Ambil cart items
            if (Auth::check()) {
                $cart = Cart::with('cartItems.product')->where('user_id', $userId)->first();
                
                if (!$cart || $cart->cartItems->isEmpty()) {
                    return back()->with('error', 'Your cart is empty');
                }
                
                $cartItems = $cart->cartItems;
            } else {
                if (!$request->cart_data) {
                    return back()->with('error', 'Cart data is missing');
                }
                
                $guestCart = json_decode($request->cart_data, true);
                $productIds = array_keys($guestCart);
                $products = Product::whereIn('id', $productIds)->get();
                
                $cartItems = $products->map(function ($product) use ($guestCart) {
                    return (object)[
                        'product' => $product,
                        'quantity' => $guestCart[$product->id]
                    ];
                });
            }

            // ⬅️ HITUNG SUBTOTAL DALAM KEDUA CURRENCY
            $subtotalIdr = $cartItems->sum(function ($item) {
                return $item->product->price_idr * $item->quantity;
            });
            
            $subtotalUsd = $cartItems->sum(function ($item) {
                return $item->product->price_usd * $item->quantity;
            });

            // ⬅️ TOTAL AMOUNT SESUAI CURRENCY TERPILIH
            $totalAmount = $selectedCurrency === 'USD' ? $subtotalUsd : $subtotalIdr;

            // Generate order number & slug
            $lastOrder = Order::latest('id')->first();
            $nextNumber = $lastOrder ? $lastOrder->id + 1 : 1;
            $orderNumber = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            $slug = SlugService::createSlug(Order::class, 'slug', $orderNumber);

            // ⬅️ CREATE ORDER DENGAN KEDUA SUBTOTAL
            $order = Order::create([
                'order_number' => $orderNumber,
                'slug' => $slug,
                'order_type' => 'CATALOG',
                'user_id' => $userId,
                'contact_email' => $validated['contact_email'],
                'guest_token' => $guestToken,
                'custom_request_id' => null,
                
                'status' => 'PENDING_REVIEW',
                'currency' => $selectedCurrency, // Currency terpilih saat ini
                
                // ⬅️ SIMPAN KEDUA SUBTOTAL
                'subtotal_idr' => $subtotalIdr,
                'subtotal_usd' => $subtotalUsd,
                
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount, // Total sesuai currency terpilih
                
                'payment_preference' => $paymentPreference, // Customer's choice
                'payment_gateway' => null, // Akan diisi admin
                'payment_method' => null,
                'transaction_id' => null,
                'payment_url' => null,
                
                'order_notes' => $validated['order_notes'],
                'is_reviewed' => false,
            ]);

            Shipment::create([
                'order_id' => $order->id,
                'first_name' => $validated['shipping_first_name'],
                'last_name' => $validated['shipping_last_name'],
                'phone' => $validated['shipping_phone'],
                'address_line1' => $validated['shipping_address_line1'],
                'city' => $validated['shipping_city'],
                'province' => $validated['shipping_province'],
                'postal_code' => $validated['shipping_postal_code'],
                'country' => $validated['shipping_country'],
                
                // Logistic fields (akan diisi admin nanti)
                'courier_name' => null,
                'tracking_number' => null,
                'shipped_at' => null,
            ]);

            // ⬅️ CREATE ORDER ITEMS DENGAN KEDUA HARGA
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'item_name' => $item->product->name,
                    'item_description' => $item->product->description,
                    'quantity' => $item->quantity,
                    
                    // ⬅️ SIMPAN KEDUA HARGA
                    'price_idr' => $item->product->price_idr,
                    'price_usd' => $item->product->price_usd,
                ]);
            }

            // Clear cart
            if (Auth::check() && isset($cart)) {
                $cart->cartItems()->delete();
            }

            DB::commit();

            $successMessage = 'Order placed successfully! Order Number: ' . $orderNumber . '. We will send payment details to your email within 24 hours.';
            
            if ($isGuest) {
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