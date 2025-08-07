<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Public routes that should bypass role checks
     */
    protected $publicRoutes = [
        '/',
        'properties/*',
        'auths/register',
        'auths/register/*', 
        'login',
        'logout',
        'password/reset',
        'password/email',
        'password/confirm',
        'forgot-password',
        'reset-password',
        'register',
        'email/verify',
        'email/verify/*'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Allow public routes to bypass role check
        foreach ($this->publicRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to access this page')
                ->with('alert-type', 'error');
        }

        // Get authenticated user
        $user = Auth::user();

        // Check if the user's role matches the expected role
        if ($user->role !== $role) {
            // Redirect based on user's actual role (matching your routes)
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                        ->with('message', 'Access denied. Redirected to admin dashboard')
                        ->with('alert-type', 'error');

                case 'student': // Changed from 'user' to 'student' to match your routes
                    return redirect()->route('student.dashboard')
                        ->with('message', 'Access denied. Redirected to student dashboard')
                        ->with('alert-type', 'error');

                default:
                    // For any other role, redirect to home or login
                    Auth::logout();
                    return redirect()->route('login')
                        ->with('message', 'Access denied. Invalid user role')
                        ->with('alert-type', 'error');
            }
        }

        return $next($request);
    }
}