<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            margin: 20px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
        }
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #2563eb;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #666;
        }
        .link-text {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding: 15px;
            background-color: #f3f4f6;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        
        <div class="content">
            <h2>Welcome, {{ $user->name }}!</h2>
            
            <p>An account has been created for you at {{ config('app.name') }}. To get started, you need to set your password.</p>
            
            <p>Click the button below to set your password:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Set Your Password</a>
            </div>
            
            <p>This password reset link will expire in 60 minutes.</p>
            
            <p>If the button doesn't work, you can copy and paste the following link into your browser:</p>
            
            <div class="link-text">
                {{ $resetUrl }}
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                If you did not expect to receive this email, please contact your administrator.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
