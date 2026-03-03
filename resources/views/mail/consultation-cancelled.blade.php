<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Consultation Cancelled</title>
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
                    
                    {{-- Header --}}
                    <tr>
                        <td align="center" style="padding: 40px 0 10px 0;">
                            <span style="font-family: 'Georgia', serif; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; color: #1c1917; font-weight: bold;">
                                Cecile Private Collection
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 40px;">
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Appointment Status</span>
                        </td>
                    </tr>

                    {{-- Title --}}
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Consultation Cancelled
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                Dear {{ $customRequest->first_name }}, your consultation request for the following date has been cancelled.
                            </p>
                        </td>
                    </tr>

                    {{-- Cancelled Appointment Box (Clean Design) --}}
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 2px solid #a8a29e; border-bottom: 2px solid #a8a29e; background-color: #fafaf9;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #78716c; margin: 0 0 15px 0;">
                                            Cancelled Appointment Details
                                        </p>
                                        <h2 style="font-family: 'Georgia', serif; font-size: 24px; color: #44403c; margin: 0 0 5px 0; font-weight: 400;">
                                            {{ \Carbon\Carbon::parse($customRequest->appointment_date)->format('l, F j, Y') }}
                                        </h2>
                                        <p style="font-size: 16px; color: #57534e; margin: 0 0 20px 0;">
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

                    {{-- Cancellation Reason Section --}}
                    @if($customRequest->admin_notes)
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 30px 50px;">
                            <div style="background-color: #fff8f3; padding: 20px; border-left: 2px solid #E8B49E; margin-bottom: 20px;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #8B5A3C; margin: 0 0 8px 0;">Note from our team</p>
                                <p style="font-size: 14px; color: #44403c; margin: 0; line-height: 1.6;">
                                    {{ $customRequest->admin_notes }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    {{-- Next Steps --}}
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 30px 50px;">
                            <p style="font-size: 14px; color: #44403c; line-height: 1.6; margin-bottom: 20px;">
                                We apologize for any inconvenience this may cause. If you would like to reschedule or discuss this further, please feel free to reach out to us.
                            </p>
                        </td>
                    </tr>

                    {{-- CTA Buttons --}}
                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding: 0 10px;">
                                        <a href="{{ url('/custom-request') }}" 
                                           style="background-color: #1c1917; color: #ffffff; padding: 14px 28px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                            Book New Appointment
                                        </a>
                                    </td>
                                    <td style="padding: 0 10px;">
                                        <a href="mailto:cecileprivatecollection@gmail.com" 
                                           style="background-color: #ffffff; color: #1c1917; border: 1px solid #d6d3d1; padding: 13px 28px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                            Contact Us
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
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