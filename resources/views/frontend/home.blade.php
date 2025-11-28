<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EMS - Electronics Medical System | Hospital Management Software</title>
    <meta name="description" content="Complete hospital management solution with patient records, appointments, billing, pharmacy, laboratory and more.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Light Mode Variables */
        :root[data-theme="light"] {
            --primary-color: #4f46e5;
            --secondary-color: #06b6d4;
            --bg-color: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --navbar-bg: rgba(255, 255, 255, 0.95);
            --card-bg: #ffffff;
            --hero-gradient-start: #f1f5f9;
            --hero-gradient-end: #e2e8f0;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --feature-bg: #f8fafc;
        }
        
        /* Dark Mode Variables */
        :root[data-theme="dark"],
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #06b6d4;
            --bg-color: #0f172a;
            --bg-secondary: #1e293b;
            --text-primary: #e2e8f0;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --navbar-bg: rgba(15, 23, 42, 0.95);
            --card-bg: #1e293b;
            --hero-gradient-start: #0f172a;
            --hero-gradient-end: #1e293b;
            --shadow-color: rgba(0, 0, 0, 0.5);
            --feature-bg: #1e293b;
        }
        
        * {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        body {
            background: var(--bg-color);
            color: var(--text-primary);
            overflow-x: hidden;
        }
        
        /* Navigation */
        .navbar {
            background: var(--navbar-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 10px var(--shadow-color);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-primary) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            color: white;
        }
        
        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .btn-theme-toggle {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-theme-toggle:hover {
            border-color: var(--primary-color);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--hero-gradient-start), var(--hero-gradient-end));
            padding: 6rem 0 4rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(79, 70, 229, 0.1), transparent 50%),
                        radial-gradient(circle at 80% 50%, rgba(6, 182, 212, 0.1), transparent 50%);
            pointer-events: none;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            color: var(--text-primary);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
            color: white;
        }
        
        .btn-secondary-custom {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary-custom:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .hero-image {
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 60px var(--shadow-color);
            overflow: hidden;
        }
        
        .hero-image img {
            width: 100%;
            height: auto;
        }
        
        /* Hero Carousel Styles */
        .hero-carousel {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px var(--shadow-color);
        }
        
        .hero-carousel .carousel-inner {
            border-radius: 20px;
        }
        
        .hero-carousel .carousel-item {
            transition: transform 0.6s ease-in-out;
        }
        
        .hero-carousel .hero-image {
            height: 500px;
            position: relative;
            margin: 0;
            box-shadow: none;
        }
        
        .hero-carousel .hero-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
        }
        
        .hero-carousel .carousel-caption {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem;
            text-align: left;
        }
        
        .hero-carousel .carousel-caption h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .hero-carousel .carousel-caption p {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
        }
        
        .hero-carousel .carousel-control-prev,
        .hero-carousel .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(79, 70, 229, 0.8);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .hero-carousel:hover .carousel-control-prev,
        .hero-carousel:hover .carousel-control-next {
            opacity: 1;
        }
        
        .hero-carousel .carousel-control-prev {
            left: 20px;
        }
        
        .hero-carousel .carousel-control-next {
            right: 20px;
        }
        
        .hero-carousel .carousel-control-prev-icon,
        .hero-carousel .carousel-control-next-icon {
            width: 20px;
            height: 20px;
        }
        
        .hero-carousel .carousel-indicators {
            margin-bottom: 1.5rem;
        }
        
        .hero-carousel .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            margin: 0 5px;
        }
        
        .hero-carousel .carousel-indicators button.active {
            background-color: var(--primary-color);
            width: 30px;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .hero-carousel .hero-image {
                height: 350px;
            }
            
            .hero-carousel .hero-image img {
                height: 350px;
            }
            
            .hero-carousel .carousel-caption h5 {
                font-size: 1.2rem;
            }
            
            .hero-carousel .carousel-caption p {
                font-size: 0.9rem;
            }
        }
        
        /* Stats Section */
        .stats-section {
            background: var(--bg-secondary);
            padding: 3rem 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: var(--bg-color);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px var(--shadow-color);
            border-color: var(--primary-color);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            color: var(--primary-color);
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }
        
        .feature-description {
            font-size: 0.95rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        /* Modules Section */
        .modules-section {
            background: var(--bg-secondary);
            padding: 5rem 0;
        }
        
        .module-category {
            margin-bottom: 3rem;
        }
        
        .category-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }
        
        .module-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .module-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .module-item:hover {
            border-color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .module-item i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }
        
        .module-item span {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 5rem 0;
            text-align: center;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }
        
        .cta-text {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
        }
        
        .btn-white-custom {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .btn-white-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            color: var(--primary-color);
        }
        
        /* Footer */
        .footer {
            background: var(--card-bg);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0 1.5rem 0;
        }
        
        .footer-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .footer-link {
            display: block;
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: var(--primary-color);
        }
        
        .footer-bottom {
            border-top: 1px solid var(--border-color);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: var(--text-secondary);
        }
        
        .social-links a {
            color: var(--text-secondary);
            font-size: 1.5rem;
            margin: 0 0.75rem;
            transition: color 0.3s ease;
        }
        
        .social-links a:hover {
            color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 2rem;
            }
            
            .module-list {
                grid-template-columns: 1fr;
            }
        }
        
        /* Login Modal */
        .modal-content {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }
        
        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }
        
        .modal-title {
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .btn-close {
            filter: var(--bs-btn-close-white-filter);
            opacity: 0.6;
        }
        
        :root[data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }
        
        .form-control:focus {
            background: var(--bg-secondary);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .form-check-input {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-label {
            color: var(--text-secondary);
        }
        
        .modal-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .modal-link:hover {
            text-decoration: underline;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }
        
        .divider span {
            padding: 0 1rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        .btn-social {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-social:hover {
            border-color: var(--primary-color);
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <span class="brand-icon">EMS</span>
                <span>EMS</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#modules">Modules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-2">
                        <button type="button" class="btn btn-theme-toggle" id="themeToggle" title="Toggle Theme">
                            <i class="bi bi-sun-fill" id="themeIcon"></i>
                        </button>
                    </li>
                    <li class="nav-item ms-2">
                        <button type="button" class="btn btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#loginModal" style="padding: 0.6rem 1.5rem; font-size: 1rem;">Login</button>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="#" class="btn btn-primary-custom" style="padding: 0.6rem 1.5rem; font-size: 1rem;">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Complete Hospital Management System</h1>
                    <p class="hero-subtitle">Streamline your healthcare operations with our comprehensive Electronics Medical System. Manage patients, appointments, billing, pharmacy, laboratory, and more - all in one platform.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#" class="btn btn-primary-custom">Start Free Trial</a>
                        <a href="#" class="btn btn-secondary-custom">Watch Demo</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <!-- Image Carousel -->
                    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="5"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="6"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="7"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="8"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="9"></button>
                        </div>
                        
                        <div class="carousel-inner">
                            <!-- Slide 1: Doctor with Patient -->
                            <div class="carousel-item active">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800&h=600&fit=crop" 
                                         alt="Doctor consulting with patient" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Professional Healthcare</h5>
                                        <p>Expert medical consultations</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 2: Surgeon Team -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800&h=600&fit=crop" 
                                         alt="Surgical team in operating room" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Advanced Surgery</h5>
                                        <p>State-of-the-art operating facilities</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 3: Nurse Care -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?w=800&h=600&fit=crop" 
                                         alt="Nurse providing patient care" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Compassionate Nursing</h5>
                                        <p>24/7 patient care and support</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 4: Hospital Building -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&h=600&fit=crop" 
                                         alt="Modern hospital building exterior" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Modern Facilities</h5>
                                        <p>World-class healthcare infrastructure</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 5: Medical Technology -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1516549655169-df83a0774514?w=800&h=600&fit=crop" 
                                         alt="Advanced medical technology and equipment" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Advanced Technology</h5>
                                        <p>Cutting-edge medical equipment</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 6: Laboratory -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1582719471137-c3967ffb1c42?w=800&h=600&fit=crop" 
                                         alt="Medical laboratory with testing equipment" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Diagnostic Excellence</h5>
                                        <p>Comprehensive laboratory services</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 7: Emergency Room -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=800&h=600&fit=crop" 
                                         alt="Emergency room with medical staff" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Emergency Care</h5>
                                        <p>Rapid response medical services</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 8: Pharmacy -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1585435557343-3b092031a831?w=800&h=600&fit=crop" 
                                         alt="Hospital pharmacy with medications" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Full-Service Pharmacy</h5>
                                        <p>Complete medication management</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 9: Medical Team Meeting -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1551076805-e1869033e561?w=800&h=600&fit=crop" 
                                         alt="Medical team in conference" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Collaborative Care</h5>
                                        <p>Multidisciplinary medical teams</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Slide 10: Patient Ward -->
                            <div class="carousel-item">
                                <div class="hero-image">
                                    <img src="https://images.unsplash.com/photo-1519494140681-8b17d830a3e9?w=800&h=600&fit=crop" 
                                         alt="Modern patient ward and rooms" 
                                         class="d-block w-100">
                                    <div class="carousel-caption">
                                        <h5>Comfortable Recovery</h5>
                                        <p>Private patient rooms and amenities</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Hospitals Using EMS</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">1M+</div>
                        <div class="stat-label">Patients Managed</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support Available</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime Guarantee</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">Powerful Features for Modern Healthcare</h2>
            <p class="section-subtitle">Everything you need to run a successful healthcare facility, all in one integrated platform.</p>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-person-hearts"></i>
                        </div>
                        <h3 class="feature-title">Patient Management</h3>
                        <p class="feature-description">Complete patient records, demographics, medical history, allergies, and comprehensive health timelines.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h3 class="feature-title">Smart Scheduling</h3>
                        <p class="feature-description">Automated appointment booking, doctor availability, SMS/Email reminders, and calendar integration.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-prescription2"></i>
                        </div>
                        <h3 class="feature-title">E-Prescriptions</h3>
                        <p class="feature-description">Digital prescriptions with drug interaction alerts, allergy warnings, and pharmacy integration.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </div>
                        <h3 class="feature-title">Laboratory System</h3>
                        <p class="feature-description">Lab test requests, specimen tracking, automated results, and LIS integration.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h3 class="feature-title">Billing & Insurance</h3>
                        <p class="feature-description">Invoice generation, payment processing, HMO claims, and comprehensive financial reporting.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h3 class="feature-title">Ward Management</h3>
                        <p class="feature-description">Bed allocation, patient admissions, nursing notes, and discharge summaries.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section class="modules-section" id="modules">
        <div class="container">
            <h2 class="section-title">Comprehensive System Modules</h2>
            <p class="section-subtitle">Over 20+ integrated modules to handle every aspect of your healthcare facility.</p>
            
            <div class="module-category">
                <h3 class="category-title">Clinical Management</h3>
                <div class="module-list">
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Patient Registration</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Appointments & Scheduling</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Outpatient (OPD)</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Inpatient / Ward</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Emergency (A&E)</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Maternity & ANC</span>
                    </div>
                </div>
            </div>
            
            <div class="module-category">
                <h3 class="category-title">Medical Services</h3>
                <div class="module-list">
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>E-Prescriptions</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Pharmacy Management</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Laboratory</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Radiology & Imaging</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Procedures & Theatre</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Immunization</span>
                    </div>
                </div>
            </div>
            
            <div class="module-category">
                <h3 class="category-title">Financial & Operations</h3>
                <div class="module-list">
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Billing & Payments</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Insurance / HMO</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Inventory Management</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>HR & Staff Management</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Reports & Analytics</span>
                    </div>
                    <div class="module-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Telemedicine</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="pricing">
        <div class="container">
            <h2 class="cta-title">Ready to Transform Your Healthcare Facility?</h2>
            <p class="cta-text">Start your 30-day free trial today. No credit card required.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="#" class="btn btn-white-custom">Start Free Trial</a>
                <a href="#" class="btn btn-secondary-custom" style="background: transparent; border-color: white; color: white;">Contact Sales</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="navbar-brand mb-3">
                        <span class="brand-icon">EMS</span>
                        <span>EMS</span>
                    </div>
                    <p style="color: var(--text-secondary);">Complete hospital management solution for modern healthcare facilities.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-6 col-lg-2 mb-4">
                    <h5 class="footer-title">Product</h5>
                    <a href="#" class="footer-link">Features</a>
                    <a href="#" class="footer-link">Modules</a>
                    <a href="#" class="footer-link">Pricing</a>
                    <a href="#" class="footer-link">Updates</a>
                </div>
                
                <div class="col-6 col-lg-2 mb-4">
                    <h5 class="footer-title">Company</h5>
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Careers</a>
                    <a href="#" class="footer-link">Contact</a>
                    <a href="#" class="footer-link">Blog</a>
                </div>
                
                <div class="col-6 col-lg-2 mb-4">
                    <h5 class="footer-title">Resources</h5>
                    <a href="#" class="footer-link">Documentation</a>
                    <a href="#" class="footer-link">Help Center</a>
                    <a href="#" class="footer-link">Tutorials</a>
                    <a href="#" class="footer-link">API</a>
                </div>
                
                <div class="col-6 col-lg-2 mb-4">
                    <h5 class="footer-title">Legal</h5>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">HIPAA Compliance</a>
                    <a href="#" class="footer-link">Security</a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="mb-0">&copy; 2024 EMS - Electronics Medical System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login to EMS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Display session messages --}}
                    @if(session('message'))
                        <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" id="loginForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="loginEmail" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="loginPassword" 
                                   name="password" 
                                   placeholder="Enter your password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom w-100 mb-3">Sign In to Portal</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        // Get saved theme from localStorage or default to 'dark'
        const savedTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);

        // Theme toggle button click handler
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        // Update theme icon based on current theme
        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.classList.remove('bi-moon-fill');
                themeIcon.classList.add('bi-sun-fill');
                themeToggle.setAttribute('title', 'Switch to Light Mode');
            } else {
                themeIcon.classList.remove('bi-sun-fill');
                themeIcon.classList.add('bi-moon-fill');
                themeToggle.setAttribute('title', 'Switch to Dark Mode');
            }
        }

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Auto-open modal if there are validation errors
        @if($errors->any())
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        @endif
    </script>
</body>
</html>