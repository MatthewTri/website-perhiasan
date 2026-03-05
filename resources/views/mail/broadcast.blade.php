<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $emailSubject }}</title>
    <style>
        /* Base Reset */
        body { margin: 0; padding: 0; width: 100% !important; background-color: #f9fafb; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        a { text-decoration: none; color: inherit; }
        
        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .main-container { width: 100% !important; max-width: 100% !important; }
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
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Newsletter Update</span>
                        </td>
                    </tr>

                    <!-- Banner Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 2px; background: linear-gradient(to right, transparent, #E3CFCF, transparent);"></div>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td class="inner-padding" style="padding: 50px; color: #44403c; font-size: 14px; line-height: 1.7;">
                            {!! nl2br(e($emailBody)) !!}
                        </td>
                    </tr>

                    <!-- Call to Action (Optional) -->
                    <tr>
                        <td align="center" style="padding: 0 50px 50px 50px;">
                            <a href="{{ route('home') }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Visit Our Page
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="border-top: 1px solid #f5f5f4; padding: 30px; background-color: #ffffff;">
                            <p style="font-family: 'Georgia', serif; color: #a8a29e; font-size: 12px; font-style: italic; margin-bottom: 10px;">
                                Cecile Private Collection
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0 0 15px 0;">
                                Questions? Contact: {{ config('mail.from.address') }} | WhatsApp: +6287766999564
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0; line-height: 1.6;">
                                <a href="{{ url('/unsubscribe?email=' . urlencode($subscriberEmail)) }}" 
                                   style="color: #E3CFCF; text-decoration: underline;">
                                    Unsubscribe from newsletter
                                </a>
                            </p>
                        </td>
                    </tr>

                </table>
                
                <!-- Spacer -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr><td height="40">&nbsp;</td></tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
