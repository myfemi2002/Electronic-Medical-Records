<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Blocked</title>
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
        
        .blocked-card {
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
        
        .blocked-icon {
            font-size: 4rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card blocked-card">
                    <!-- Header Section -->
                    <div class="header-section">
                        <div class="blocked-icon">
                            <i class="mdi mdi-shield-off"></i>
                        </div>
                        <h2 class="mt-3 mb-2">Access Blocked</h2>
                        <p class="mb-0 opacity-75">Your IP address has been temporarily restricted</p>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="content-section">
                        <div class="alert alert-danger border-0">
                            <h5 class="alert-heading">
                                <i class="mdi mdi-alert-circle me-2"></i>Security Restriction
                            </h5>
                            <p class="mb-0">{{ $message ?? 'Access from your IP address has been temporarily blocked due to suspicious activity.' }}</p>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="mb-3">Why was I blocked?</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="mdi mdi-check-circle text-muted me-2"></i>
                                    Multiple failed login attempts were detected from your IP address
                                </li>
                                <li class="mb-2">
                                    <i class="mdi mdi-check-circle text-muted me-2"></i>
                                    This is an automated security measure to protect our system
                                </li>
                                <li class="mb-2">
                                    <i class="mdi mdi-check-circle text-muted me-2"></i>
                                    The block is temporary and will be lifted automatically
                                </li>
                                <li class="mb-0">
                                    <i class="mdi mdi-check-circle text-muted me-2"></i>
                                    No action is required from you at this time
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="mb-3">What can I do?</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body text-center p-3">
                                            <i class="mdi mdi-clock-outline text-primary fs-4"></i>
                                            <h6 class="mt-2">Wait it out</h6>
                                            <small class="text-muted">The block will be automatically lifted after 24 hours</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body text-center p-3">
                                            <i class="mdi mdi-phone text-success fs-4"></i>
                                            <h6 class="mt-2">Contact Support</h6>
                                            <small class="text-muted">If you believe this is an error, contact our support team</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="mdi mdi-information me-1"></i>
                                Your IP: {{ request()->ip() }} | Time: {{ now()->format('M d, Y g:i A') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>