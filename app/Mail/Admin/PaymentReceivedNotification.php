<?php

namespace App\Mail\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceivedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $paymentType; // 'manual' atau 'paypal'

    public function __construct(Order $order, string $paymentType = 'manual')
    {
        $this->order = $order;
        $this->paymentType = $paymentType;
    }

    public function build()
    {
        $subject = $this->paymentType === 'manual' 
            ? 'Payment Proof Received - Order #' . $this->order->order_number
            : 'Payment Completed - Order #' . $this->order->order_number;

        return $this->subject($subject)
                    ->view('mail.admin.payment-received');
    }
}
