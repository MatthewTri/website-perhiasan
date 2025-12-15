<?php

namespace App\Services;

use App\Models\Order;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $apiInstance;

    public function __construct()
    {
        // 1. Setup Konfigurasi Xendit
        Configuration::setXenditKey(env('XENDIT_API_KEY'));
        
        // Inisialisasi API Instance (Sesuai dokumentasi link Anda)
        $this->apiInstance = new InvoiceApi();
    }

    /**
     * Membuat Invoice Xendit (IDR)
     */
    public function createXenditInvoice(Order $order)
    {
        try {
            // 2. Siapkan Data Request (Pakai Object 'CreateInvoiceRequest')
            $create_invoice_request = new CreateInvoiceRequest([
                'external_id' => (string) $order->order_number,
                'amount' => (float) $order->total_amount,
                'payer_email' => $order->contact_email,
                'description' => "Pembayaran Order #{$order->order_number}",
                'invoice_duration' => 86400, // Link aktif 24 jam
                'currency' => 'IDR',
                'should_send_email' => false, // PENTING: Matikan email bawaan Xendit
                
                // Opsional: Redirect user setelah sukses bayar (biar tidak bengong di Xendit)
                'success_redirect_url' => route('home', $order->order_number), 
            ]);

            // 3. Panggil Fungsi createInvoice
            // Dokumentasi: $apiInstance->createInvoice($create_invoice_request);
            $result = $this->apiInstance->createInvoice($create_invoice_request);

            // 4. Kembalikan Hasilnya (Objek Invoice)
            return [
                'success' => true,
                'invoice_id' => $result['id'],
                'invoice_url' => $result['invoice_url']
            ];

        } catch (\Xendit\XenditSdkException $e) {
            Log::error('Xendit Error: ' . $e->getMessage());
            Log::error('Full Error: ' . $e->getFullError());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            Log::error('General Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ];
        }
    }
}