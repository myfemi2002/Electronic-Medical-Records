<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Allow access to registration routes even when authenticated
                if ($request->is('auths/register') || $request->is('auths/register/*')) {
                    return $next($request);
                }

                // Get user role
                $userRole = Auth::user()->role;

                // Redirect based on user role (matching your actual routes)
                switch ($userRole) {
                    case 'admin':
                        return redirect()->route('admin.dashboard')
                            ->with('message', 'Already logged in as Admin')
                            ->with('alert-type', 'info');

                    case 'student': // Changed from 'user' to 'student' to match your routes
                        return redirect()->route('student.dashboard')
                            ->with('message', 'Already logged in as Student')
                            ->with('alert-type', 'info');

                    default:
                        // For unknown roles, redirect to home
                        return redirect()->route('home')
                            ->with('message', 'Already logged in')
                            ->with('alert-type', 'info');
                }
            }
        }

        return $next($request);
    }
}