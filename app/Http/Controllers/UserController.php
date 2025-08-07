<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\WalletAdjustment;
use App\Models\DailyContribution;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
     /**
     * Display user dashboard with dynamic data
     */
    public function userDashboard()
    {
        $user = Auth::user();
        return view('userend.index');
    }

    /**


     * Show edit profile form
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('userend.profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $this->handlePhotoUpload($request->file('photo'), $user);
                if ($photo) {
                    $updateData['photo'] = $photo;
                }
            }

            $user->update($updateData);

            return redirect()->route('user.profile')
                ->with('message', 'Profile updated successfully!')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Failed to update profile: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('tab', 'security');
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect'])
                ->with('tab', 'security');
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password),
                'last_password_change' => now()->toDateTimeString()
            ]);

            return redirect()->back()
                ->with('message', 'Password changed successfully!')
                ->with('alert-type', 'success')
                ->with('tab', 'security');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Failed to change password')
                ->with('alert-type', 'error')
                ->with('tab', 'security');
        }
    }

    /**
     * Show user settings page
     */
    public function showSettings()
    {
        $user = Auth::user();
        return view('userend.profile.settings', compact('user'));
    }

    /**
     * Update user settings/preferences
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $settings = [
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'push_notifications' => $request->has('push_notifications'),
            'newsletter_subscription' => $request->has('newsletter_subscription'),
            'dark_mode' => $request->has('dark_mode'),
            'language' => $request->language ?? 'en',
            'timezone' => $request->timezone ?? 'UTC',
        ];
        
        $user->setProfileField('settings', $settings);
        
        return redirect()->back()
            ->with('message', 'Settings updated successfully!')
            ->with('alert-type', 'success');
    }

    /**
     * Handle photo upload
     */
    private function handlePhotoUpload($file, $user)
    {
        try {
            $uploadPath = 'upload/user_images';
            
            // Create directory if it doesn't exist
            if (!File::exists(public_path($uploadPath))) {
                File::makeDirectory(public_path($uploadPath), 0777, true);
            }

            // Delete old photo if exists
            if ($user->photo && File::exists(public_path($user->photo))) {
                File::delete(public_path($user->photo));
            }

            // Generate filename
            $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $fullPath = $uploadPath . '/' . $filename;

            // Resize and save image
            $imageManager = new ImageManager(new Driver());
            $image = $imageManager->read($file->getRealPath());
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save(public_path($fullPath), 80);

            return $fullPath;

        } catch (\Exception $e) {
            Log::error('Photo upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user profile data for API/AJAX
     */
    public function getProfileData()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'photo' => $user->photo,
                'role' => $user->role,
                'status' => $user->status,
                'credit_rating' => $user->credit_rating,
                'loan_interest_rate' => $user->loan_interest_rate,
                'profile' => $user->profile,
                'created_at' => $user->created_at,
                'last_login_at' => $user->last_login_at,
            ]
        ]);
    }


    /**
     * Log out the user
     */
    public function userDestroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        );
        return redirect('/login')->with($notification);
    }
    
}