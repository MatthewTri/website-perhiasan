<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body p {
            margin: 0 0 20px;
            color: #555;
        }
        .reset-button {
            display: inline-block;
            padding: 14px 30px;
            background: #667eea;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .reset-button:hover {
            background: #5568d3;
        }
        .alternative-link {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            word-break: break-all;
            font-size: 12px;
            color: #666;
            margin: 20px 0;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #e9ecef;
        }
        .security-notice {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .security-notice p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🔐 Password Reset Request</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hello,</p>
            
            <p>We received a request to reset the password for your account associated with <strong>{{ $email }}</strong>.</p>
            
            <p>Click the button below to reset your password:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetLink }}" class="reset-button">Reset My Password</a>
            </div>

            <p style="font-size: 14px; color: #666; margin-top: 30px;">
                If the button doesn't work, copy and paste this link into your browser:
            </p>
            <div class="alternative-link">
                {{ $resetLink }}
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <p><strong>⚠️ Security Notice:</strong></p>
                <p style="margin-top: 8px;">
                    • This link will expire in <strong>60 minutes</strong><br>
                    • If you didn't request this reset, please ignore this email<br>
                    • Your password will remain unchanged if you don't click the link
                </p>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                If you're having trouble resetting your password, please contact our support team.
            </p>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>{{ config('app.name') }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="margin-top: 10px;">
                This is an automated message, please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
