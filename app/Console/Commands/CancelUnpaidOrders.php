<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Membatalkan order Invoice sent yang sudah lewat batas waktu (24 jam)';

    public function handle()
    {
        // 1. Tentukan Batas Waktu (72 jam yang lalu)
        $timeLimit = Carbon::now()->subHours(72);

        // TIPS TESTING: Untuk tes cepat (1 menit)
        // $timeLimit = Carbon::now()->subMinute(1);

        $this->info("🔍 Mencari order INVOICE_SENT sebelum: " . $timeLimit->format('Y-m-d H:i:s'));

        // 2. Cari Order INVOICE_SENT yang kadaluarsa
        $expiredOrders = Order::where('status', 'INVOICE_SENT')
                              ->where('updated_at', '<', $timeLimit)
                              ->with('orderItems.product')
                              ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('✅ Tidak ada order yang perlu dibatalkan.');
            Log::info('Scheduler: No expired INVOICE_SENT orders found.');
            return Command::SUCCESS;
        }

        $this->info("📦 Ditemukan {$expiredOrders->count()} order yang akan dibatalkan...");

        $cancelledCount = 0;

        // 3. Loop setiap order untuk dibatalkan
        foreach ($expiredOrders as $order) {
            try {
                // A. Update Status jadi CANCELLED
                $order->update([
                    'status' => 'CANCELLED',
                    'cancelled_at' => now(), // ⬅️ OPSIONAL: Track waktu cancel
                ]);

                // B. Log untuk monitoring
                Log::info("✅ Order #{$order->order_number} otomatis dibatalkan (unpaid > 24h)", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'created_at' => $order->created_at,
                    'expired_hours' => $order->created_at->diffInHours(now()),
                ]);

                $this->info("✅ Order #{$order->order_number} berhasil dibatalkan.");
                $cancelledCount++;

            } catch (\Exception $e) {
                // Handle error per order (jangan stop semua)
                Log::error("❌ Gagal cancel order #{$order->order_number}", [
                    'error' => $e->getMessage(),
                ]);
                $this->error("❌ Gagal cancel order #{$order->order_number}: {$e->getMessage()}");
            }
        }

        // 4. Summary
        $this->info("🎯 Total order dibatalkan: {$cancelledCount}/{$expiredOrders->count()}");
        Log::info("Scheduler: Cancelled {$cancelledCount} unpaid orders.");

        return Command::SUCCESS;
    }
}