<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Consultation Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-left: 4px solid #667eea;
            border-radius: 5px;
        }
        .field-label {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .field-value {
            color: #333;
        }
        .notes {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .attachment-info {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            text-align: center;
        }
        .attachment-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .highlight-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .row {
            display: flex;
            gap: 20px;
        }

        .row .field {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎯 New Consultation Request</h1>
        <p>A new jewelry consultation request has been submitted</p>
    </div>

    <div class="content">
        <div class="row">
            <div class="field">
                <div class="field-label">👤 Customer Name:</div>
                <div class="field-value">{{ $customRequest->user_name }}</div>
            </div>

            <div class="field">
                <div class="field-label">📧 Email Address:</div>
                <div class="field-value">{{ $customRequest->user_email }}</div>
            </div>

            
            <div class="field">
                <div class="field-label">🌍 Country:</div>
                <div class="field-value">{{ $customRequest->country }}</div>
            </div>

            <div class="field">
                <div class="field-label">👤 Account Status:</div>
                <div class="field-value">
                    @if($customRequest->user_id)
                        ✅ Registered User (ID: {{ $customRequest->user_id }})
                    @else
                        🔄 Guest User
                    @endif
                </div>
            </div>

        </div>


        <div class="field">
            <div class="field-label">📅 Submitted:</div>
            <div class="field-value">{{ $customRequest->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>

        @if($customRequest->notes)
        <div class="field">
            <div class="field-label">💎 Jewelry Description:</div>
            <div class="notes">{{ $customRequest->notes }}</div>
        </div>
        @endif

        @if($customRequest->images->count() > 0)
        <div class="field">
            <div class="field-label">📸 Reference Images:</div>
            <div class="attachment-info">
                <div class="attachment-icon">🖼️</div>
                <div style="font-weight: bold; color: #1976d2; font-size: 16px;">
                    {{ $customRequest->images->count() }} Reference Image{{ $customRequest->images->count() > 1 ? 's' : '' }} Attached
                </div>
                <div style="color: #666; font-size: 14px; margin-top: 5px;">
                    Please check the attachments below to view customer's reference images
                </div>
                <div style="margin-top: 10px; font-size: 12px; color: #999;">
                    @foreach($customRequest->images as $index => $image)
                        📎 reference_image_{{ $index + 1 }}.jpg{{ !$loop->last ? ' • ' : '' }}
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="highlight-box">
            <div style="display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 20px; margin-right: 10px;">💡</span>
                <div>
                    <strong style="color: #856404;">Important:</strong>
                    <span style="color: #856404;">Scroll down to view the attached reference images. These images will help you understand the customer's vision and preferences.</span>
                </div>
            </div>
        </div>
        @else
        <div class="field">
            <div class="field-label">📸 Reference Images:</div>
            <div class="field-value" style="color: #666; font-style: italic;">
                No reference images provided by the customer.
            </div>
        </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url('/admin/consultation/' . $customRequest->id) }}" 
               class="button">
               View Full Details & Respond
            </a>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated notification from your jewelry website.</p>
        <p><strong>Request ID: #{{ $customRequest->id }}</strong></p>
        @if($customRequest->images->count() > 0)
            <p style="color: #1976d2; font-weight: bold;">
                📎 {{ $customRequest->images->count() }} attachment{{ $customRequest->images->count() > 1 ? 's' : '' }} included
            </p>
        @endif
        
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 12px; color: #999;">
            <p>💌 Auto-generated from Cecile Private Collection consultation system</p>
        </div>
    </div>
</body>
</html>