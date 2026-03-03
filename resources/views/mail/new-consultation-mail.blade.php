<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Consultation Request</title>
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

                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                New Consultation Request
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                A client has submitted a request for a consultation. <br>Please review the details below.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E8B49E; border-bottom: 1px solid #E8B49E;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 10px 0;">
                                            Requested Schedule
                                        </p>
                                        <h2 style="font-family: 'Georgia', serif; font-size: 24px; color: #1c1917; margin: 0 0 5px 0; font-weight: 400;">
                                            {{ \Carbon\Carbon::parse($customRequest->appointment_date)->format('l, F j, Y') }}
                                        </h2>
                                        <p style="font-size: 16px; color: #44403c; margin: 0 0 20px 0;">
                                            {{ \Carbon\Carbon::parse($customRequest->appointment_time)->format('g:i A') }} 
                                            - 
                                            @if($customRequest->consultation_type === 'design')
                                                {{ \Carbon\Carbon::parse($customRequest->appointment_time)->addHour()->format('g:i A') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($customRequest->appointment_time)->addMinutes(30)->format('g:i A') }}
                                            @endif
                                            <span style="color: #a8a29e; font-size: 12px;">(WIB)</span>
                                        </p>
                                        
                                        <span style="background-color: #f5f5f4; color: #57534e; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 6px 12px; border-radius: 2px;">
                                            {{ $customRequest->consultation_type === 'design' ? 'Custom Design (60 Minutes)' : 'Short Consultation (30 Minutes)' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Meeting Location/Type --}}
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 35px 50px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fafaf9; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #78716c; margin: 0 0 12px 0;">
                                            @if($customRequest->meeting_type === 'offline')
                                                Meeting Location
                                            @else
                                                Virtual Meeting
                                            @endif
                                        </p>
                                        
                                        @if($customRequest->meeting_type === 'offline')
                                            <p style="font-size: 16px; color: #1c1917; font-weight: 600; margin: 0 0 8px 0;">
                                                Cécile Studio
                                            </p>
                                            <p style="font-size: 14px; color: #44403c; margin: 0; line-height: 1.6;">
                                                Jl. Prapen Indah B no. 12<br>
                                                Surabaya 60299, Indonesia
                                            </p>
                                        @else
                                            <p style="font-size: 14px; color: #1c1917; font-weight: 600; margin: 0 0 8px 0;">
                                                Zoom / Google Meet
                                            </p>
                                            <p style="font-size: 13px; color: #57534e; margin: 0; line-height: 1.7;">
                                                The meeting link will be sent to the client <strong style="color: #1c1917;">30 minutes before</strong> the scheduled consultation via WhatsApp and Email.
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 30px 50px;">
                            <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1c1917; margin-bottom: 20px; border-bottom: 1px solid #f5f5f4; padding-bottom: 10px;">
                                Client Profile
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Full Name</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500;">{{ $customRequest->first_name }} {{ $customRequest->last_name }}</span>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Submitted On</span>
                                        <span style="font-size: 14px; color: #1c1917;">{{ $customRequest->created_at->format('M j, Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email</span>
                                        <a href="mailto:{{ $customRequest->user_email }}" style="font-size: 14px; color: #E8B49E; text-decoration: none; border-bottom: 1px dotted #E8B49E;">{{ $customRequest->user_email }}</a>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone</span>
                                        <a href="tel:{{ $customRequest->user_phone }}" style="font-size: 14px; color: #1c1917; text-decoration: none;">{{ $customRequest->user_phone }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            @if($customRequest->appointment_notes)
                                <div style="background-color: #fafaf9; padding: 20px; border-left: 2px solid #E8B49E; margin-bottom: 20px;">
                                    <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Client Notes</p>
                                    <p style="font-size: 14px; color: #44403c; margin: 0; font-style: italic; line-height: 1.6;">
                                        "{{ $customRequest->appointment_notes }}"
                                    </p>
                                </div>
                            @endif

                            @if($customRequest->images->count() > 0)
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td valign="middle">
                                            <span style="font-size: 13px; color: #57534e;">
                                                <strong>{{ $customRequest->images->count() }} Reference Image(s)</strong> attached to this request.
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <a href="{{ route('admin.consultation.show', $customRequest->id) }}" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Review Application
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="border-top: 1px solid #f5f5f4; padding: 30px; background-color: #ffffff;">
                            <p style="font-family: 'Georgia', serif; color: #a8a29e; font-size: 12px; font-style: italic; margin-bottom: 10px;">
                                Cecile Private Collection
                            </p>
                            <p style="color: #d6d3d1; font-size: 10px; text-transform: uppercase; letter-spacing: 1px;">
                                &copy; {{ date('Y') }} All rights reserved.
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