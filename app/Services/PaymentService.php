<?php

namespace App\Services;

use App\Models\Order;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentService
{
    /**
     * 1. Membuat Order di PayPal (Redirect URL)
     */
    public function createPaypalOrder(Order $order)
    {
        try {
            // Inisialisasi PayPal Client
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            // Buat Payload Order
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                
                "application_context" => [
                    "return_url" => route('payment.paypal.success', ['order' => $order->id]),
                    "cancel_url" => route('payment.paypal.cancel', ['order' => $order->id]),
                    "brand_name" => config('app.name', 'Cecile Private Collection'),
                    "shipping_preference" => "NO_SHIPPING", // PENTING: Tidak tanya alamat lagi
                ],
                
                "purchase_units" => [
                    [
                        "reference_id" => $order->order_number,
                        "description" => "Order #{$order->order_number}",
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($order->total_amount, 2, '.', ''),
                            "breakdown" => [
                                "item_total" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($order->subtotal, 2, '.', '')
                                ],
                                "shipping" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($order->shipping_cost, 2, '.', '')
                                ],
                                "tax_total" => [
                                    "currency_code" => "USD",
                                    "value" => number_format($order->tax_amount, 2, '.', '')
                                ],
                            ]
                        ],
                        "items" => $this->formatPayPalItems($order)
                    ]
                ]
            ]);

            // Cek Response
            if (isset($response['id']) && $response['id'] != null) {
                // Cari Link 'approve'
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

            // Jika gagal
            Log::error('PayPal Create Order Failed', ['response' => $response]);
            return [
                'success' => false, 
                'message' => 'Failed to create PayPal order: ' . ($response['message'] ?? 'Unknown error')
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'PayPal error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * 2. Format Order Items untuk PayPal
     */
    private function formatPayPalItems(Order $order)
    {
        $items = [];
        
        foreach ($order->orderItems as $item) {
            $items[] = [
                "name" => substr($item->item_name, 0, 127), // PayPal max 127 chars
                "description" => substr($item->item_description ?? '', 0, 127),
                "unit_amount" => [
                    "currency_code" => "USD",
                    "value" => number_format($item->price, 2, '.', '')
                ],
                "quantity" => (string) $item->quantity,
                "category" => "PHYSICAL_GOODS"
            ];
        }
        
        return $items;
    }

    /**
     * 3. Capture Payment (Saat User Kembali dari PayPal)
     */
    public function capturePaypalPayment($orderId)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            // Capture Payment
            $response = $provider->capturePaymentOrder($orderId);

            // Cek Status
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                return [
                    'success' => true,
                    'transaction_id' => $response['id'],
                    'status' => 'PAID',
                    'payer_email' => $response['payer']['email_address'] ?? null,
                    'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0
                ];
            }

            Log::error('PayPal Capture Failed', ['response' => $response]);
            return [
                'success' => false, 
                'message' => 'Payment capture failed',
                'details' => $response
            ];

        } catch (\Exception $e) {
            Log::error('PayPal Capture Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'PayPal capture error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * 4. Get Order Details (untuk verifikasi)
     */
    public function getPaypalOrderDetails($orderId)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            return $provider->showOrderDetails($orderId);

        } catch (\Exception $e) {
            Log::error('PayPal Get Order Details Exception', [
                'message' => $e->getMessage()
            ]);
            
            return null;
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
}