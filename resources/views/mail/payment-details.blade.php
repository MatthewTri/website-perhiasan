<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 20px; margin: 20px 0; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; color: #6b7280; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Details</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <div class="content">
            <p>Hi Customer,</p>
            
            <p>Thank you for your order! Here are your payment details:</p>

            @if($order->payment_gateway === 'MANUAL')
            <!-- BCA Transfer Instructions -->
            <h3>BCA Bank Transfer</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Bank Name:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>BCA (Bank Central Asia)</strong></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Account Number:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>{{ config('services.bca.account_number') }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Account Name:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>{{ config('services.bca.account_name') }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">Total Amount:</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong style="color: #3b82f6; font-size: 18px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>

            <p style="margin-top: 20px;">After payment, please upload your proof of transfer via the link below.</p>
            
            @else
            <!-- ⬅️ PAYPAL: HANYA INFO, TIDAK ADA CHECKOUT LINK -->
            <h3>PayPal Payment</h3>
            <p>Total Amount: <strong style="color: #3b82f6; font-size: 18px;">${{ number_format($order->total_amount, 2) }}</strong></p>

            <div style="background: #e0f2fe; border-left: 4px solid #0284c7; padding: 15px; margin: 20px 0;">
                <p style="margin: 0; color: #0c4a6e; font-weight: 500;">
                    To complete your payment, please click the button below to access your order tracking page, 
                    then click <strong>"Pay with PayPal"</strong> to proceed with checkout.
                </p>
            </div>
            @endif

            @if($order->admin_notes)
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
                <strong>Note from Admin:</strong>
                <p style="margin: 5px 0 0 0;">{{ $order->admin_notes }}</p>
            </div>
            @endif

            <!-- ⬅️ MAIN CTA: MAGIC LINK (UNTUK SEMUA PAYMENT METHOD) -->
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ $magicLink }}" class="button">View Order & Payment Details</a>
            </div>
        </div>

        <div class="footer">
            <p>Order Number: {{ $order->order_number }}</p>
            <p>If you have any questions, please contact us at {{ config('mail.from.address') }}</p>
        </div>
    </div>
</body>
</html>