<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Payment Details - Order #{{ $order->order_number }}</title>
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
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Payment Details</span>
                        </td>
                    </tr>

                    <!-- Title Section -->
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Thank You for Your Order
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                Order #{{ $order->order_number }}
                            </p>
                            <p style="color: #78716c; font-size: 12px; margin: 10px 0 0 0; line-height: 1.6;">
                                Please complete payment within <strong>3 days</strong>.
                                @if(!empty($order->payment_due_at))
                                    If payment is not received by <strong>{{ optional($order->payment_due_at)->format('M d, Y H:i') }}</strong>, your order will be automatically cancelled.
                                @else
                                    If payment is not received within 3 days, your order will be automatically cancelled.
                                @endif
                            </p>
                        </td>
                    </tr>

                    @if($order->payment_gateway === 'MANUAL')
                    <!-- BCA Bank Transfer Section -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E3CFCF; border-bottom: 1px solid #E3CFCF;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 20px 0;">
                                            Bank Transfer Details
                                        </p>
                                        
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 400px; margin: 0 auto;">
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                                    <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Bank Name</span>
                                                    <span style="font-size: 14px; color: #1c1917; font-weight: 500;">BCA (Bank Central Asia)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                                    <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Account Number</span>
                                                    <span style="font-size: 16px; color: #1c1917; font-weight: 600; letter-spacing: 0.5px;">{{ config('services.bca.account_number') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                                    <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Account Name</span>
                                                    <span style="font-size: 14px; color: #1c1917; font-weight: 500;">{{ config('services.bca.account_name') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 20px 0 0 0;">
                                                    <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Amount</span>
                                                    <span style="font-size: 32px; color: #1c1917; font-weight: 400;">IDR {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #fafaf9; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 13px; color: #44403c; margin: 0; line-height: 1.6;">
                                    After completing your transfer, please upload your payment proof via the link below so we can verify and process your order.
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    @else
                    <!-- PayPal Payment Section -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E3CFCF; border-bottom: 1px solid #E3CFCF;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 20px 0;">
                                            PayPal Payment
                                        </p>
                                        
                                        <p style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0;">Total Amount</p>
                                        <h2 style="font-size: 32px; color: #1c1917; margin: 0; font-weight: 400;">
                                            USD {{ number_format($order->total_amount, 2, '.', ',') }}
                                        </h2>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">How to Complete Payment</p>
                                <p style="font-size: 13px; color: #44403c; margin: 0; line-height: 1.6;">
                                    To complete your payment, please click the button below to access your order tracking page, 
                                    then click <strong>"Pay with PayPal"</strong> to proceed with checkout.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    <!-- Admin Notes (if any) -->
                    @if($order->admin_notes)
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Note from Admin</p>
                                <p style="font-size: 14px; color: #44403c; margin: 0; font-style: italic; line-height: 1.6;">
                                    "{{ $order->admin_notes }}"
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <a href="{{ $magicLink }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                View Order & Payment Details
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