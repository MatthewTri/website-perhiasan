<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Order Received - Order #{{ $order->order_number }}</title>
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
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Admin Notification</span>
                        </td>
                    </tr>

                    <!-- Title Section -->
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                New Order Received
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                A customer has placed a new order
                            </p>
                        </td>
                    </tr>

                    <!-- Order Info Banner -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 2px solid #E3CFCF; border-bottom: 2px solid #E3CFCF;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px; background-color: #faf7f7;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 10px 0;">
                                            Order Number
                                        </p>
                                        <h2 style="font-family: 'Georgia', serif; font-size: 28px; color: #78716c; margin: 0 0 15px 0; font-weight: 400; letter-spacing: 0.5px;">
                                            {{ $order->order_number }}
                                        </h2>
                                        <p style="font-size: 12px; color: #78716c; margin: 0;">
                                            Placed on {{ $order->created_at->format('l, F j, Y \a\t g:i A') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Customer Information -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1c1917; margin-bottom: 20px; border-bottom: 1px solid #f5f5f4; padding-bottom: 10px;">
                                Customer Details
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 15px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Customer Name</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500;">
                                            {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                                        </span>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 15px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Contact Email</span>
                                        <a href="mailto:{{ $order->contact_email }}" style="font-size: 14px; color: #1c1917; text-decoration: none; border-bottom: 1px dotted #E3CFCF;">
                                            {{ $order->contact_email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 15px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone Number</span>
                                        <span style="font-size: 14px; color: #1c1917;">{{ $order->shipment->phone }}</span>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 15px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Payment Preference</span>
                                        <span style="background-color: #f5f5f4; color: #57534e; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 2px;">
                                            {{ $order->payment_preference === 'MANUAL' ? 'Bank Transfer (BCA)' : 'PayPal' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Shipping Address -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1c1917; margin-bottom: 15px; border-bottom: 1px solid #f5f5f4; padding-bottom: 10px;">
                                Shipping Address
                            </p>
                            <p style="font-size: 14px; color: #44403c; margin: 0; line-height: 1.6;">
                                {{ $order->shipment->address_line1 }}<br>
                                {{ $order->shipment->city }}, {{ $order->shipment->province }} {{ $order->shipment->postal_code }}<br>
                                {{ $order->shipment->country }}
                            </p>
                        </td>
                    </tr>

                    <!-- Order Items -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1c1917; margin-bottom: 20px; border-bottom: 1px solid #f5f5f4; padding-bottom: 10px;">
                                Order Items
                            </p>
                            
                            @foreach($order->orderItems as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px; background-color: #fafaf9; padding: 15px; border-radius: 4px;">
                                <tr>
                                    <td width="70%" valign="top">
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500; display: block; margin-bottom: 4px;">{{ $item->item_name }}</span>
                                        <span style="font-size: 12px; color: #a8a29e;">Qty: {{ $item->quantity }}</span>
                                        @if($item->color || $item->size)
                                        <br>
                                        <span style="font-size: 11px; color: #78716c;">
                                            @if($item->color) Color: {{ $item->color }} @endif
                                            @if($item->color && $item->size) | @endif
                                            @if($item->size) Size: {{ $item->size }} @endif
                                        </span>
                                        @endif
                                    </td>
                                    <td width="30%" valign="top" align="right">
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 600;">
                                            @if($order->currency === 'USD')
                                                ${{ number_format($item->price_usd * $item->quantity, 2) }}
                                            @else
                                                Rp {{ number_format($item->price_idr * $item->quantity, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            @endforeach

                            <!-- Subtotal -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 2px solid #1c1917; padding-top: 15px; margin-top: 10px;">
                                <tr>
                                    <td style="padding: 5px 0;">
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 600;">Subtotal</span>
                                    </td>
                                    <td align="right" style="padding: 5px 0;">
                                        <span style="font-family: 'Georgia', serif; font-size: 18px; color: #1c1917; font-weight: 600;">
                                            @if($order->currency === 'USD')
                                                ${{ number_format($order->subtotal_usd, 2) }}
                                            @else
                                                Rp {{ number_format($order->subtotal_idr, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Order Notes (if any) -->
                    @if($order->order_notes)
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Customer Notes</p>
                                <p style="font-size: 14px; color: #44403c; margin: 0; font-style: italic; line-height: 1.6;">
                                    "{{ $order->order_notes }}"
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    <!-- Action Required -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Next Steps</p>
                                <p style="font-size: 13px; color: #44403c; margin: 0; line-height: 1.6;">
                                    Please review this order and send payment details to the customer within 24 hours. Don't forget to calculate the shipping cost based on the customer's location.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Review & Process Order
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
                                Admin Panel
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0 0 10px 0;">
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
