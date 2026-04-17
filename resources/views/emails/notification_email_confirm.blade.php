<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Notification Email</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f5f6fa;">
    <table role="presentation" style="max-width:600px;width:100%;margin:40px auto;border-collapse:collapse;">
        <tr>
            <td style="padding:30px;background-color:#ffffff;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
                <h1 style="color:#5044e4;font-size:24px;text-align:center;margin-top:0;">Confirm Notification Email</h1>
                <p style="font-size:16px;line-height:1.5;color:#333;">You have been added as a notification recipient for the <strong>CRAD System</strong>.</p>
                <p style="font-size:16px;line-height:1.5;color:#333;">Click the button below to confirm and activate your email for system notifications:</p>
                <div style="text-align:center;margin:30px 0;">
                    <a href="{{ $confirmUrl }}" style="background-color:#5044e4;color:#ffffff;padding:14px 28px;border-radius:6px;text-decoration:none;font-size:16px;font-weight:bold;">
                        Confirm Email
                    </a>
                </div>
                <p style="font-size:14px;color:#777;">If you did not expect this email, you can safely ignore it.</p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px;font-size:14px;color:#777777;text-align:center;">
                Thank you,<br><strong>SMS 3:</strong>Center for Research & Development (CRAD)
            </td>
        </tr>
    </table>
</body>
</html>
