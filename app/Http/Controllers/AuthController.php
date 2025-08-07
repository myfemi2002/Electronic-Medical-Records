<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function __construct()
    {
        Log::info('AuthController constructed');
    }

    public function showRegister()
    {
        Log::info('Attempting to show register page');
        
        try {
            Log::info('Checking if view exists');
            if (!view()->exists('backend.auth.register')) {
                Log::error('View does not exist: backend.auth.register');
                throw new \Exception('Registration view not found');
            }

            Log::info('View exists, attempting to render');
            return view('backend.auth.register');
            
        } catch (\Exception $e) {
            Log::error('Error in showRegister: ' . $e->getMessage());
            
            return response()->view('errors.404', [], 404);
        }
    }

    public function register(Request $request)
    {
        Log::info('Registration attempt', ['email' => $request->email]);
        
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'terms' => 'required|accepted',
            ]);

            $agent = new Agent();

            Log::info('User agent info', [
                'browser' => $agent->browser(),
                'device' => $agent->device(),
                'platform' => $agent->platform()
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone' => $validatedData['phone'] ?? null,
                'registration_ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'last_login_browser' => $agent->browser(),
                'last_login_device' => $agent->device(),
                'last_login_os' => $agent->platform(),
                'last_login_ip' => $request->ip(),
                'last_login_at' => now(),
                'is_logged_in' => true,
                // 'newsletter' => $request->has('newsletter') ? 1 : 0,
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            // Only assign role if the method exists and try to handle gracefully if it fails
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('user');
                    Log::info('Role assigned successfully');
                } catch (\Exception $e) {
                    // Log the error but continue with the registration process
                    Log::warning('Role assignment failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('login')
                ->with('message', 'Registration successful! Please login.')
                ->with('alert-type', 'success');

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            
            // More user-friendly error message
            $errorMessage = 'Registration failed. ';
            
            // Database connection issues
            if (str_contains($e->getMessage(), 'No database selected') || 
                str_contains($e->getMessage(), 'Unknown database')) {
                $errorMessage .= 'Database connection issue. Please try again later.';
            } 
            // Validation issues
            else if ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage .= 'Please check your information and try again.';
            } 
            // Default message
            else {
                $errorMessage .= 'Please try again or contact support if the issue persists.';
            }
            
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('message', $errorMessage)
                ->with('alert-type', 'error');
        }
    }
}