<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\IPManagementController;

class CheckIPAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ipAddress = $request->ip();
        
        // Skip check for local development
        if (app()->environment('local') && in_array($ipAddress, ['127.0.0.1', '::1'])) {
            return $next($request);
        }
        
        // Check if IP is whitelisted (whitelisted IPs bypass blocking)
        if (IPManagementController::isIPWhitelisted($ipAddress)) {
            // Log access for whitelisted IPs
            IPManagementController::logAccess($request, false);
            return $next($request);
        }
        
        // Check if IP is blocked
        if (IPManagementController::isIPBlocked($ipAddress)) {
            // Log blocked access attempt
            IPManagementController::logAccess($request, true);
            
            // Return 403 Forbidden with custom message
            return response()->view('errors.ip-blocked', [
                'ip_address' => $ipAddress,
                'message' => 'Your IP address has been blocked due to security reasons.'
            ], 403);
        }
        
        // Log successful access
        IPManagementController::logAccess($request, false);
        
        return $next($request);
    }
}
