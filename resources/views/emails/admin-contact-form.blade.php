<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #2563eb;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .email-content {
            padding: 30px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h2 {
            font-size: 18px;
            color: #374151;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 5px;
        }

        .info-item label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .info-item p {
            color: #111827;
            font-weight: 500;
            margin: 0;
        }

        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
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

        .action-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
        }

        .email-footer {
            padding: 20px 30px;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .timestamp {
            color: #6b7280;
            font-size: 14px;
        }

        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>New Contact Form Submission</h1>
        </div>

        <div class="email-content">
            <div class="info-section">
                <h2>Contact Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Name</label>
                        <p>{{ $messageData->name }}</p>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <p>{{ $messageData->email }}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p>{{ $messageData->phone }}</p>
                    </div>
                    @if($messageData->website_url)
                    <div class="info-item">
                        <label>Website</label>
                        <p>{{ $messageData->website_url }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="message-box">
                <h3>Message Content</h3>
                <p>{{ $messageData->message }}</p>
            </div>

        </div>

        <div class="email-footer">
            <p class="timestamp">Submitted on {{ $messageData->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>