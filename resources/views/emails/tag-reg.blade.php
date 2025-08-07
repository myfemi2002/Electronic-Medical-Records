<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f8f8f8;
            border: 1px solid #dddddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            background-color: #224abe;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #224abe;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .details {
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Event Registration Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $attendee->name }},</p>
            
            <p>Thank you for registering for <strong>{{ $event->name }} {{ date('Y') }}</strong>. Your registration has been successfully completed.</p>

            <div class="details">
                <h3>Registration Details:</h3>
                <p><strong>Event ID:</strong> {{ $attendee->unique_id }}</p>
                <p><strong>Province:</strong> {{ $attendee->province }}</p>
                <p><strong>Region:</strong> {{ $attendee->region }}</p>
            </div>

            <p>Please click the button below to download your Event Tag:</p>
            
            <center>
                <a href="{{ $printTagUrl }}" class="button">Download Event Tag</a>
            </center>

            <p>Please keep this tag with you during the event. You can always come back to download your tag using the link above.</p>

            <p>If you have any questions, please don't hesitate to contact us.</p>

            <p>Best regards,<br>Event Management Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message from {{ config('app.name') }}. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>