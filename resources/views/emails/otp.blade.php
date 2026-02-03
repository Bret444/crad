<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your One-Time Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
        }
        table.container {
            max-width: 600px;
            width: 100%;
            margin: 40px auto;
            border-collapse: collapse;
        }
        td.content-cell {
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .otp-box {
            background-color: #e6f7ff;
            border: 2px solid #5044e4;
            color: #5044e4;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .text {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777777;
            text-align: center;
        }
        .main-heading {
            color: #5044e4;
            font-size: 24px;
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f6fa;">

    <table role="presentation" class="container" style="max-width: 600px; width: 100%; margin: 40px auto; border-collapse: collapse;">
        <tr>
            <td class="content-cell" style="padding: 30px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <h1 class="main-heading" style="color: #5044e4; font-size: 24px; text-align: center; margin-top: 0; margin-bottom: 20px;">Your One-Time Password (OTP)</h1>
                <p class="text" style="font-size: 16px; line-height: 1.5; color: #333333;">Hello, Admin</p>
                <p class="text" style="font-size: 16px; line-height: 1.5; color: #333333;">You have requested a one-time password to log in. Please use the following code to complete your sign-in:</p>
                
                <div class="otp-box" style="background-color: #e6f7ff; border: 2px solid #5044e4; color: #5044e4; font-size: 32px; font-weight: bold; text-align: center; padding: 15px; border-radius: 8px; margin: 20px 0; letter-spacing: 2px;">
                    {{ $otp }}
                </div>

                <p class="text" style="font-size: 16px; line-height: 1.5; color: #333333;">This code is valid for 10 minutes.</p>
                <p class="text" style="font-size: 16px; line-height: 1.5; color: #333333;">If you did not request this, please ignore this email.</p>
            </td>
        </tr>
        <tr>
            <td class="footer" style="padding: 30px; font-size: 14px; color: #777777; text-align: center;">
                Thank you,<br>Center for Research And Development&nbsp;(CRAD) 
            </td>
        </tr>
    </table>

</body>
</html>