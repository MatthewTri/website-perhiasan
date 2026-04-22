<?php

namespace App\Mail\Order;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $magicLink;
    public $cancelReason;

    public function __construct(Order $order, string $magicLink, ?string $cancelReason = null)
    {
        $this->order = $order;
        $this->magicLink = $magicLink;
        $this->cancelReason = $cancelReason;
    }

    public function build()
    {
        return $this->subject('Order Cancellation - Order #' . $this->order->order_number)
                    ->view('mail.order.cancelled');
    }
}
