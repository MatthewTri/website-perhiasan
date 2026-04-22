<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Order\OrderCompletedMail;
use Carbon\Carbon;

class AutoCompleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically complete orders that have been shipped for more than 7/21 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $completedCount = 0;

        Order::query()
            ->with('shipment')
            ->where('status', 'SHIPPED')
            ->whereHas('shipment', function ($query) use ($now) {
                $query->whereNotNull('auto_complete_due_at')
                    ->where('auto_complete_due_at', '<=', $now);
            })
            ->chunkById(200, function ($orders) use (&$completedCount, $now) {
                foreach ($orders as $order) {
                    if (!$order->shipment || !$order->shipment->auto_complete_due_at) {
                        continue;
                    }

                    // Idempotent update: only transition SHIPPED -> COMPLETED once
                    $updated = Order::where('id', $order->id)
                        ->where('status', 'SHIPPED')
                        ->update([
                            'status' => 'COMPLETED',
                            'completed_at' => now(),
                        ]);

                    if ($updated !== 1) {
                        continue;
                    }

                    $completedCount++;

                    Log::info('✅ Order auto-completed', [
                        'order_number' => $order->order_number,
                        'shipped_at' => optional($order->shipment->shipped_at)->toDateTimeString(),
                        'auto_complete_due_at' => optional($order->shipment->auto_complete_due_at)->toDateTimeString(),
                        'country' => $order->shipment->country,
                        'completed_at' => now()->toDateTimeString(),
                    ]);

                    // Send completion email to customer
                    $magicLink = route('order.track', $order->guest_token);
                    try {
                        Mail::to($order->contact_email)->queue(new OrderCompletedMail($order, $magicLink));
                        Log::info('✅ Auto-complete email sent to customer', [
                            'order_number' => $order->order_number,
                            'customer_email' => $order->contact_email,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send auto-complete email', [
                            'order_number' => $order->order_number,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    $this->info("✅ Order {$order->order_number} marked as COMPLETED & email queued");
                }
            });

        if ($completedCount > 0) {
            Log::info("🎉 Auto-Complete Summary: {$completedCount} orders completed");
            $this->info("🎉 Total {$completedCount} orders marked as COMPLETED");
        } else {
            Log::info('ℹ️ No orders to auto-complete at this time');
            $this->info('ℹ️ No orders to auto-complete');
        }

        return Command::SUCCESS;
    }
}