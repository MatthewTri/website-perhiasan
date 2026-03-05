<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>We Hope You Love Your Purchase - Order #{{ $order->order_number }}</title>
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
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Order Complete</span>
                        </td>
                    </tr>

                    <!-- Title Section -->
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Thank You for Your Purchase
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                We hope you're delighted with your jewellery from our collection
                            </p>
                        </td>
                    </tr>

                    <!-- Hero Image/Icon -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <div style="background: linear-gradient(135deg, #faf7f5 0%, #f5f1ee 100%); padding: 40px; border-radius: 4px;">
                                <p style="font-family: 'Georgia', serif; font-size: 18px; color: #1c1917; margin: 0; font-style: italic;">
                                    Your order has been completed
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Order Details -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1c1917; margin-bottom: 20px; border-bottom: 1px solid #f5f5f4; padding-bottom: 10px;">
                                Order Summary
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 15px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Order Number</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 600;">{{ $order->order_number }}</span>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 15px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Completed Date</span>
                                        <span style="font-size: 14px; color: #1c1917;">{{ $order->completed_at->format('M j, Y') }}</span>
                                    </td>
                                </tr>
                            </table>

                            @foreach($order->orderItems as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 15px 0; padding: 15px; background-color: #fafaf9; border-radius: 4px;">
                                <tr>
                                    <td width="70%" valign="top">
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500; display: block; margin-bottom: 4px;">{{ $item->product->name }}</span>
                                        <span style="font-size: 12px; color: #a8a29e;">Quantity: {{ $item->quantity }}</span>
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
                        </td>
                    </tr>

                    <!-- Care Instructions -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Care for Your Jewellery</p>
                                <p style="font-size: 13px; color: #44403c; margin: 0 0 10px 0; line-height: 1.6;">
                                    To maintain the beauty and longevity of your jewellery:
                                </p>
                                <ul style="font-size: 13px; color: #44403c; margin: 0; padding-left: 20px; line-height: 1.8;">
                                    <li>Store in a cool, dry place away from direct sunlight</li>
                                    <li>Clean gently with a soft cloth</li>
                                    <li>Remove before swimming or exercising</li>
                                    <li>Avoid contact with perfumes and chemicals</li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Review Request Section -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E3CFCF; border-bottom: 1px solid #E3CFCF;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 15px 0;">
                                            Share Your Experience
                                        </p>
                                        <p style="font-family: 'Georgia', serif; font-size: 16px; color: #1c1917; margin: 0 0 20px 0; line-height: 1.6;">
                                            We'd love to hear what you think about your purchase. Your feedback helps us and other customers make informed decisions.
                                        </p>
                                        <a href="{{ $magicLink }}" 
                                           style="background-color: #E3CFCF; color: #ffffff; padding: 14px 28px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                            Write a Review
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Refund Policy Reminder -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #fafaf9; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Refund Policy</p>
                                <p style="font-size: 13px; color: #44403c; margin: 0; line-height: 1.6;">
                                    You have <strong>14 days</strong> from the completion date to request a refund if you're not completely satisfied with your purchase. Please ensure items are in their original condition.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <a href="{{ route('products.index') }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Explore More Collection
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
