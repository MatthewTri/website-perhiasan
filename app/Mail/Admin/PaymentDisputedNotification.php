<?php

namespace App\Mail\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentDisputedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $disputeId;
    public $reason;
    public $amount;

    public function __construct(Order $order, ?string $disputeId = null, string $reason = 'UNKNOWN', $amount = null)
    {
        $this->order = $order;
        $this->disputeId = $disputeId;
        $this->reason = $reason;
        $this->amount = $amount;
    }

    public function build()
    {
        return $this->subject('Payment Disputed - Order #' . $this->order->order_number)
            ->view('mail.admin.payment-disputed');
    }
}
