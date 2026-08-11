<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;padding:30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e4e4e7;">
                    <tr>
                        <td style="background:#16a34a;padding:24px 30px;text-align:center;">
                            <span style="color:#ffffff;font-size:24px;font-weight:bold;letter-spacing:1px;">HappyStem</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 30px;">
                            <h1 style="margin:0 0 12px;font-size:20px;color:#18181b;">Password Reset Code</h1>
                            @if ($name)
                                <p style="margin:0 0 16px;color:#52525b;font-size:14px;line-height:1.6;">Hello {{ $name }},</p>
                            @endif
                            <p style="margin:0 0 20px;color:#52525b;font-size:14px;line-height:1.6;">
                                Use the 6-digit code below to reset your password. This code expires in 15 minutes.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border:2px dashed #16a34a;border-radius:8px;width:100%;">
                                <tr>
                                    <td align="center" style="padding:20px;font-size:32px;font-weight:bold;letter-spacing:8px;color:#15803d;">{{ $code }}</td>
                                </tr>
                            </table>
                            <p style="margin:20px 0 0;color:#71717a;font-size:12px;line-height:1.6;">
                                If you did not request a password reset, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#fafafa;padding:16px 30px;text-align:center;border-top:1px solid #e4e4e7;">
                            <span style="color:#a1a1aa;font-size:12px;">&copy; {{ date('Y') }} HappyStem. All rights reserved.</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
