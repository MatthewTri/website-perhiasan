<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Order\OrderCompletedMail;

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
        $now = now();
        
        // ⬅️ LOAD SHIPMENT RELATION
        $shippedOrders = Order::with('shipment')
                              ->where('status', 'SHIPPED')
                              ->whereHas('shipment', function($query) {
                                  $query->whereNotNull('shipped_at');
                              })
                              ->get();

        $completedCount = 0;

        foreach ($shippedOrders as $order) {
            // ⬅️ CEK SHIPMENT DULU
            if (!$order->shipment || !$order->shipment->shipped_at) {
                continue;
            }

            // ⬅️ AMBIL COUNTRY DARI SHIPMENT
            $deliveryDays = $order->shipment->country === 'Indonesia' ? 7 : 21;
            
            // ⬅️ HITUNG DEADLINE DARI shipment->shipped_at
            $deadline = $order->shipment->shipped_at->addDays($deliveryDays);
            
            // Cek apakah sudah melewati deadline
            if ($now->greaterThanOrEqualTo($deadline)) {
                $order->update([
                    'status' => 'COMPLETED',
                    'completed_at' => now(),
                ]);
                
                $completedCount++;
                
                Log::info('✅ Order auto-completed', [
                    'order_number' => $order->order_number,
                    'shipped_at' => $order->shipment->shipped_at->toDateTimeString(),
                    'country' => $order->shipment->country,
                    'delivery_days' => $deliveryDays,
                    'completed_at' => now()->toDateTimeString(),
                ]);

                // Send completion email to customer
                $magicLink = route('order.track', $order->guest_token);
                try {
                    Mail::to($order->contact_email)->send(new OrderCompletedMail($order, $magicLink));
                    Log::info('✅ Auto-complete email sent to customer', [
                        'order_number' => $order->order_number,
                        'customer_email' => $order->contact_email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send auto-complete email', [
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage()
                    ]);
                }

                $this->info("✅ Order {$order->order_number} marked as COMPLETED & email sent");
            }
        }

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