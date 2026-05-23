<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventure Registration</title>
</head>
<body style="margin:0; padding:0; background:#e8f4fd; font-family:Arial, Helvetica, sans-serif; color:#0A2342;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#e8f4fd; padding:24px 0;">
        <tr>
            <td align="center" style="padding:0 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #bfdfff;">
                    <tr>
                        <td style="background:linear-gradient(135deg, #1B6CA8 0%, #0A2342 80%); padding:20px 24px;">
                            <p style="margin:0; font-size:24px; font-weight:700; letter-spacing:-0.02em; color:#ffffff;">
                                <span style="color:#ffffff;">Event</span><span style="color:#5BA4CF;">ure</span>
                            </p>
                            <p style="margin:8px 0 0; font-size:13px; color:#bfdfff;">Digital ID Registration Confirmation</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px; font-size:16px; color:#0A2342;">Hello {{ $participant->name }},</p>
                            <p style="margin:0 0 18px; font-size:14px; line-height:1.6; color:#1B6CA8;">
                                You are successfully registered for <strong style="color:#0A2342;">{{ $participant->event->title }}</strong>.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5faff; border:1px solid #bfdfff; border-radius:12px; margin:0 0 18px;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 10px; font-size:11px; letter-spacing:0.09em; text-transform:uppercase; color:#5BA4CF; font-weight:700;">Event Details</p>
                                        <p style="margin:0 0 6px; font-size:13px; color:#0A2342;"><strong>Event:</strong> {{ $participant->event->title }}</p>
                                        <p style="margin:0 0 6px; font-size:13px; color:#0A2342;"><strong>Date:</strong> {{ $participant->event->dateRangeLabel() }}</p>
                                        <p style="margin:0; font-size:13px; color:#0A2342;"><strong>Location:</strong> {{ $participant->event->location ?: 'TBA' }}</p>
                                        <p style="margin:12px 0 0; font-size:13px; color:#0A2342;"><strong>Certificate:</strong> {{ $participant->event->getCertificateType() }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 12px; font-size:14px; color:#1B6CA8;">Your digital gate pass is ready for mobile access:</p>

                            <p style="margin:0 0 14px;">
                                <a href="{{ $digitalIdUrl }}" style="display:inline-block; background:#0A2342; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:10px; font-size:14px; font-weight:700;">Open My Digital ID</a>
                            </p>

                            <p style="margin:0 0 6px; font-size:12px; color:#5BA4CF;">If the button does not work, copy and paste this URL:</p>
                            <p style="margin:0 0 14px; font-size:12px; color:#0A2342; word-break:break-all;">{{ $digitalIdUrl }}</p>

                            <p style="margin:0; font-size:13px; line-height:1.5; color:#1B6CA8;">Please keep this link for entry verification.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f8fcff; border-top:1px solid #bfdfff; padding:14px 24px; text-align:center;">
                            <p style="margin:0; font-size:11px; color:#5BA4CF;">Empowering Events. Connecting People.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
