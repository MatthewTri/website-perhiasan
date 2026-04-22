<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\Order\OrderCancelledMail;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Membatalkan order Invoice sent yang sudah lewat batas waktu (72 jam)';

    public function handle()
    {
        // 1. Orders are expired when now >= payment_due_at
        $now = Carbon::now();

        // TIPS TESTING: Untuk tes cepat (1 menit)
        // $timeLimit = Carbon::now()->subMinute(1);

        $this->info("🔍 Mencari order INVOICE_SENT yang sudah melewati payment_due_at: " . $now->format('Y-m-d H:i:s'));

        // 2. Cari Order INVOICE_SENT yang kadaluarsa
        $expiredOrders = Order::where('status', 'INVOICE_SENT')
                              ->whereNotNull('payment_due_at')
                              ->where('payment_due_at', '<=', $now)
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
                $overdueHours = $order->payment_due_at
                    ? Carbon::parse($order->payment_due_at)->diffInHours($now)
                    : null;

                Log::info("✅ Order #{$order->order_number} otomatis dibatalkan (invoice expired)", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_due_at' => optional($order->payment_due_at)->toDateTimeString(),
                    'overdue_hours' => $overdueHours,
                ]);

                // Send cancellation email to customer
                $magicLink = route('order.track', $order->guest_token);
                try {
                    Mail::to($order->contact_email)->queue(new OrderCancelledMail($order, $magicLink, 'expired_unpaid'));
                    Log::info('✅ Scheduler: cancellation email sent', [
                        'order_number' => $order->order_number,
                        'customer_email' => $order->contact_email,
                    ]);
                } catch (\Exception $e) {
                    Log::error('❌ Scheduler: failed to send cancellation email', [
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                    ]);
                }

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