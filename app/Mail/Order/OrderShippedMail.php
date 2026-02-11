<?php

namespace App\Mail\Order;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShippedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $magicLink;

    public function __construct(Order $order, string $magicLink)
    {
        $this->order = $order;
        $this->magicLink = $magicLink;
    }

    public function build()
    {
        return $this->subject('Your Order Has Been Shipped - Order #' . $this->order->order_number)
                    ->view('mail.order.shipped');
    }
}
