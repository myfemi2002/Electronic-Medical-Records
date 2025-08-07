<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is banned
            if ($user->isBanned()) {
                // Get ban details for the message
                $banDetails = $user->getBanDetails();
                $banMessage = 'Your account has been banned. ';
                
                if (!empty($banDetails['ban_reason'])) {
                    $banMessage .= 'Reason: ' . $banDetails['ban_reason'] . '. ';
                }
                
                $banMessage .= 'Please contact the administrator for assistance.';
                
                // Force logout
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Update user login status
                $user->update(['is_logged_in' => 0]);
                
                // For AJAX requests, return JSON response
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $banMessage,
                        'banned' => true
                    ], 403);
                }
                
                // For regular requests, redirect to login with error
                return redirect()->route('login')
                    ->with('message', $banMessage)
                    ->with('alert-type', 'error');
            }
        }

        return $next($request);
    }
}