<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LoginAttempt;
use Illuminate\Http\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        
        // Check if IP should be blocked
        if (LoginAttempt::shouldBlockIp($ip)) {
            return response()->view('errors.blocked', [
                'message' => 'Your IP address has been temporarily blocked due to suspicious activity.'
            ], 403);
        }
        
        // Check for login route specifically
        if ($request->routeIs('login') && $request->isMethod('POST')) {
            $email = $request->input('email');
            
            // Check if account should be locked
            if ($email && LoginAttempt::shouldLockAccount($email)) {
                return back()->withErrors([
                    'email' => 'This account has been temporarily locked due to multiple failed login attempts.'
                ]);
            }
        }
        
        return $next($request);
    }
}