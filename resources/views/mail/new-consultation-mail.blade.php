<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Consultation Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 650px;
            margin: 30px auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: #ffd8d8;
            padding: 40px 30px;
            text-align: center;
            border-bottom: 3px solid #FF9999;
        }
        .header h1 {
            margin: 0;
            color: #000000;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 10px 0 0 0;
            color: #000000;
            font-size: 15px;
        }
        .content {
            padding: 40px 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #8B0000;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #FFCCCC;
        }
        .section-title:first-child {
            margin-top: 0;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: 600;
            color: #555555;
            padding: 8px 12px 8px 0;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            width: 65%;
            color: #333333;
            padding: 8px 0;
            vertical-align: top;
        }
        .notes-section {
            background-color: #FFF5F5;
            border-left: 4px solid #FFCCCC;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .notes-title {
            font-weight: 600;
            color: #8B0000;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .notes-content {
            color: #333333;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .images-section {
            background-color: #FFFAFA;
            border: 2px solid #FFCCCC;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .images-count {
            font-weight: 600;
            color: #8B0000;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .image-item {
            background-color: #ffffff;
            border: 1px solid #FFE0E0;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            color: #555555;
        }
        .image-item:last-child {
            margin-bottom: 0;
        }
        .no-images {
            color: #999999;
            font-style: italic;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .button-container {
            text-align: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background: #ffd8d8;
            color: #000000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid #FF9999;
            transition: all 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #FFB3B3 0%, #FF9999 100%);
        }
        .footer {
            background-color: #f9f9f9;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            color: #777777;
            font-size: 13px;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #FFCCCC, transparent);
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>New Consultation Request</h1>
            <p>A new jewelry consultation request has been submitted</p>
        </div>

        <div class="content">
            <div class="section-title">Customer Information</div>
            
            <div class="info-row">
                <div class="info-label">Customer Name:</div>
                <div class="info-value">{{ $customRequest->user_name }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Email Address:</div>
                <div class="info-value">{{ $customRequest->user_email }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value">{{ $customRequest->user_phone }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Request Date:</div>
                <div class="info-value">{{ $customRequest->created_at->format('l, F j, Y \a\t g:i A') }}</div>
            </div>

            @if($customRequest->notes)
            <div class="divider"></div>
            
            <div class="section-title">Additional Notes</div>
            <div class="notes-section">
                <div class="notes-content">{{ $customRequest->notes }}</div>
            </div>
            @endif

            <div class="divider"></div>

            <div class="section-title">Design References</div>
            
            @if($customRequest->images->count() > 0)
                <div class="images-section">
                    <div class="images-count">
                        Total Attached Images: {{ $customRequest->images->count() }}
                    </div>
                </div>
            @else
                <div class="no-images">
                    No reference images attached
                </div>
            @endif

            <div class="button-container">
                <a href="{{ route('admin.consultation.show', $customRequest->id) }}" 
                    class="button" 
                    style="color: #000000 !important; text-decoration: none !important;">
                    View Full Details
                </a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from Cecile Private Collection</p>
            <p>Please do not reply directly to this email</p>
        </div>
    </div>
</body>
</html>