<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SessionTracking
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Update session information after response
        $this->updateSessionInfo($request);
        
        return $response;
    }
    
    private function updateSessionInfo(Request $request)
    {
        try {
            $sessionId = session()->getId();
            $userId = auth()->id();
            
            if ($sessionId) {
                DB::table('sessions')
                    ->where('id', $sessionId)
                    ->update([
                        'user_id' => $userId,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'last_activity' => now()->timestamp,
                    ]);
            }
        } catch (\Exception $e) {
            // Log error but don't break the application
            Log::error('Session tracking error: ' . $e->getMessage());
        }
    }
}
