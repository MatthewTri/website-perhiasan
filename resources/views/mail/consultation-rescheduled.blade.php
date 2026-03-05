<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Consultation Rescheduled</title>
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
                            <span style="font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px;">Appointment Rescheduled</span>
                        </td>
                    </tr>

                    {{-- Title --}}
                    <tr>
                        <td class="inner-padding" align="center" style="padding: 0 50px 40px 50px;">
                            <h1 style="font-family: 'Georgia', serif; color: #1c1917; font-size: 28px; font-weight: 400; margin: 0 0 15px 0; line-height: 1.3;">
                                Your Consultation Has Been Rescheduled
                            </h1>
                            <p style="color: #78716c; font-size: 14px; margin: 0; line-height: 1.6;">
                                Dear {{ $customRequest->first_name }}, your appointment has been moved to a new date and time. <br>Please review the updated schedule below.
                            </p>
                        </td>
                    </tr>

                    {{-- New Appointment Schedule Box --}}
                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #E3CFCF; border-bottom: 1px solid #E3CFCF;">
                                <tr>
                                    <td align="center" style="padding: 30px 20px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; margin: 0 0 15px 0;">
                                            New Schedule
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

                    {{-- What Changed Section --}}
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 30px 50px;">
                            <div style="background-color: #fafaf9; padding: 20px; border-left: 2px solid #a8a29e; margin-bottom: 20px;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Reason for Reschedule</p>
                                <p style="font-size: 14px; color: #44403c; margin: 0; line-height: 1.6;">
                                    We needed to adjust your appointment time to better accommodate your consultation needs. We apologize for any inconvenience this may cause.
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- Meeting Location/Type --}}
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 35px 50px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fafaf9; border-radius: 4px; border: 1px solid #f5f5f4;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #78716c; margin: 0 0 12px 0;">
                                            @if($customRequest->meeting_type === 'offline')
                                                Where We'll Meet
                                            @else
                                                Virtual Meeting Details
                                            @endif
                                        </p>
                                        
                                        @if($customRequest->meeting_type === 'offline')
                                            <p style="font-size: 16px; color: #1c1917; font-weight: 600; margin: 0 0 8px 0;">
                                                Cécile Studio
                                            </p>
                                            <p style="font-size: 14px; color: #44403c; margin: 0 0 15px 0; line-height: 1.6;">
                                                Jl. Prapen Indah B no. 12<br>
                                                Surabaya 60299, Indonesia
                                            </p>
                                            <p style="font-size: 12px; color: #78716c; margin: 0; font-style: italic;">
                                                Please arrive 5-10 minutes early. Free parking is available on-site.
                                            </p>
                                        @else
                                            <p style="font-size: 14px; color: #1c1917; font-weight: 600; margin: 0 0 8px 0;">
                                                Zoom / Google Meet
                                            </p>
                                            <p style="font-size: 13px; color: #57534e; margin: 0 0 12px 0; line-height: 1.7;">
                                                Your personalized meeting link will be sent <strong style="color: #1c1917;">30 minutes before</strong> your scheduled consultation time.
                                            </p>
                                            <p style="font-size: 12px; color: #78716c; margin: 0; font-style: italic;">
                                                Please check your WhatsApp and email inbox. Ensure you have a stable internet connection and working camera.
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Client Profile Section --}}
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 30px 50px;">
                            <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1c1917; margin-bottom: 20px; border-bottom: 1px solid #f5f5f4; padding-bottom: 10px;">
                                Your Contact Information
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Full Name</span>
                                        <span style="font-size: 14px; color: #1c1917; font-weight: 500;">{{ $customRequest->first_name }} {{ $customRequest->last_name }}</span>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Rescheduled On</span>
                                        <span style="font-size: 14px; color: #1c1917;">{{ now()->format('M j, Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email</span>
                                        <a href="mailto:{{ $customRequest->user_email }}" style="font-size: 14px; color: #1c1917; text-decoration: none;">{{ $customRequest->user_email }}</a>
                                    </td>
                                    <td class="mobile-stack" width="50%" valign="top" style="padding-bottom: 20px;">
                                        <span style="display: block; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Phone</span>
                                        <span style="font-size: 14px; color: #1c1917;">{{ $customRequest->user_phone }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Notes Section --}}
                    @if($customRequest->appointment_notes)
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 20px; border-left: 2px solid #E3CFCF;">
                                <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #78716c; margin: 0 0 8px 0;">Your Original Notes</p>
                                <p style="font-size: 14px; color: #44403c; margin: 0; font-style: italic; line-height: 1.6;">
                                    "{{ $customRequest->appointment_notes }}"
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif


                    {{-- Important Notice --}}
                    <tr>
                        <td class="inner-padding" style="padding: 0 50px 40px 50px;">
                            <div style="background-color: #faf7f7; padding: 25px; border-left: 3px solid #E3CFCF; border-radius: 0 4px 4px 0;">
                                <p style="font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #78716c; margin: 0 0 12px 0;">
                                    Need to Change This Schedule?
                                </p>
                                <p style="font-size: 14px; color: #44403c; margin: 0 0 15px 0; line-height: 1.7;">
                                    If this new schedule doesn't work for you, please contact us at least <strong style="color: #1c1917;">24 hours in advance</strong> to discuss alternative arrangements.
                                </p>
                                <p style="font-size: 13px; color: #78716c; margin: 0 0 6px 0;">
                                    <strong style="color: #1c1917;">Email:</strong> 
                                    <a href="mailto:{{ config('mail.from.address') }}" style="color: #1c1917; text-decoration: none;">{{ config('mail.from.address') }}</a>
                                </p>
                                <p style="font-size: 13px; color: #78716c; margin: 0;">
                                    <strong style="color: #1c1917;">WhatsApp:</strong> 
                                    <a href="https://wa.me/6287766999564" style="color: #1c1917; text-decoration: none;">+6287766999564</a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td align="center" style="padding-bottom: 50px;">
                            <a href="mailto:cecileprivatecollection@gmail.com" 
                               style="background-color: #1c1917; color: #ffffff; padding: 16px 32px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; border-radius: 2px; display: inline-block;">
                                Contact Us
                            </a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="border-top: 1px solid #f5f5f4; padding: 30px; background-color: #ffffff;">
                            <p style="font-family: 'Georgia', serif; color: #a8a29e; font-size: 12px; font-style: italic; margin-bottom: 10px;">
                                Cecile Private Collection
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