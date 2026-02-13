<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Confirmation - #{{ $order->order_number }}</title>
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
            .item-table td { display: block !important; width: 100% !important; text-align: left !important; }
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
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Order Confirmation</span>
                        </td>
                    </tr>

                    <!-- Title Section -->
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 30px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Thank You for Your Order
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0 0 10px 0; line-height: 1.6;">
                                We've received your order and our team will review it shortly.
                            </p>
                            <p style="color: #a8a29e; font-size: 12px; margin: 0;">
                                Order #{{ $order->order_number }}
                            </p>
                        </td>
                    </tr>

                    <!-- Order Summary Box -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E8B49E; border-bottom: 1px solid #E8B49E;">
                                <tr>
                                    <td style="padding: 25px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 20px 0; text-align: center;">
                                            Order Summary
                                        </p>
                                        
                                        <!-- Items List -->
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="item-table">
                                            @foreach($order->orderItems as $item)
                                            <tr>
                                                <td style="padding: 12px 0; border-bottom: 1px solid #f5f5f4;">
                                                    <span style="font-size: 14px; color: #1c1917; font-weight: 500; display: block;">{{ $item->item_name }}</span>
                                                    @if($item->color || $item->size)
                                                    <span style="font-size: 12px; color: #78716c; display: block; margin-top: 4px;">
                                                        @if($item->color)Color: {{ $item->color }}@endif
                                                        @if($item->color && $item->size) • @endif
                                                        @if($item->size)Size: {{ $item->size }}@endif
                                                    </span>
                                                    @endif
                                                </td>
                                                <td align="right" style="padding: 12px 0; border-bottom: 1px solid #f5f5f4; vertical-align: top;">
                                                    <span style="font-size: 13px; color: #78716c; white-space: nowrap;">× {{ $item->quantity }}</span>
                                                </td>
                                                <td align="right" style="padding: 12px 0 12px 15px; border-bottom: 1px solid #f5f5f4; vertical-align: top;">
                                                    <span style="font-size: 14px; color: #1c1917; font-weight: 500; white-space: nowrap;">
                                                        @if($order->payment_preference === 'PAYPAL')
                                                            ${{ number_format($item->price_usd * $item->quantity, 2) }}
                                                        @else
                                                            Rp {{ number_format($item->price_idr * $item->quantity, 0, ',', '.') }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            
                                            <!-- Subtotal -->
                                            <tr>
                                                <td colspan="2" style="padding: 15px 0 8px 0;">
                                                    <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Subtotal</span>
                                                </td>
                                                <td align="right" style="padding: 15px 0 8px 15px;">
                                                    <span style="font-size: 14px; color: #1c1917;">
                                                        @if($order->payment_preference === 'PAYPAL')
                                                            ${{ number_format($order->subtotal_usd, 2) }}
                                                        @else
                                                            Rp {{ number_format($order->subtotal_idr, 0, ',', '.') }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            
                                            <!-- Shipping (TBD) -->
                                            <tr>
                                                <td colspan="2" style="padding: 8px 0;">
                                                    <span style="font-size: 12px; color: #78716c; text-transform: uppercase; letter-spacing: 0.5px;">Shipping</span>
                                                </td>
                                                <td align="right" style="padding: 8px 0 8px 15px;">
                                                    <span style="font-size: 13px; color: #a8a29e; font-style: italic;">To be calculated</span>
                                                </td>
                                            </tr>
                                            
                                            <!-- Estimated Total -->
                                            <tr>
                                                <td colspan="2" style="padding: 15px 0 0 0; border-top: 2px solid #E8B49E;">
                                                    <span style="font-size: 12px; color: #1c1917; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Estimated Total</span>
                                                </td>
                                                <td align="right" style="padding: 15px 0 0 15px; border-top: 2px solid #E8B49E;">
                                                    <span style="font-size: 18px; color: #1c1917; font-weight: 600;">
                                                        @if($order->payment_preference === 'PAYPAL')
                                                            ${{ number_format($order->subtotal_usd, 2) }}+
                                                        @else
                                                            Rp {{ number_format($order->subtotal_idr, 0, ',', '.') }}+
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Shipping Address -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #a8a29e; margin: 0 0 12px 0;">Shipping Address</p>
                            <div style="background-color: #fafaf9; padding: 18px; border-left: 2px solid #E8B49E;">
                                <p style="font-size: 14px; color: #1c1917; margin: 0 0 6px 0; font-weight: 500;">
                                    {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                                </p>
                                <p style="font-size: 13px; color: #44403c; margin: 0; line-height: 1.6;">
                                    {{ $order->shipment->address_line1 }}<br>
                                    {{ $order->shipment->city }}@if($order->shipment->province), {{ $order->shipment->province }}@endif @if($order->shipment->postal_code){{ $order->shipment->postal_code }}@endif<br>
                                    {{ $order->shipment->country }}<br>
                                    <span style="color: #78716c;">Phone: {{ $order->shipment->phone }}</span>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Payment Preference -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #a8a29e; margin: 0 0 12px 0;">Payment Preference</p>
                            <div style="background-color: #fef8f3; padding: 18px; border-left: 2px solid #E8B49E; text-align: center;">
                                <span style="font-size: 16px; color: #1c1917; font-weight: 500;">
                                    @if($order->payment_preference === 'PAYPAL')
                                        PayPal
                                    @else
                                        Bank Transfer
                                    @endif
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- Next Steps -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #f0f9ff; padding: 20px; border-left: 2px solid #0ea5e9;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #0c4a6e; margin: 0 0 10px 0;">What Happens Next?</p>
                                <ol style="font-size: 13px; color: #0c4a6e; margin: 0; padding-left: 20px; line-height: 1.8;">
                                    <li>Our team will review your order and calculate shipping costs</li>
                                    <li>You'll receive payment instructions via email within 24 hours</li>
                                    <li>Complete your payment using your preferred method</li>
                                    <li>We'll process and ship your order once payment is confirmed</li>
                                </ol>
                            </div>
                        </td>
                    </tr>

                    @if($order->order_notes)
                    <!-- Customer Notes -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #fffbeb; padding: 18px; border-left: 2px solid #f59e0b;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #92400e; margin: 0 0 8px 0;">Your Note</p>
                                <p style="font-size: 13px; color: #78350f; margin: 0; font-style: italic; line-height: 1.6;">
                                    "{{ $order->order_notes }}"
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-bottom: 40px;">
                            <a href="{{ $magicLink }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Track Your Order
                            </a>
                            <p style="font-size: 11px; color: #a8a29e; margin: 15px 0 0 0;">
                                Bookmark this link to check your order status anytime
                            </p>
                        </td>
                    </tr>

                    <!-- Gratitude Message -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 50px 50px;">
                            <div style="text-align: center; padding: 30px 20px; background-color: #fafaf9; border-radius: 4px;">
                                <p style="font-family: 'Georgia', serif; font-size: 18px; color: #1c1917; margin: 0 0 12px 0; font-style: italic; line-height: 1.4;">
                                    "Each piece is crafted with care,<br>especially for you"
                                </p>
                                <p style="font-size: 13px; color: #78716c; margin: 0; line-height: 1.6;">
                                    Thank you for choosing Cecile Private Collection.<br>
                                    We're honored to be part of your jewelry story.
                                </p>
                            </div>
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
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0 0 15px 0;">
                                Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0;">
                                Questions? Contact us at {{ config('mail.from.address') }}
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
