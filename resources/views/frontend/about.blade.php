@extends('frontend.master')
@section('title', 'About Us')
@section('content')

<!-- Hero Section -->
<section class="relative py-20 bg-gradient-to-r from-blue-600 to-purple-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">About Our Ministry</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto opacity-90">
                Discover the heart and vision behind the G.O Office Portal and how we're connecting hearts to divine transformation
            </p>
        </div>
    </div>
</section>

<!-- Pastor Adeboye Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Pastor E.A. Adeboye</h2>
                <h3 class="text-xl text-blue-600 font-semibold mb-4">General Overseer, The Redeemed Christian Church of God</h3>
                
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        Pastor Enoch Adejare Adeboye, fondly called "Daddy G.O" by millions worldwide, has been the General Overseer of The Redeemed Christian Church of God since 1981. Under his leadership, RCCG has grown from a small fellowship to one of the largest Pentecostal churches in the world, with presence in over 200 countries.
                    </p>
                    
                    <p>
                        Born on March 2, 1942, Pastor Adeboye is a man of God known for his humility, wisdom, and powerful prayers. He was a Professor of Mathematics at the University of Lagos before answering the call to full-time ministry. His academic background brings a unique dimension to his theological teachings.
                    </p>
                    
                    <p>
                        Pastor Adeboye is renowned for his prophecies, miraculous healings, and the power of his prayers. His monthly Holy Ghost Services attract millions of participants from around the globe. He has authored numerous books and his teachings have transformed countless lives.
                    </p>
                    
                    <p>
                        Through this portal, you have the privilege to connect directly with his office for personal prayer requests, laying of hands sessions, and spiritual guidance that has brought breakthrough to families, businesses, and individuals worldwide.
                    </p>
                </div>
            </div>
            
            <div class="order-1 lg:order-2">
                <div class="relative">
                    <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl p-8 shadow-xl">
                        <div class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-circle text-8xl text-gray-400"></i>
                        </div>
                        <div class="mt-6 text-center">
                            <h4 class="text-2xl font-bold text-gray-900">Pastor E.A. Adeboye</h4>
                            <p class="text-lg text-blue-600 font-semibold">Daddy G.O</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Vision & Mission</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Understanding our purpose and the divine mandate we carry
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vision -->
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-eye text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed text-lg">
                    To make heaven and to take as many people as possible with us. We envision a world where every individual has access to divine intervention, spiritual guidance, and the transformative power of God through prayer and faith.
                </p>
            </div>
            
            <!-- Mission -->
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-hands-helping text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h3>
                <p class="text-gray-600 leading-relaxed text-lg">
                    To facilitate divine connection between individuals and God through prayer, laying of hands, spiritual guidance, and pastoral care. We exist to serve as a bridge that brings God's miraculous power into everyday lives.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                The principles that guide everything we do in ministry
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Faith -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors duration-300">
                    <i class="fas fa-cross text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Faith</h3>
                <p class="text-gray-600">
                    Unwavering belief in God's power to transform lives and answer prayers according to His will and timing.
                </p>
            </div>
            
            <!-- Love -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors duration-300">
                    <i class="fas fa-heart text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Love</h3>
                <p class="text-gray-600">
                    Demonstrating Christ's love through compassionate service and genuine care for every individual who seeks help.
                </p>
            </div>
            
            <!-- Integrity -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors duration-300">
                    <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Integrity</h3>
                <p class="text-gray-600">
                    Maintaining the highest standards of honesty, transparency, and ethical conduct in all our dealings.
                </p>
            </div>
            
            <!-- Excellence -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-200 transition-colors duration-300">
                    <i class="fas fa-star text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Excellence</h3>
                <p class="text-gray-600">
                    Striving for excellence in every service we provide, ensuring quality and efficiency in our ministry.
                </p>
            </div>
            
            <!-- Humility -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors duration-300">
                    <i class="fas fa-praying-hands text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Humility</h3>
                <p class="text-gray-600">
                    Serving with a humble heart, recognizing that all power and glory belong to God alone.
                </p>
            </div>
            
            <!-- Unity -->
            <div class="text-center group">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors duration-300">
                    <i class="fas fa-users text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Unity</h3>
                <p class="text-gray-600">
                    Fostering unity among believers and creating an inclusive environment for all who seek God.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Portal Features -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">What This Portal Offers</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Comprehensive digital services designed to connect you with divine intervention
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Laying of Hands -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hands-helping text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Laying of Hands Requests</h3>
                        <p class="text-gray-600">
                            Submit requests for personal prayer sessions with Daddy G.O. Whether you need healing, breakthrough, or spiritual guidance, experience the power of anointed hands.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Prayer Requests -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-praying-hands text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Prayer Requests</h3>
                        <p class="text-gray-600">
                            Submit your prayer needs and join our prayer community. Every request is carefully reviewed and presented before God with faith and expectation.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Event Updates -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-alt text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Event Notifications</h3>
                        <p class="text-gray-600">
                            Stay updated with upcoming events, special services, and programs through automated SMS and email notifications.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Online Giving -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-heart text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure Online Giving</h3>
                        <p class="text-gray-600">
                            Support the ministry through secure online donations. Your giving helps spread the gospel and support various church programs worldwide.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Simple steps to connect with divine intervention through our portal
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                    1
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Submit Your Request</h3>
                <p class="text-gray-600">
                    Fill out the online form with your personal details and specific prayer needs. Be as detailed as possible for better ministry.
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                    2
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Review & Approval</h3>
                <p class="text-gray-600">
                    Our office team reviews your request prayerfully and processes it according to availability and divine leading.
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                    3
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Receive Notification</h3>
                <p class="text-gray-600">
                    Get notified via SMS or email about your request status and any special instructions for your prayer session.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonies -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Testimonies of God's Faithfulness</h2>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                Read how lives have been transformed through this ministry
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-quote-left text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-white">Mrs. Jennifer Adebayo</h4>
                        <p class="text-gray-400 text-sm">Lagos, Nigeria</p>
                    </div>
                </div>
                <p class="text-gray-300 italic leading-relaxed">
                    "My husband was declared dead for 30 minutes after a car accident. Through this portal, I submitted a desperate prayer request. Daddy G.O prayed, and God raised my husband back to life. Today, he's completely whole. This portal is a divine connection point!"
                </p>
            </div>
            
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-quote-left text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-white">Mr. David Okafor</h4>
                        <p class="text-gray-400 text-sm">Abuja, Nigeria</p>
                    </div>
                </div>
                <p class="text-gray-300 italic leading-relaxed">
                    "I was struggling with a business that was failing for 7 years. Through this platform, I requested for laying of hands. Within 3 months of Daddy G.O's prayer, contracts worth over ₦200 million came my way. God is faithful!"
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Experience God's Power?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto opacity-90">
            Don't let another day pass without experiencing the miraculous. Submit your request today and step into your breakthrough.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('laying-hands.form') }}" 
               class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center">
                <i class="fas fa-hands-helping mr-2"></i>
                Request Laying of Hands
            </a>
            <a href="{{ route('contact') }}" 
               class="bg-transparent border-2 border-white hover:bg-white hover:text-gray-900 text-white font-bold py-4 px-8 rounded-full transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>

@endsection