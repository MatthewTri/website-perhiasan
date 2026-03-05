<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Refund Processed - Order #{{ $order->order_number }}</title>
    <style>
        /* Base Reset */
        body { margin: 0; padding: 0; width: 100% !important; background-color: #f9fafb; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        a { text-decoration: none; color: inherit; }
        
        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .main-container { width: 100% !important; max-width: 100% !important; }
            .mobile-stack { display: block !important; width: 100% !important; padding-bottom: 10px; }
            .mobile-center { text-align: center !important; }
            .inner-padding { padding: 30px 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f9fafb;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f9fafb">
        <tr>
            <td align="center" valign="top" style="padding-top: 40px; padding-bottom: 40px;">
                
                <table class="main-container" width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="max-width: 600px; width: 100%; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 40px 0 10px 0;">
                            <span style="font-family: 'Georgia', serif; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; color: #1c1917; font-weight: bold;">
                                Cecile Private Collection
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 40px;">
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Refund Confirmation</span>
                        </td>
                    </tr>

                    <!-- Title Section -->
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 30px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Refund Processed Successfully
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                Your refund request for Order #{{ $order->order_number }} has been approved and processed.
                            </p>
                        </td>
                    </tr>

                    <!-- Refund Amount Section -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E3CFCF; border-bottom: 1px solid #E3CFCF;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 20px 0;">
                                            Refund Amount
                                        </p>
                                        
                                        <h2 style="font-size: 36px; color: #1c1917; margin: 0; font-weight: 400;">
                                            @if($order->currency === 'USD')
                                                ${{ number_format($order->refund_amount, 2) }}
                                            @else
                                                Rp {{ number_format($order->refund_amount, 0, ',', '.') }}
                                            @endif
                                        </h2>
                                        
                                        <p style="font-size: 12px; color: #78716c; margin: 15px 0 0 0;">
                                            Original Order Total: 
                                            @if($order->currency === 'USD')
                                                ${{ number_format($order->total_amount, 2) }}
                                            @else
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Refund Details -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Order Number</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500;">{{ $order->order_number }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Refund Date</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500;">{{ $order->refunded_at->format('F d, Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Payment Method</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500;">
                                            @if($order->payment_gateway === 'PAYPAL')
                                                PayPal
                                            @else
                                                Bank Transfer (BCA)
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Refund Processing Info -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Processing Time</p>
                                <p style="font-size: 13px; color: #44403c; margin: 0; line-height: 1.6;">
                                    @if($order->payment_gateway === 'PAYPAL')
                                        Your refund has been processed back to your PayPal account. It may take 5-10 business days for the funds to appear in your account, depending on your PayPal settings.
                                    @else
                                        Your refund will be processed via bank transfer to your original payment account. Please allow 3-7 business days for the funds to appear in your account.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Order Summary -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #a8a29e; margin: 0 0 15px 0;">Refunded Items</p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f5f5f4;">
                                        <span style="font-size: 14px; color: #1c1917;">{{ $item->item_name }}</span>
                                        @if($item->color_name || $item->size_name)
                                        <br>
                                        <span style="font-size: 12px; color: #78716c;">
                                            @if($item->color_name)Color: {{ $item->color_name }}@endif
                                            @if($item->color_name && $item->size_name) • @endif
                                            @if($item->size_name)Size: {{ $item->size_name }}@endif
                                        </span>
                                        @endif
                                    </td>
                                    <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f5f5f4;">
                                        <span style="font-size: 14px; color: #78716c;">× {{ $item->quantity }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <!-- Message Section -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #fafaf9; padding: 20px; text-align: center; border-radius: 4px;">
                                <p style="font-family: 'Georgia', serif; font-size: 16px; color: #1c1917; margin: 0 0 10px 0; font-style: italic;">
                                    "We're sorry to see you go"
                                </p>
                                <p style="font-size: 13px; color: #78716c; margin: 0; line-height: 1.6;">
                                    Thank you for giving us the opportunity to serve you. We hope to see you again in the future. If you have any questions about your refund, please don't hesitate to contact us.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <a href="{{ $magicLink }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                View Order Details
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="border-top: 1px solid #f5f5f4; padding: 30px; background-color: #ffffff;">
                            <p style="font-family: 'Georgia', serif; color: #a8a29e; font-size: 12px; font-style: italic; margin-bottom: 10px;">
                                Cecile Private Collection
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 5px 0;">
                                Order #{{ $order->order_number }}
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0;">
                                Questions? Contact: {{ config('mail.from.address') }} | WhatsApp: +6287766999564
                            </p>
                        </td>
                    </tr>

                </table>
                
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr><td height="40">&nbsp;</td></tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
