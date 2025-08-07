<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G.O Office Digital Platform - Connecting Faith Communities</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e40af',
                        accent: '#f59e0b',
                        dark: '#1f2937'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans">

    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-church text-2xl text-primary mr-2"></i>
                        <span class="font-bold text-xl text-gray-800">G.O Office Platform</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-primary transition duration-300">Features</a>
                    <a href="#about" class="text-gray-600 hover:text-primary transition duration-300">About</a>
                    <a href="#pricing" class="text-gray-600 hover:text-primary transition duration-300">Pricing</a>
                    <a href="#contact" class="text-gray-600 hover:text-primary transition duration-300">Contact</a>
                    <button class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition duration-300">
                        Get Started
                    </button>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="text-gray-600 hover:text-gray-800" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#features" class="block px-3 py-2 text-gray-600 hover:text-primary">Features</a>
                <a href="#about" class="block px-3 py-2 text-gray-600 hover:text-primary">About</a>
                <a href="#pricing" class="block px-3 py-2 text-gray-600 hover:text-primary">Pricing</a>
                <a href="#contact" class="block px-3 py-2 text-gray-600 hover:text-primary">Contact</a>
                <button class="w-full mt-2 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition duration-300">
                    Get Started
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-8">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    Empowering Churches Through
                    <span class="text-yellow-300">Digital Innovation</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
                    A comprehensive digital platform designed to enhance communication, foster spiritual growth, 
                    and streamline church operations for the modern faith community.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-primary px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-rocket mr-2"></i>Start Your Journey
                    </button>
                    <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-primary transition duration-300">
                        <i class="fas fa-play mr-2"></i>Watch Demo
                    </button>
                </div>
            </div>
        </div>
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-white opacity-10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-yellow-300 opacity-20 rounded-full animate-bounce"></div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Comprehensive Church Management</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything your church needs to thrive in the digital age, all in one powerful platform.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Communication Features -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="bg-blue-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-comments text-2xl text-primary"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Bulk Messaging</h3>
                    <p class="text-gray-600 mb-4">Reach your entire congregation instantly with SMS and email messaging. Send personalized updates, event reminders, and prayer requests.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• SMS & Email Integration</li>
                        <li>• Group Targeting</li>
                        <li>• Delivery Tracking</li>
                    </ul>
                </div>

                <!-- Event Management -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="bg-green-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Event Management</h3>
                    <p class="text-gray-600 mb-4">Organize and manage church events with online registration, automated reminders, and attendance tracking.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Online Registration</li>
                        <li>• Automated Reminders</li>
                        <li>• Attendance Tracking</li>
                    </ul>
                </div>

                <!-- Donation Platform -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="bg-yellow-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-heart text-2xl text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Online Giving</h3>
                    <p class="text-gray-600 mb-4">Secure online donation platform supporting multiple payment methods for tithes, offerings, and special contributions.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Multiple Payment Gateways</li>
                        <li>• Recurring Donations</li>
                        <li>• Tax Receipts</li>
                    </ul>
                </div>

                <!-- Sermon Archive -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="bg-purple-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-microphone text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Sermon Archive</h3>
                    <p class="text-gray-600 mb-4">Complete sermon library with audio, video, and text formats. Advanced search and categorization features.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Multi-format Support</li>
                        <li>• Search Functionality</li>
                        <li>• Download Options</li>
                    </ul>
                </div>

                <!-- Prayer Requests -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="bg-pink-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-praying-hands text-2xl text-pink-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Prayer Network</h3>
                    <p class="text-gray-600 mb-4">Enable members to submit prayer requests and testimonies. Foster a supportive community through shared faith.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• Prayer Request Forms</li>
                        <li>• Testimony Sharing</li>
                        <li>• Privacy Controls</li>
                    </ul>
                </div>

                <!-- Live Streaming -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                    <div class="bg-red-100 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-video text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Live Streaming</h3>
                    <p class="text-gray-600 mb-4">Broadcast services live to reach members who can't attend in person. Archive streams for later viewing.</p>
                    <ul class="text-sm text-gray-500 space-y-1">
                        <li>• HD Live Streaming</li>
                        <li>• Multi-platform Support</li>
                        <li>• Stream Archives</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Features Grid -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">More Powerful Features</h2>
                <p class="text-xl text-gray-600">Discover additional tools designed to enhance your church's digital presence</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                <div class="text-center group">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Member Directory</h4>
                    <p class="text-sm text-gray-600">Complete member management system</p>
                </div>

                <div class="text-center group">
                    <div class="bg-gradient-to-r from-green-500 to-teal-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-hands-helping text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Volunteer Portal</h4>
                    <p class="text-sm text-gray-600">Easy volunteer sign-up and management</p>
                </div>

                <div class="text-center group">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-newspaper text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Newsletter</h4>
                    <p class="text-sm text-gray-600">Automated newsletter system</p>
                </div>

                <div class="text-center group">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-book-open text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Bible Study</h4>
                    <p class="text-sm text-gray-600">Interactive study materials</p>
                </div>

                <div class="text-center group">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-bullhorn text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Announcements</h4>
                    <p class="text-sm text-gray-600">Real-time church updates</p>
                </div>

                <div class="text-center group">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                        <i class="fas fa-layer-group text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Groups</h4>
                    <p class="text-sm text-gray-600">Small group management</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 gradient-bg relative">
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Trusted by Churches Worldwide</h2>
                <p class="text-xl text-gray-200">Join the growing community of digitally empowered churches</p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">500+</div>
                    <div class="text-gray-200">Churches Served</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">50K+</div>
                    <div class="text-gray-200">Active Members</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">1M+</div>
                    <div class="text-gray-200">Messages Sent</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-2">99.9%</div>
                    <div class="text-gray-200">Uptime</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Plan</h2>
                <p class="text-xl text-gray-600">Flexible pricing options to fit churches of all sizes</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Starter</h3>
                        <div class="text-4xl font-bold text-primary mb-2">₦150K</div>
                        <div class="text-gray-600">One-time setup</div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Basic Website & Landing Page</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Member Registration</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Event Management</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Basic Messaging (100 SMS/month)</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Prayer Requests</span>
                        </li>
                    </ul>
                    <button class="w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                        Get Started
                    </button>
                </div>

                <!-- Professional Plan -->
                <div class="bg-white rounded-xl shadow-xl p-8 border-2 border-primary relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <div class="bg-primary text-white px-4 py-2 rounded-full text-sm font-semibold">
                            Most Popular
                        </div>
                    </div>
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Professional</h3>
                        <div class="text-4xl font-bold text-primary mb-2">₦300K</div>
                        <div class="text-gray-600">One-time setup</div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Everything in Starter</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Online Donation Platform</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Sermon Archive System</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Advanced Messaging (500 SMS/month)</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Newsletter System</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Volunteer Management</span>
                        </li>
                    </ul>
                    <button class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-secondary transition duration-300">
                        Get Started
                    </button>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
                        <div class="text-4xl font-bold text-primary mb-2">₦500K</div>
                        <div class="text-gray-600">One-time setup</div>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Everything in Professional</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Live Streaming Integration</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Advanced Analytics</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Unlimited Messaging</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Custom Integrations</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span class="text-gray-700">Priority Support</span>
                        </li>
                    </ul>
                    <button class="w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                        Contact Sales
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Transform Your Church?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of churches already using our platform to connect, grow, and serve their communities better.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-primary px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition duration-300">
                    Start Free Trial
                </button>
                <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-primary transition duration-300">
                    Schedule Demo
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-church text-2xl text-primary mr-2"></i>
                        <span class="font-bold text-xl">G.O Office Platform</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        Empowering churches worldwide with comprehensive digital solutions for community building, 
                        communication, and spiritual growth in the modern age.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-6">Features</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Bulk Messaging</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Event Management</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Online Giving</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Live Streaming</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Member Portal</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-lg mb-6">Contact</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-phone mr-3"></i>
                            <span>08035543036</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>info@newinfoglobal.com</span>
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            <span>Lagos, Nigeria</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-400 mb-4 md:mb-0">
                        <p>&copy; 2025 G.O Office Platform by Newinfo Global Solutions Ltd. All rights reserved.</p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-8 right-8 bg-primary text-white w-12 h-12 rounded-full shadow-lg hover:bg-secondary transition duration-300 transform hover:scale-110 hidden z-50">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Smooth Scrolling
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

        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('nav');
            if (window.pageYOffset > 50) {
                navbar.classList.add('bg-white', 'shadow-lg');
                navbar.classList.remove('bg-transparent');
            } else {
                navbar.classList.remove('bg-white', 'shadow-lg');
                navbar.classList.add('bg-transparent');
            }
        });

        // Animation on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Add animation classes
        const animatedElements = document.querySelectorAll('.feature-card, .stat-item, .pricing-card');
        animatedElements.forEach(el => observer.observe(el));

        // Add CSS for fade-in animation
        const style = document.createElement('style');
        style.textContent = `
            .animate-fade-in {
                animation: fadeInUp 0.6s ease-out forwards;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .feature-card, .stat-item, .pricing-card {
                opacity: 0;
                transform: translateY(30px);
            }
        `;
        document.head.appendChild(style);

        // Counter Animation for Stats
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = target + (element.dataset.suffix || '');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start) + (element.dataset.suffix || '');
                }
            }, 16);
        }

        // Trigger counter animation when stats section is visible
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counters = entry.target.querySelectorAll('[data-count]');
                        counters.forEach(counter => {
                            const target = parseInt(counter.dataset.count);
                            animateCounter(counter, target);
                        });
                        statsObserver.unobserve(entry.target);
                    }
                });
            });
            statsObserver.observe(statsSection);
        }

        // Form Validation (if forms are added)
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Newsletter Subscription (placeholder)
        function subscribeNewsletter(email) {
            if (validateEmail(email)) {
                // Show success message
                alert('Thank you for subscribing to our newsletter!');
                return true;
            } else {
                alert('Please enter a valid email address.');
                return false;
            }
        }

        // Contact Form Handler (placeholder)
        function handleContactForm(formData) {
            // This would typically send data to your Laravel backend
            console.log('Contact form submitted:', formData);
            alert('Thank you for your message! We will get back to you soon.');
        }

        // Demo Request Handler
        function requestDemo() {
            // This would typically open a modal or redirect to a demo form
            alert('Demo request received! Our team will contact you within 24 hours.');
        }

        // Get Started Button Handler
        function getStarted() {
            // This would typically redirect to registration or contact page
            window.location.href = '#contact';
        }

        // Add event listeners for CTA buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Get Started buttons
            const getStartedBtns = document.querySelectorAll('button:contains("Get Started"), button:contains("Start")');
            getStartedBtns.forEach(btn => {
                if (btn.textContent.includes('Get Started') || btn.textContent.includes('Start')) {
                    btn.addEventListener('click', getStarted);
                }
            });

            // Demo buttons
            const demoBtns = document.querySelectorAll('button:contains("Demo")');
            demoBtns.forEach(btn => {
                if (btn.textContent.includes('Demo')) {
                    btn.addEventListener('click', requestDemo);
                }
            });
        });

        // Loading Animation
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });

        // Add loading styles
        const loadingStyle = document.createElement('style');
        loadingStyle.textContent = `
            body:not(.loaded) {
                overflow: hidden;
            }
            
            body:not(.loaded)::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: white;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            body:not(.loaded)::after {
                content: '';
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 50px;
                height: 50px;
                border: 3px solid #f3f3f3;
                border-top: 3px solid #2563eb;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                z-index: 10000;
            }
            
            @keyframes spin {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }
        `;
        document.head.appendChild(loadingStyle);
    </script>

</body>
</html>