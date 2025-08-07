<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | EMR Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #6366f1 100%);
            background-size: 300% 300%;
            animation: gradientShift 6s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.15);
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ef4444, #f97316, #eab308, #22c55e, #3b82f6, #8b5cf6);
            background-size: 300% 100%;
            animation: shimmer 3s linear infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -300% 0; }
            100% { background-position: 300% 0; }
        }
        
        .logo-container {
            margin-bottom: 30px;
            position: relative;
        }
        
        .logo {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 10px 25px rgba(0, 0, 0, 0.1),
                0 0 0 8px rgba(255, 255, 255, 0.8),
                0 0 0 12px rgba(59, 130, 246, 0.1);
            position: relative;
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 
                    0 10px 25px rgba(0, 0, 0, 0.1),
                    0 0 0 8px rgba(255, 255, 255, 0.8),
                    0 0 0 12px rgba(59, 130, 246, 0.1);
            }
            50% {
                box-shadow: 
                    0 15px 35px rgba(0, 0, 0, 0.15),
                    0 0 0 8px rgba(255, 255, 255, 0.9),
                    0 0 0 16px rgba(59, 130, 246, 0.15);
            }
        }
        
        .logo img {
            max-width: 70px;
            max-height: 70px;
            border-radius: 50%;
        }
        
        .welcome-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }
        
        .welcome-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 35px;
            font-weight: 500;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 16px;
            font-weight: 500;
            background: #f8fafc;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        
        .form-control:focus {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
            transform: translateY(-1px);
        }
        
        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        
        .form-control.is-invalid {
            border-color: #ef4444;
        }
        
        .text-danger {
            font-size: 0.85rem;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 25px 0;
            text-align: left;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            background: white;
            margin: 0;
        }
        
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        
        .form-check-label {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            padding: 16px 24px;
            width: 100%;
            font-weight: 600;
            font-size: 16px;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(59, 130, 246, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        
        .footer-text {
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 20px;
        }
        
        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 8s linear infinite;
        }
        
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 1s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 2s; }
        .particle:nth-child(4) { left: 40%; animation-delay: 3s; }
        .particle:nth-child(5) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(6) { left: 60%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 70%; animation-delay: 6s; }
        .particle:nth-child(8) { left: 80%; animation-delay: 7s; }
        .particle:nth-child(9) { left: 90%; animation-delay: 8s; }
        
        @keyframes float {
            0% {
                top: 100%;
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                top: -10%;
                opacity: 0;
            }
        }
        
        /* Idle Animation Effects */
        .login-card.idle-mode {
            animation: idleAnimation 8s ease-in-out infinite;
        }
        
        .login-card.idle-mode .logo {
            animation: idleLogoSpin 4s linear infinite;
        }
        
        .login-card.idle-mode .welcome-text {
            animation: idleTextBounce 2s ease-in-out infinite;
        }
        
        .login-card.idle-mode .form-control {
            animation: idleInputGlow 3s ease-in-out infinite;
        }
        
        .login-card.idle-mode .btn-login {
            animation: idleBtnPulse 1.5s ease-in-out infinite;
        }
        
        @keyframes idleAnimation {
            0%, 100% {
                transform: translateX(0) translateY(0) scale(1) rotate(0deg);
            }
            10% {
                transform: translateX(15px) translateY(-10px) scale(1.02) rotate(1deg);
            }
            20% {
                transform: translateX(-10px) translateY(15px) scale(0.98) rotate(-1deg);
            }
            30% {
                transform: translateX(20px) translateY(-5px) scale(1.03) rotate(2deg);
            }
            40% {
                transform: translateX(-15px) translateY(-20px) scale(0.97) rotate(-2deg);
            }
            50% {
                transform: translateX(0) translateY(20px) scale(1.05) rotate(0deg);
            }
            60% {
                transform: translateX(25px) translateY(10px) scale(0.95) rotate(3deg);
            }
            70% {
                transform: translateX(-20px) translateY(-15px) scale(1.04) rotate(-1deg);
            }
            80% {
                transform: translateX(10px) translateY(25px) scale(0.96) rotate(2deg);
            }
            90% {
                transform: translateX(-5px) translateY(-10px) scale(1.01) rotate(-3deg);
            }
        }
        
        @keyframes idleLogoSpin {
            0% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(90deg) scale(1.1); }
            50% { transform: rotate(180deg) scale(1.2); }
            75% { transform: rotate(270deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }
        
        @keyframes idleTextBounce {
            0%, 100% {
                transform: translateY(0) scale(1);
                color: #1e293b;
            }
            25% {
                transform: translateY(-8px) scale(1.05);
                color: #3b82f6;
            }
            50% {
                transform: translateY(-15px) scale(1.1);
                color: #8b5cf6;
            }
            75% {
                transform: translateY(-8px) scale(1.05);
                color: #ef4444;
            }
        }
        
        @keyframes idleInputGlow {
            0%, 100% {
                border-color: #e2e8f0;
                box-shadow: none;
            }
            50% {
                border-color: #3b82f6;
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
            }
        }
        
        @keyframes idleBtnPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 12px 32px rgba(59, 130, 246, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 15px 40px rgba(59, 130, 246, 0.6);
            }
        }
        
        /* Enhanced particle effects during idle */
        .particles.idle-mode .particle {
            animation-duration: 3s;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .particles.idle-mode .particle:nth-child(odd) {
            animation-direction: reverse;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .welcome-text {
                font-size: 1.3rem;
            }
            
            .logo {
                width: 80px;
                height: 80px;
            }
            
            .logo img {
                max-width: 60px;
                max-height: 60px;
            }
            
            /* Reduce idle animation intensity on mobile */
            .login-card.idle-mode {
                animation: idleAnimationMobile 6s ease-in-out infinite;
            }
            
            @keyframes idleAnimationMobile {
                0%, 100% {
                    transform: translateX(0) translateY(0) scale(1) rotate(0deg);
                }
                25% {
                    transform: translateX(8px) translateY(-5px) scale(1.01) rotate(1deg);
                }
                50% {
                    transform: translateX(-8px) translateY(10px) scale(0.99) rotate(-1deg);
                }
                75% {
                    transform: translateX(5px) translateY(-8px) scale(1.02) rotate(0.5deg);
                }
            }
        }
    </style>
</head>

<body>
    @auth
        <script>window.location.href = "{{ route('admin.dashboard') }}";</script>
    @else
        <!-- Floating particles -->
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <div class="login-card">
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('upload/EMR_logo-removebg-preview.png') }}" alt="EMR Logo">
                </div>
            </div>
            
            <h1 class="welcome-text">Welcome Back</h1>
            <p class="welcome-subtitle">Login to access your EMR portal dashboard</p>

            @if(session('message'))
                <div class="alert alert-{{ session('alert-type', 'info') }}">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <input class="form-control @error('email') is-invalid @enderror" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Email Address" 
                           required>
                    @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <input class="form-control @error('password') is-invalid @enderror" 
                           type="password" 
                           name="password" 
                           placeholder="Password" 
                           required>
                    @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me for 30 days</label>
                </div>

                <button class="btn btn-login" type="submit">
                    Sign In to Portal
                </button>
            </form>

            <div class="footer-text">
                Secure access to EMR portal
            </div>
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Idle detection and animation system
        let idleTimer;
        let isIdle = false;
        const IDLE_TIME = 30 * 1000; // 30 seconds in milliseconds
        
        const loginCard = document.querySelector('.login-card');
        const particles = document.querySelector('.particles');
        const formInputs = document.querySelectorAll('input, button');
        
        // Reset idle timer
        function resetIdleTimer() {
            clearTimeout(idleTimer);
            
            // Remove idle mode if active
            if (isIdle) {
                loginCard.classList.remove('idle-mode');
                particles.classList.remove('idle-mode');
                isIdle = false;
                console.log('User activity detected - idle mode disabled');
            }
            
            // Set new timer
            idleTimer = setTimeout(() => {
                activateIdleMode();
            }, IDLE_TIME);
        }
        
        // Activate idle animations
        function activateIdleMode() {
            if (!isIdle) {
                loginCard.classList.add('idle-mode');
                particles.classList.add('idle-mode');
                isIdle = true;
                console.log('Idle mode activated - fun animations started!');
                
                // Add some random effects
                setTimeout(() => {
                    if (isIdle) {
                        loginCard.style.filter = 'hue-rotate(180deg)';
                    }
                }, 5000);
                
                setTimeout(() => {
                    if (isIdle) {
                        loginCard.style.filter = 'hue-rotate(0deg)';
                    }
                }, 10000);
            }
        }
        
        // Events that reset the idle timer
        const resetEvents = [
            'mousedown', 'mousemove', 'keypress', 'scroll', 
            'touchstart', 'click', 'focus', 'input'
        ];
        
        // Add event listeners
        resetEvents.forEach(event => {
            document.addEventListener(event, resetIdleTimer, true);
        });
        
        // Special handling for form inputs
        formInputs.forEach(input => {
            input.addEventListener('focus', resetIdleTimer);
            input.addEventListener('input', resetIdleTimer);
            input.addEventListener('change', resetIdleTimer);
        });
        
        // Initialize timer
        resetIdleTimer();
        
        // Enhanced form interactions
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Fun easter egg - konami code for instant idle mode
        let konamiCode = [38,38,40,40,37,39,37,39,66,65];
        let konamiIndex = 0;
        
        document.addEventListener('keydown', function(e) {
            if (e.keyCode === konamiCode[konamiIndex]) {
                konamiIndex++;
                if (konamiIndex === konamiCode.length) {
                    activateIdleMode();
                    konamiIndex = 0;
                    console.log('Konami code activated - instant idle mode!');
                }
            } else {
                konamiIndex = 0;
            }
        });
        
        // Debug mode (remove in production)
        // Uncomment below to test idle mode after 5 seconds instead of 30 minutes
        // setTimeout(activateIdleMode, 5000);
    </script>
</body>
</html>