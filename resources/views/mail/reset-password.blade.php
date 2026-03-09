<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Password</title>
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
                    
                    <!-- Header: Brand Name -->
                    <tr>
                        <td align="center" style="padding: 40px 0 10px 0;">
                            <span style="font-family: 'Georgia', serif; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; color: #1c1917; font-weight: bold;">
                                Cecile Private Collection
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 40px;">
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Security Notification</span>
                        </td>
                    </tr>

                    <!-- Main Title -->
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Reset Your Password
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                We received a request to reset the password for your account associated with <strong style="color: #1c1917;">{{ $email }}</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Call to Action Button -->
                    <tr>
                        <td align="center" style="padding: 0 50px 40px 50px;">
                            <a href="{{ $resetLink }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Reset My Password
                            </a>
                        </td>
                    </tr>

                    <!-- Security Notice Box -->
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #faf7f7; border-left: 2px solid #E3CFCF; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #78716c; margin: 0 0 12px 0;">
                                            Security Notice
                                        </p>
                                        <p style="font-size: 14px; color: #44403c; margin: 0 0 10px 0; line-height: 1.6;">
                                            • This link will expire in <strong style="color: #1c1917;">60 minutes</strong>
                                        </p>
                                        <p style="font-size: 14px; color: #44403c; margin: 0 0 10px 0; line-height: 1.6;">
                                            • If you didn't request this reset, please ignore this email
                                        </p>
                                        <p style="font-size: 14px; color: #44403c; margin: 0; line-height: 1.6;">
                                            • Your password will remain unchanged if you don't click the link
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>


                    <!-- Footer -->
                    <tr>
                        <td align="center" style="border-top: 1px solid #f5f5f4; padding: 30px; background-color: #ffffff;">
                            <p style="font-family: 'Georgia', serif; color: #a8a29e; font-size: 12px; font-style: italic; margin-bottom: 10px;">
                                Cecile Private Collection
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 5px 0;">
                                Luxury Jewelry Atelier
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; margin: 0;">
                                © {{ date('Y') }} All rights reserved. | This is an automated message, please do not reply.
                            </p>
                        </td>
                    </tr>

                </table>
                
                <!-- Bottom Spacing -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr><td height="40">&nbsp;</td></tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
