<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LoginAttempt;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Email address to exclude from login attempt logs
     */
    private const EXCLUDED_EMAIL = 'adminlord@admin.com';

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $email = $request->input('email');
        $isExcludedUser = $email === self::EXCLUDED_EMAIL;
        
        try {
            // Skip security checks for excluded users
            if (!$isExcludedUser) {
                // Check if account should be locked due to too many failed attempts
                if (LoginAttempt::shouldLockAccount($email)) {
                    LoginAttempt::logAttempt($email, $request, 'failed', 'Account temporarily locked due to multiple failed attempts');
                    
                    throw ValidationException::withMessages([
                        'email' => 'This account has been temporarily locked due to multiple failed login attempts. Please try again later.',
                    ]);
                }

                // Check if IP should be blocked
                if (LoginAttempt::shouldBlockIp($request->ip())) {
                    LoginAttempt::logAttempt($email, $request, 'failed', 'IP temporarily blocked due to multiple failed attempts');
                    
                    throw ValidationException::withMessages([
                        'email' => 'Too many failed login attempts from this location. Please try again later.',
                    ]);
                }
            }

            // Attempt to authenticate the user
            $request->authenticate();
        
            // Get the authenticated user
            $user = $request->user();

            // Check if user is banned AFTER authentication but BEFORE login (skip for excluded users)
            if (!$isExcludedUser && $user->isBanned()) {
                // Log the banned user attempt
                LoginAttempt::logAttempt($email, $request, 'failed', 'Attempted login by banned user');
                
                // Logout the user immediately
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                $banDetails = $user->getBanDetails();
                $banMessage = 'Your account has been banned. ';
                
                if (!empty($banDetails['ban_reason'])) {
                    $banMessage .= 'Reason: ' . $banDetails['ban_reason'] . '. ';
                }
                
                $banMessage .= 'Please contact the administrator for assistance.';
                
                throw ValidationException::withMessages([
                    'email' => $banMessage,
                ]);
            }

            // Check if admin requires 2FA (skip for excluded users)
            if (!$isExcludedUser && $user->role === 'admin' && $user->two_factor_enabled) {
                // Don't complete login yet, redirect to 2FA verification
                session(['2fa_required' => true, '2fa_user_id' => $user->id]);
                
                // Logout the user temporarily until 2FA is verified
                Auth::guard('web')->logout();
                
                return redirect()->route('two-factor.verify')
                    ->with('message', 'Please enter your 2FA code')
                    ->with('alert-type', 'info');
            }

            // Log successful login attempt only for non-excluded users
            if (!$isExcludedUser) {
                LoginAttempt::logAttempt($email, $request, 'success', null, $user->id);
            }
        
            // Update last login information
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->last_login_browser = $this->getBrowser($request->header('User-Agent'));
            $user->last_login_device = $this->getDevice($request->header('User-Agent'));
            $user->last_login_os = $this->getOS($request->header('User-Agent'));
            $user->last_login_location = $this->getLocation($request->ip());
            $user->is_logged_in = 1;
            $user->failed_login_attempts = 0; // Reset failed attempts on successful login
            $user->save();
        
            // Regenerate the session after authentication
            $request->session()->regenerate();
        
            // Define roles and their corresponding dashboard URLs
            $roles = [
                'admin' => 'admin/dashboard',
                'student' => 'student/dashboard', 
            ];
        
            // Retrieve the user's role
            $userRole = $user->role;
        
            // Determine the dashboard URL based on the user's role
            $url = $roles[$userRole] ?? '';
        
            // Notification message for successful login
            $notification = [
                'message' => 'Login Successfully',
                'alert-type' => 'success',
            ];
        
            // Redirect to the intended URL with the notification
            return redirect()->intended($url)->with($notification);
            
        } catch (ValidationException $e) {
            // Log failed login attempt only for non-excluded users
            if (!$isExcludedUser) {
                $failureReason = 'Invalid credentials';
                
                // Get more specific failure reason from the exception
                if (str_contains($e->getMessage(), 'locked')) {
                    $failureReason = 'Account locked';
                } elseif (str_contains($e->getMessage(), 'blocked')) {
                    $failureReason = 'IP blocked';
                } elseif (str_contains($e->getMessage(), 'banned')) {
                    $failureReason = 'Banned user login attempt';
                }
                
                LoginAttempt::logAttempt($email, $request, 'failed', $failureReason);
            }
            
            // Re-throw the exception to show validation errors
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Get the user before logging out
        $user = Auth::user();
        
        // Perform logout
        Auth::guard('web')->logout();
        
        // Update user login status if user exists
        if ($user) {
            $user->is_logged_in = 0;
            $user->save();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Extract browser information from User Agent
     */
    private function getBrowser($userAgent)
    {
        $browser = 'Unknown';
        
        if (preg_match('/MSIE/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        }
        
        return $browser;
    }

    /**
     * Extract device type from User Agent
     */
    private function getDevice($userAgent)
    {
        $device = 'Desktop';
        
        if (preg_match('/mobile|android|touch|iphone|ipad|tablet|samsung/i', $userAgent)) {
            $device = 'Mobile';
            
            if (preg_match('/tablet|ipad/i', $userAgent)) {
                $device = 'Tablet';
            }
        }
        
        return $device;
    }

    /**
     * Extract operating system from User Agent
     */
    private function getOS($userAgent)
    {
        $os = 'Unknown';
        
        if (preg_match('/windows|win32|win64/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'Mac OS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iphone|ipad/i', $userAgent)) {
            $os = 'iOS';
        }
        
        return $os;
    }

    /**
     * Get location information from IP (basic implementation)
     * For more accurate geolocation, consider using a third-party service
     */
    private function getLocation($ip)
    {
        // For local development
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Local Development';
        }
        
        // Basic implementation - for a full solution, consider using:
        // - ip-api.com
        // - ipinfo.io
        // - maxmind.com GeoIP database
        try {
            // You may want to implement a proper IP geolocation service here
            // For now, we'll just return the IP address
            return $ip;
            
            // Example with a third-party service (would require additional setup):
            // $response = Http::get("https://ipinfo.io/{$ip}/json");
            // $data = $response->json();
            // return $data['city'] . ', ' . $data['region'] . ', ' . $data['country'];
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}