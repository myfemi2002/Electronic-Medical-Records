<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Partnership Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #4a6cf7;
            color: white;
            padding: 20px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
        }
        .email-intro {
            margin-bottom: 25px;
            font-size: 16px;
        }
        .partnership-details {
            background-color: #f8f9fa;
            border-left: 4px solid #4a6cf7;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 4px 4px 0;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #333;
        }
        .btn-primary {
            display: inline-block;
            background-color: #4a6cf7;
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #3a5bd9;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 15px 30px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        @media (max-width: 600px) {
            .email-container {
                width: 100%;
                margin: 0;
                border-radius: 0;
            }
            .email-header {
                padding: 15px;
            }
            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>New Partnership Application</h1>
        </div>
        <div class="email-body">
            <div class="email-intro">
                A new partnership application has been submitted.
            </div>
            
            <div class="partnership-details">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $partnership->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $partnership->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $partnership->phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $partnership->created_at->format('F d, Y h:i A') }}</span>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.partnerships.show', $partnership->id) }}" class="btn-primary">
                    View Full Application
                </a>
            </div>
        </div>
        
        <div class="email-footer">
            <p>Thanks,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>