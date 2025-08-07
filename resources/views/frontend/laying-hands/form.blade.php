{{-- File: resources/views/frontend/laying-hands/form.blade.php --}}
@extends('frontend.master')
@section('title', 'Laying of Hands Request')
@section('content')

<!-- Hero Section -->
<section class="relative py-16 bg-gradient-to-r from-blue-600 to-purple-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Laying of Hands Request</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto opacity-90">
                Experience the miraculous power of God through personal prayer with Daddy G.O
            </p>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="bg-blue-600 text-white p-6">
                <h2 class="text-2xl font-bold mb-2">Submit Your Request</h2>
                <p class="opacity-90">Please fill out all required fields to submit your laying of hands request</p>
            </div>
            
            <!-- Alert Messages -->
            <div class="p-6">
                @if(session('message'))
                    <div class="mb-6 p-4 rounded-lg {{ session('alert-type') == 'success' ? 'bg-green-50 border border-green-200 text-green-800' : (session('alert-type') == 'warning' ? 'bg-yellow-50 border border-yellow-200 text-yellow-800' : 'bg-red-50 border border-red-200 text-red-800') }}">
                        <div class="flex items-center">
                            <i class="fas {{ session('alert-type') == 'success' ? 'fa-check-circle' : (session('alert-type') == 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle') }} mr-2"></i>
                            {{ session('message') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-1 mr-2"></i>
                            <div>
                                <h3 class="text-red-800 font-semibold mb-2">Please correct the following errors:</h3>
                                <ul class="list-disc list-inside text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('laying-hands.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-2"></i>
                            Basic Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="full_name" 
                                       name="full_name" 
                                       value="{{ old('full_name') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('full_name') border-red-500 @enderror" 
                                       placeholder="Enter your full name"
                                       required>
                                @error('full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror" 
                                       placeholder="Enter your email address"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('phone') border-red-500 @enderror" 
                                       placeholder="Enter your phone number"
                                       required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="state" 
                                       name="state" 
                                       value="{{ old('state') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('state') border-red-500 @enderror" 
                                       placeholder="Enter your state"
                                       required>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Church Membership Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-church text-blue-600 mr-2"></i>
                            Church Membership
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Are you a member of The Redeemed Christian Church of God? <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col space-y-3">
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="is_rccg_member" 
                                               id="is_member_yes" 
                                               value="1" 
                                               {{ old('is_rccg_member') == '1' ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                                               required>
                                        <span class="ml-3 text-gray-700">Yes, I am an RCCG member</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="is_rccg_member" 
                                               id="is_member_no" 
                                               value="0" 
                                               {{ old('is_rccg_member') == '0' ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" 
                                               required>
                                        <span class="ml-3 text-gray-700">No, I am not an RCCG member</span>
                                    </label>
                                </div>
                                @error('is_rccg_member')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- RCCG Member Details (Hidden by default) -->
                    <div id="rccg_member_fields" class="border-b border-gray-200 pb-6" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            RCCG Member Details
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="parish" class="block text-sm font-medium text-gray-700 mb-2">
                                    Parish <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="parish" 
                                       name="parish" 
                                       value="{{ old('parish') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('parish') border-red-500 @enderror" 
                                       placeholder="Enter your parish">
                                @error('parish')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="province" 
                                       name="province" 
                                       value="{{ old('province') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('province') border-red-500 @enderror" 
                                       placeholder="Enter your province">
                                @error('province')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                                    Region <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="region" 
                                       name="region" 
                                       value="{{ old('region') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('region') border-red-500 @enderror" 
                                       placeholder="Enter your region">
                                @error('region')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select id="gender" 
                                        name="gender" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('gender') border-red-500 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('date_of_birth') border-red-500 @enderror">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Address <span class="text-red-500">*</span>
                            </label>
                            <textarea id="contact_address" 
                                      name="contact_address" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('contact_address') border-red-500 @enderror" 
                                      placeholder="Enter your full contact address">{{ old('contact_address') }}</textarea>
                            @error('contact_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="prayer_category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prayer Request Category <span class="text-red-500">*</span>
                                </label>
                                <select id="prayer_category" 
                                        name="prayer_category" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('prayer_category') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    <option value="Healing" {{ old('prayer_category') == 'Healing' ? 'selected' : '' }}>Healing</option>
                                    <option value="Financial Breakthrough" {{ old('prayer_category') == 'Financial Breakthrough' ? 'selected' : '' }}>Financial Breakthrough</option>
                                    <option value="Family" {{ old('prayer_category') == 'Family' ? 'selected' : '' }}>Family</option>
                                    <option value="Deliverance" {{ old('prayer_category') == 'Deliverance' ? 'selected' : '' }}>Deliverance</option>
                                    <option value="Marital" {{ old('prayer_category') == 'Marital' ? 'selected' : '' }}>Marital</option>
                                    <option value="Career" {{ old('prayer_category') == 'Career' ? 'selected' : '' }}>Career</option>
                                    <option value="Educational" {{ old('prayer_category') == 'Educational' ? 'selected' : '' }}>Educational</option>
                                    <option value="Spiritual Growth" {{ old('prayer_category') == 'Spiritual Growth' ? 'selected' : '' }}>Spiritual Growth</option>
                                    <option value="Other" {{ old('prayer_category') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('prayer_category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="preferred_communication" class="block text-sm font-medium text-gray-700 mb-2">
                                    Preferred Communication <span class="text-red-500">*</span>
                                </label>
                                <select id="preferred_communication" 
                                        name="preferred_communication" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('preferred_communication') border-red-500 @enderror">
                                    <option value="">Select Preference</option>
                                    <option value="email" {{ old('preferred_communication') == 'email' ? 'selected' : '' }}>Email Only</option>
                                    <option value="sms" {{ old('preferred_communication') == 'sms' ? 'selected' : '' }}>SMS Only</option>
                                    <option value="both" {{ old('preferred_communication') == 'both' ? 'selected' : '' }}>Both Email & SMS</option>
                                </select>
                                @error('preferred_communication')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="service_attended" class="block text-sm font-medium text-gray-700 mb-2">
                                    Service Attended (if any)
                                </label>
                                <input type="text" 
                                       id="service_attended" 
                                       name="service_attended" 
                                       value="{{ old('service_attended') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('service_attended') border-red-500 @enderror" 
                                       placeholder="e.g., Sunday Service, Holy Ghost Service">
                                @error('service_attended')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="how_heard_about_program" class="block text-sm font-medium text-gray-700 mb-2">
                                    How did you hear about this program? <span class="text-red-500">*</span>
                                </label>
                                <select id="how_heard_about_program" 
                                        name="how_heard_about_program" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('how_heard_about_program') border-red-500 @enderror">
                                    <option value="">Select Option</option>
                                    <option value="Church" {{ old('how_heard_about_program') == 'Church' ? 'selected' : '' }}>Church</option>
                                    <option value="Social Media" {{ old('how_heard_about_program') == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                                    <option value="Friend" {{ old('how_heard_about_program') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                    <option value="Website" {{ old('how_heard_about_program') == 'Website' ? 'selected' : '' }}>Website</option>
                                    <option value="Other" {{ old('how_heard_about_program') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('how_heard_about_program')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes / Special Instructions
                            </label>
                            <textarea id="additional_notes" 
                                      name="additional_notes" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('additional_notes') border-red-500 @enderror" 
                                      placeholder="Any additional information you'd like to share">{{ old('additional_notes') }}</textarea>
                            @error('additional_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Request Details Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-hands-helping text-blue-600 mr-2"></i>
                            Request Details
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
    <label for="laying_hands_request" class="block text-sm font-medium text-gray-700 mb-2">
        Why do you want Daddy G.O to lay hands on you? <span class="text-red-500">*</span>
    </label>
    
<textarea 
    id="laying_hands_request" 
    name="laying_hands_request" 
    rows="5"
    maxlength="1000"
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('laying_hands_request') border-red-500 @enderror"
    placeholder="Please explain in detail your reason for requesting the laying of hands (max 1000 characters)"
    required
    oninput="updateCharCount(this)">
</textarea>

<small class="text-gray-500 mt-1 block text-right">
    <span id="char-count">0/1000</span>
</small>
</div>

                            
                            <!-- <div>
                                <label for="reason_to_see_go" class="block text-sm font-medium text-gray-700 mb-2">
                                    Reason to see G.O <span class="text-red-500">*</span>
                                </label>
                                <textarea id="reason_to_see_go" 
                                          name="reason_to_see_go" 
                                          rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('reason_to_see_go') border-red-500 @enderror" 
                                          placeholder="Please provide specific reasons why you need to see Daddy G.O..."
                                          required>{{ old('reason_to_see_go') }}</textarea>
                                @error('reason_to_see_go')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div> -->
                        </div>
                    </div>

                    <!-- Important Notice -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                            <div class="text-yellow-800">
                                <h4 class="font-semibold mb-2">Important Notice:</h4>
                                <ul class="text-sm space-y-1 list-disc list-inside">
                                    <li>If you have a pending request, you cannot submit another until it's processed</li>
                                    <li>All requests are reviewed prayerfully by our office team</li>
                                    <li>You will be notified via your preferred communication method</li>
                                    <li>Please ensure all information provided is accurate</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-center">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center text-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Information Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- What to Expect -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">What to Expect</h3>
                <p class="text-gray-600">
                    Your request will be reviewed within 24-48 hours. You'll receive notification about approval status and next steps.
                </p>
            </div>
            
            <!-- Prayer Process -->
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-praying-hands text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Prayer Process</h3>
                <p class="text-gray-600">
                    Approved requests are scheduled based on availability. Special prayer sessions are conducted with faith and expectation.
                </p>
            </div>
            
            <!-- Follow-up -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Follow-up</h3>
                <p class="text-gray-600">
                    After prayer sessions, participants are encouraged to share testimonies and maintain their spiritual connection.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Show/Hide RCCG Member Fields
    document.addEventListener('DOMContentLoaded', function() {
        const rccgYes = document.getElementById('is_member_yes');
        const rccgNo = document.getElementById('is_member_no');
        const rccgFields = document.getElementById('rccg_member_fields');
        
        function toggleRccgFields() {
            if (rccgYes.checked) {
                rccgFields.style.display = 'block';
                // Make RCCG fields required
                rccgFields.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
                    field.setAttribute('required', 'required');
                });
            } else {
                rccgFields.style.display = 'none';
                // Remove required from RCCG fields
                rccgFields.querySelectorAll('input, select, textarea').forEach(field => {
                    field.removeAttribute('required');
                });
            }
        }
        
        // Check on page load (for validation errors)
        if (rccgYes.checked) {
            toggleRccgFields();
        }
        
        // Event listeners
        rccgYes.addEventListener('change', toggleRccgFields);
        rccgNo.addEventListener('change', toggleRccgFields);
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('laying_hands_request');
        const oldValue = @json(old('laying_hands_request'));

        if (textarea && oldValue) {
            textarea.value = oldValue.trim();
            updateCharCount(textarea);
        }
    });

    function updateCharCount(textarea) {
        const counter = document.getElementById('char-count');
        const length = textarea.value.trim().length;
        counter.textContent = `${length}/1000`;
    }
</script>



@endpush