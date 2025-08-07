@php
$settings = App\Models\SiteSetting::first();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            padding: 20px;
        }

        /* Container styles */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header styles */
        .email-header {
            background-color: #4f46e5;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        /* Content styles */
        .email-content {
            padding: 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #111827;
        }

        .message-text {
            margin-bottom: 25px;
            color: #4b5563;
        }

        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 5px 5px 0;
        }

        .message-box h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #374151;
        }

        .message-box p {
            color: #6b7280;
            margin: 0;
        }

        .response-time {
            background-color: #fff7ed;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .response-time p {
            color: #92400e;
            margin: 0;
        }

        .contact-info {
            background-color: #f0fdf4;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .contact-info p {
            color: #166534;
            margin: 0;
        }

        /* Footer styles */
        .email-footer {
            padding: 20px 30px;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            color: #6b7280;
            font-size: 14px;
        }

        .company-name {
            font-weight: 600;
            color: #4f46e5;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .email-header {
                padding: 20px;
            }

            .email-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Thank You for Contacting Us</h1>
        </div>

        <div class="email-content">
            <p class="greeting">Dear {{ $messageData->name }},</p>

            <p class="message-text">
                We have received your message and will get back to you soon. Here's a copy of your message for your reference:
            </p>

            <div class="message-box">
                <h3>Your Message:</h3>
                <p>{{ $messageData->message }}</p>
            </div>

            <div class="response-time">
                <p>
                    <strong>📝 Response Time:</strong> We typically respond within 24-48 business hours.
                </p>
            </div>

            <div class="contact-info">
                <p>
                    <strong>☎️ Need Urgent Help?</strong> Contact us directly at {{ $settings->phone }}
                </p>
            </div>
        </div>

        <div class="email-footer">
            <p class="footer-text">
                Best regards,<br>
                <span class="company-name">{{ config('app.name') }} Team</span>
            </p>
        </div>
    </div>
</body>
</html>