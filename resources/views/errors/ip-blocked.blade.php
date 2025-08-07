<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - IP Blocked</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .error-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            border: none;
            overflow: hidden;
            max-width: 600px;
        }
        
        .header-section {
            background: linear-gradient(45deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .content-section {
            background: white;
            padding: 2rem;
        }
        
        .error-icon {
            font-size: 4rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }
        
        .info-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 1rem;
            display: inline-block;
        }
        
        .ip-display {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: center;
        }
        
        .contact-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card error-card">
                    <!-- Header Section -->
                    <div class="header-section">
                        <div class="error-icon">
                            <i class="mdi mdi-shield-off"></i>
                        </div>
                        <h2 class="mt-3 mb-2">Access Denied</h2>
                        <p class="mb-0 opacity-75">Your IP address has been blocked</p>
                        
                        <div class="info-badge">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            Error Code: 403 - Forbidden
                        </div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="content-section">
                        <!-- IP Display -->
                        <div class="ip-display">
                            <h5 class="text-primary mb-3">
                                <i class="mdi mdi-ip-network me-2"></i>Your IP Address
                            </h5>
                            <h3 class="mb-0">
                                <code class="text-danger">{{ $ip_address ?? 'Unknown' }}</code>
                            </h3>
                        </div>
                        
                        <!-- Message -->
                        <div class="alert alert-danger border-0">
                            <h5 class="alert-heading">
                                <i class="mdi mdi-shield-alert me-2"></i>Access Blocked
                            </h5>
                            <p class="mb-0">{{ $message ?? 'Your IP address has been blocked due to security reasons.' }}</p>
                        </div>
                        
                        <!-- Possible Reasons -->
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="mdi mdi-help-circle me-2"></i>Why might this have happened?
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="mdi mdi-alert text-warning me-2"></i>
                                    Multiple failed login attempts
                                </li>
                                <li class="mb-2">
                                    <i class="mdi mdi-robot text-warning me-2"></i>
                                    Suspicious or automated activity detected
                                </li>
                                <li class="mb-2">
                                    <i class="mdi mdi-security text-warning me-2"></i>
                                    Violation of terms of service
                                </li>
                                <li class="mb-2">
                                    <i class="mdi mdi-bug text-warning me-2"></i>
                                    Malicious software or scripts detected
                                </li>
                                <li class="mb-0">
                                    <i class="mdi mdi-spam text-warning me-2"></i>
                                    Spam or abuse reported from this IP
                                </li>
                            </ul>
                        </div>
                        
                        <!-- What to do -->
                        <div class="alert alert-info border-0">
                            <h6 class="alert-heading">
                                <i class="mdi mdi-lightbulb me-2"></i>What can you do?
                            </h6>
                            <ul class="mb-0">
                                <li>Wait for the block to expire (if temporary)</li>
                                <li>Contact the website administrator</li>
                                <li>Check if you're using a VPN or proxy server</li>
                                <li>Ensure your computer is free from malware</li>
                                <li>Try accessing from a different network</li>
                            </ul>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="contact-info">
                            <h6 class="text-primary mb-3">
                                <i class="mdi mdi-email me-2"></i>Need Help?
                            </h6>
                            <p class="mb-2">
                                If you believe this is an error, please contact our support team with the following information:
                            </p>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Your IP Address:</strong> <code>{{ $ip_address ?? 'Unknown' }}</code></li>
                                <li><strong>Time:</strong> {{ now()->format('Y-m-d H:i:s T') }}</li>
                                <li><strong>Error Code:</strong> 403 - IP Blocked</li>
                                <li><strong>Reference ID:</strong> {{ Str::random(8) }}</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary me-2" onclick="window.history.back()">
                                <i class="mdi mdi-arrow-left me-2"></i>Go Back
                            </button>
                            <button class="btn btn-primary" onclick="window.location.reload()">
                                <i class="mdi mdi-refresh me-2"></i>Try Again
                            </button>
                        </div>
                        
                        <!-- Additional Info -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="mdi mdi-clock me-1"></i>
                                This page was generated at {{ now()->format('Y-m-d H:i:s T') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh after 5 minutes to check if block is lifted
        setTimeout(function() {
            if (confirm('Would you like to check if the block has been lifted?')) {
                window.location.reload();
            }
        }, 300000); // 5 minutes
        
        // Disable F12, right-click, and common key combinations
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || 
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.shiftKey && e.key === 'C') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
                return false;
            }
        });
        
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });
    </script>
</body>
</html>