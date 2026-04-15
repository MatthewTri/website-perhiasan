<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentService
{
    protected $maxRetries = 3;
    protected $retryDelay = 1000; // milliseconds

    /**
     * ⬅️ GET PAYPAL CLIENT WITH CACHED TOKEN
     */
    private function getPayPalClient()
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        $mode = (string) (config('paypal.mode') ?? '');
        $cacheKey = 'paypal_access_token:' . ($mode ?: 'default');

        // ⬅️ CACHE TOKEN SELAMA 55 MENIT (Token PayPal berlaku 60 menit)
        $token = Cache::remember($cacheKey, 55 * 60, function () use ($provider) {
            try {
                return $provider->getAccessToken();
            } catch (\Exception $e) {
                Log::error('PayPal Get Token Failed', ['error' => $e->getMessage()]);
                return null;
            }
        });

        if (!$token) {
            // Jika cache gagal, coba langsung
            $token = $provider->getAccessToken();
        }

        $provider->setAccessToken($token);
        return $provider;
    }

    /**
     * ⬅️ RETRY WRAPPER UNTUK API CALLS
     */
    private function retryApiCall(callable $callback, $context = 'PayPal API Call')
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            try {
                return $callback();
            } catch (\Exception $e) {
                $lastException = $e;
                $attempt++;

                Log::warning("{$context} Failed - Attempt {$attempt}/{$this->maxRetries}", [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt
                ]);

                if ($attempt < $this->maxRetries) {
                    // ⬅️ CLEAR CACHE TOKEN JIKA ERROR (BIAR REFRESH)
                    if (str_contains($e->getMessage(), 'Authentication') || 
                        str_contains($e->getMessage(), 'Unauthorized')) {
                            $mode = (string) (config('paypal.mode') ?? '');
                            Cache::forget('paypal_access_token:' . ($mode ?: 'default'));
                    }

                    // Exponential backoff: 1s, 2s, 4s
                    usleep($this->retryDelay * pow(2, $attempt - 1) * 1000);
                }
            }
        }

        // Jika semua retry gagal
        Log::error("{$context} Failed After {$this->maxRetries} Retries", [
            'error' => $lastException->getMessage(),
            'trace' => $lastException->getTraceAsString()
        ]);

        throw $lastException;
    }

    /**
     * 1. Membuat Order di PayPal (Redirect URL)
     */
    public function createPaypalOrder(Order $order)
    {
        try {
            return $this->retryApiCall(function () use ($order) {
                $provider = $this->getPayPalClient();
                $uniqueInvoiceId = $order->order_number . '-' . time();

                $itemTotal = 0;
                $items = [];

                foreach ($order->orderItems as $orderItem) {
                    $unitPrice = number_format($orderItem->price_usd, 2, '.', '');
                    $itemSubtotal = $orderItem->price_usd * $orderItem->quantity;
                    $itemTotal += $itemSubtotal;

                    $items[] = [
                        'name' => $orderItem->item_name,
                        // 'description' => $orderItem->item_description ?? 'Product from ' . config('app.name'),
                        'quantity' => (string)$orderItem->quantity,
                        'unit_amount' => [
                            'currency_code' => 'USD',
                            'value' => $unitPrice
                        ]
                    ];
                }
                
                $itemTotal = number_format($itemTotal, 2, '.', '');
                $shippingCost = number_format($order->shipping_cost, 2, '.', '');
                $taxAmount = number_format($order->tax_amount ?? 0, 2, '.', '');
                
                $totalAmount = number_format(
                    floatval($itemTotal) + floatval($shippingCost) + floatval($taxAmount),
                    2,
                    '.',
                    ''
                );

                // Buat Payload Order
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    
                    "application_context" => [
                        "return_url" => route('payment.paypal.success', ['order' => $order->id]),
                        "cancel_url" => route('payment.paypal.cancel', ['order' => $order->id]),
                        "brand_name" => config('app.name', 'Cecile Private Collection'),
                        "shipping_preference" => "NO_SHIPPING",
                    ],
                    
                    "purchase_units" => [
                        [
                            "reference_id" => $order->order_number,
                            "invoice_id" => $uniqueInvoiceId,
                            "description" => "Order #{$order->order_number}",
                            "amount" => [
                                "currency_code" => "USD",
                                'value' => $totalAmount,
                                'breakdown' => [
                                    'item_total' => [
                                        'currency_code' => 'USD',
                                        'value' => $itemTotal
                                    ],
                                    'shipping' => [
                                        'currency_code' => 'USD',
                                        'value' => $shippingCost
                                    ],
                                    'tax_total' => [
                                        'currency_code' => 'USD',
                                        'value' => $taxAmount
                                    ]
                                ]
                            ],
                            "items" => $items
                        ]
                    ]
                ]);

                // Cek Response
                if (isset($response['id']) && $response['id'] != null) {
                    foreach ($response['links'] as $link) {
                        if ($link['rel'] === 'approve') {
                            return [
                                'success' => true,
                                'redirect_url' => $link['href'],
                                'transaction_id' => $response['id']
                            ];
                        }
                    }
                }

                Log::error('PayPal Create Order Failed', ['response' => $response]);
                return [
                    'success' => false, 
                    'message' => 'Failed to create PayPal order: ' . ($response['message'] ?? 'Unknown error')
                ];

            }, 'PayPal Create Order');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'PayPal error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * 2. Capture Payment (Saat User Kembali dari PayPal)
     */
    public function capturePaypalPayment($orderId)
    {
        try {
            return $this->retryApiCall(function () use ($orderId) {
                $provider = $this->getPayPalClient();
                $response = $provider->capturePaymentOrder($orderId);

                if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                    $captureId = $response['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
                    $currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] ?? null;

                    return [
                        'success' => true,
                        'transaction_id' => $response['id'],
                        'capture_id' => $captureId, // Refund id
                        'status' => 'PAID',
                        'payer_email' => $response['payer']['email_address'] ?? null,
                        'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0,
                        'currency' => $currency,
                    ];
                }

                Log::error('PayPal Capture Failed', ['response' => $response]);
                return [
                    'success' => false, 
                    'message' => 'Payment capture failed',
                    'details' => $response
                ];

            }, 'PayPal Capture Payment');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'PayPal capture error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * 3. Get Order Details (untuk verifikasi)
     */
    public function getPaypalOrderStatus($paypalOrderId)
    {
        try {
            return $this->retryApiCall(function () use ($paypalOrderId) {
                $provider = $this->getPayPalClient();
                $orderDetails = $provider->showOrderDetails($paypalOrderId);

                if (!$orderDetails) {
                    return [
                        'success' => false,
                        'message' => 'Order not found'
                    ];
                }

                return [
                    'success' => true,
                    'paypal_order_id' => $orderDetails['id'],
                    'status' => $orderDetails['status'],
                    'create_time' => $orderDetails['create_time'] ?? null,
                    'update_time' => $orderDetails['update_time'] ?? null,
                    'payer_email' => $orderDetails['payer']['email_address'] ?? 'N/A',
                    'payer_name' => $orderDetails['payer']['name']['given_name'] ?? 'N/A',
                    'amount' => $orderDetails['purchase_units'][0]['amount']['value'] ?? 0,
                    'currency' => $orderDetails['purchase_units'][0]['amount']['currency_code'] ?? 'USD',
                ];

            }, 'PayPal Get Order Status');

        } catch (\Exception $e) {
            Log::error('PayPal Get Order Status Exception', [
                'message' => $e->getMessage(),
                'paypal_order_id' => $paypalOrderId
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to fetch PayPal order status: ' . $e->getMessage()
            ];
        }
    }
}








    // protected $apiInstance;

    // public function __construct()
    // {
    //     // 1. Setup Konfigurasi Xendit
    //     Configuration::setXenditKey(env('XENDIT_API_KEY'));
        
    //     // Inisialisasi API Instance (Sesuai dokumentasi link Anda)
    //     $this->apiInstance = new InvoiceApi();
    // }

    // public function createXenditInvoice(Order $order)
    // {
    //     try {
    //         // 2. Siapkan Data Request (Pakai Object 'CreateInvoiceRequest')
    //         $create_invoice_request = new CreateInvoiceRequest([
    //             'external_id' => (string) $order->order_number,
    //             'amount' => (float) $order->total_amount,
    //             'payer_email' => $order->contact_email,
    //             'description' => "Pembayaran Order #{$order->order_number}",
    //             'invoice_duration' => 86400, // Link aktif 24 jam
    //             'currency' => 'IDR',
    //             'should_send_email' => false, // PENTING: Matikan email bawaan Xendit
                
    //             // Opsional: Redirect user setelah sukses bayar (biar tidak bengong di Xendit)
    //             'success_redirect_url' => route('home', $order->order_number), 
    //         ]);

    //         // 3. Panggil Fungsi createInvoice
    //         // Dokumentasi: $apiInstance->createInvoice($create_invoice_request);
    //         $result = $this->apiInstance->createInvoice($create_invoice_request);

    //         // 4. Kembalikan Hasilnya (Objek Invoice)
    //         return [
    //             'success' => true,
    //             'invoice_id' => $result['id'],
    //             'invoice_url' => $result['invoice_url']
    //         ];

    //     } catch (\Xendit\XenditSdkException $e) {
    //         Log::error('Xendit Error: ' . $e->getMessage());
    //         Log::error('Full Error: ' . $e->getFullError());
            
    //         return [
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     } catch (\Exception $e) {
    //         Log::error('General Error: ' . $e->getMessage());
    //         return [
    //             'success' => false,
    //             'message' => 'Terjadi kesalahan sistem'
    //         ];
    //     }
    // }