<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WebhookController extends Controller
{
    /**
     * Handle Webhook dari Xendit (Invoice Paid / Expired)
     */
    public function handleXendit(Request $request)
    {
        // 1. LOGGING (Sangat Penting untuk Debugging)
        // Cek storage/logs/laravel.log untuk melihat data mentah dari Xendit
        Log::info('Webhook Xendit Masuk:', $request->all());

        try {
            // 2. AMBIL DATA DARI PAYLOAD JSON XENDIT
            // 'external_id' adalah Order Number kita (misal: INV-2025-001)
            $externalId = $request->input('external_id'); 
            $status = $request->input('status'); // 'PAID', 'SETTLED', atau 'EXPIRED'
            
            // Ambil detail bank. Xendit kadang kirim di 'payment_channel' (MANDIRI),
            // kadang di 'payment_method' (BANK_TRANSFER). Kita prioritas channel.
            $paymentDetail = $request->input('payment_channel') ?? $request->input('payment_method');

            // 3. CARI DATA ORDER DI DATABASE KITA
            $order = Order::where('order_number', $externalId)->first();

            // Jika order tidak ditemukan (misal data testing lama), kembalikan 404
            if (!$order) {
                Log::warning("Webhook Xendit: Order dengan ID $externalId tidak ditemukan.");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 4. LOGIKA UPDATE STATUS (PAID / SETTLED)
            if ($status == 'PAID' || $status == 'SETTLED') {
                
                // Cek dulu biar tidak update double (Idempotency)
                if ($order->status == 'PAID') {
                    Log::info("Webhook Xendit: Order $externalId sudah lunas sebelumnya. Skip.");
                    return response()->json(['message' => 'Order already paid'], 200);
                }

                // Lakukan Update ke Database
                $order->update([
                    'status' => 'PAID',               // Status Utama Order
                    'payment_method' => $paymentDetail, // Detail Bank (misal: 'MANDIRI', 'OVO')
                    'paid_at' => Carbon::now(),       // Waktu pembayaran diterima
                ]);

                Log::info("Webhook Xendit: Sukses update Order $externalId menjadi PAID via $paymentDetail.");
                
                // (Opsional) Kirim Email 'Terima Kasih' ke User disini
            } 
            
            // 5. LOGIKA UPDATE STATUS (EXPIRED)
            else if ($status == 'EXPIRED') {
                // Hanya ubah jadi expired jika statusnya belum PAID
                if ($order->status != 'PAID') {
                    $order->update(['status' => 'EXPIRED']);
                    Log::info("Webhook Xendit: Order $externalId telah EXPIRED.");
                }
            }

            // 6. RESPONSE WAJIB KE XENDIT
            // Harus return 200 OK, kalau tidak Xendit akan kirim ulang terus menerus
            return response()->json(['message' => 'Webhook received successfully'], 200);

        } catch (\Exception $e) {
            // Tangkap jika ada error codingan kita
            Log::error('Webhook Xendit Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}