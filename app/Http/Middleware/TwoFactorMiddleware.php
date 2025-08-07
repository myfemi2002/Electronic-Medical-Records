<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Check if user is admin and has 2FA enabled but not verified in this session
        if ($user && $user->role === 'admin' && $user->two_factor_enabled && session('2fa_required')) {
            return redirect()->route('two-factor.verify');
        }
        
        return $next($request);
    }
}
