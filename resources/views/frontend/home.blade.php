{{-- File: resources/views/frontend/home.blade.php --}}
@extends('frontend.master')
@section('title', 'Welcome to G.O Office')
@section('content')

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center gradient-bg overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></g></svg>');"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <div class="fade-in">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Welcome to the<br>
                <span class="text-yellow-300">G.O Office Portal</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                Connect with the divine through prayer, receive spiritual guidance, and experience the transformative power of faith under the leadership of our beloved Daddy G.O.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('laying-hands.form') }}" 
                   class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-hands-helping mr-2"></i>
                    Request Laying of Hands
                </a>
                <a href="{{ route('about') }}" 
                   class="bg-transparent border-2 border-white hover:bg-white hover:text-gray-900 text-white font-bold py-4 px-8 rounded-full transition-all duration-300">
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-white text-2xl"></i>
    </div>
</section>

<!-- Quick Services Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Experience spiritual growth and divine connection through our comprehensive range of services designed to strengthen your faith journey.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Laying of Hands -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-100 group">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors duration-300">
                    <i class="fas fa-hands-helping text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Laying of Hands</h3>
                <p class="text-gray-600 mb-4">Request personal prayer and divine intervention from Daddy G.O for healing, breakthrough, and spiritual guidance.</p>
                <a href="{{ route('laying-hands.form') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                    Request Now <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Prayer Requests -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-100 group">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-200 transition-colors duration-300">
                    <i class="fas fa-praying-hands text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Prayer Requests</h3>
                <p class="text-gray-600 mb-4">Submit your prayer requests and join our community in lifting each other up through the power of collective prayer.</p>
                <a href="#" class="text-green-600 hover:text-green-800 font-medium inline-flex items-center">
                    Submit Prayer <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Live Sermons -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-100 group">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors duration-300">
                    <i class="fas fa-video text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Live Sermons</h3>
                <p class="text-gray-600 mb-4">Watch live services and access our extensive library of sermons for spiritual nourishment and growth.</p>
                <a href="{{ route('sermons') }}" class="text-purple-600 hover:text-purple-800 font-medium inline-flex items-center">
                    Watch Now <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Online Giving -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-100 group">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition-colors duration-300">
                    <i class="fas fa-heart text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Online Giving</h3>
                <p class="text-gray-600 mb-4">Support the ministry through secure online donations and be part of spreading God's love worldwide.</p>
                <a href="{{ route('donations') }}" class="text-yellow-600 hover:text-yellow-800 font-medium inline-flex items-center">
                    Give Now <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Events -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-100 group">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-red-200 transition-colors duration-300">
                    <i class="fas fa-calendar-alt text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Upcoming Events</h3>
                <p class="text-gray-600 mb-4">Stay updated with our upcoming events, conferences, and special services designed to strengthen your faith.</p>
                <a href="{{ route('events') }}" class="text-red-600 hover:text-red-800 font-medium inline-flex items-center">
                    View Events <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <!-- Testimonies -->
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-100 group">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors duration-300">
                    <i class="fas fa-star text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Testimonies</h3>
                <p class="text-gray-600 mb-4">Read inspiring testimonies of God's faithfulness and share your own miraculous experiences with our community.</p>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                    Read Stories <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">About Daddy G.O</h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    Pastor E.A. Adeboye, lovingly called Daddy G.O, has been a beacon of hope and spiritual guidance for millions worldwide. His ministry has transformed countless lives through the power of prayer, divine intervention, and unwavering faith in God's promises.
                </p>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Through this portal, you can connect directly with his office for personal prayer requests, laying of hands sessions, and spiritual guidance that has brought breakthrough to families, businesses, and individuals across the globe.
                </p>
                <a href="{{ route('about') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 inline-flex items-center">
                    Learn More <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="relative">
                <div class="bg-gradient-to-r from-blue-400 to-purple-500 rounded-2xl p-8 text-white">
                    <i class="fas fa-quote-left text-4xl mb-4 opacity-50"></i>
                    <blockquote class="text-xl italic mb-4">
                        "Prayer is not a ritual; it is a relationship with the Almighty God who hears and answers when we call upon Him in faith."
                    </blockquote>
                    <cite class="text-lg font-semibold">- Pastor E.A. Adeboye</cite>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Impact</h2>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                See how God has been faithful through our ministry over the years
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-yellow-400 mb-2">500K+</div>
                <p class="text-gray-300">Prayer Requests Answered</p>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-yellow-400 mb-2">1M+</div>
                <p class="text-gray-300">Lives Transformed</p>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-yellow-400 mb-2">200+</div>
                <p class="text-gray-300">Countries Reached</p>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold text-yellow-400 mb-2">50+</div>
                <p class="text-gray-300">Years of Ministry</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Testimonies -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Recent Testimonies</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Read how God has been working miracles in the lives of our community members
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Sarah M.</h4>
                        <p class="text-gray-600 text-sm">Lagos, Nigeria</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "After Daddy G.O prayed for my marriage, God restored my home completely. My husband came back and we are stronger than ever. Glory to God!"
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">John K.</h4>
                        <p class="text-gray-600 text-sm">Abuja, Nigeria</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "The business breakthrough came exactly as Daddy G.O prophesied. From near bankruptcy to millions in contracts. God is faithful!"
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Grace O.</h4>
                        <p class="text-gray-600 text-sm">London, UK</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Complete healing from cancer after Daddy G.O laid hands on me during the laying of hands session. Jesus is Lord!"
                </p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 inline-flex items-center">
                Read More Testimonies <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 gradient-bg text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Experience God's Power?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto opacity-90">
            Don't wait any longer. Submit your request for laying of hands and experience the miraculous power of God in your life today.
        </p>
        <a href="{{ route('laying-hands.form') }}" 
           class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center">
            <i class="fas fa-hands-helping mr-2"></i>
            Request Laying of Hands Now
        </a>
    </div>
</section>

@endsection