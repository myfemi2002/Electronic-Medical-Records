<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Carbon\Carbon;

class ActiveSessionController extends Controller
{
    public function index()
    {
        // Get all active sessions from the sessions table
        $activeSessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.id',
                'sessions.user_id',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
                'sessions.payload',
                'users.name',
                'users.email',
                'users.role'
            )
            ->where('sessions.last_activity', '>', now()->subMinutes(30)->timestamp) // Active in last 30 minutes
            ->orderBy('sessions.last_activity', 'desc')
            ->get();

        // Process sessions data
        $processedSessions = $activeSessions->map(function ($session) {
            return [
                'id' => $session->id,
                'user_id' => $session->user_id,
                'user_name' => $session->name ?? 'Guest',
                'user_email' => $session->email ?? 'N/A',
                'user_role' => $session->role ?? 'guest',
                'ip_address' => $session->ip_address,
                'user_agent' => $session->user_agent,
                'last_activity' => Carbon::createFromTimestamp($session->last_activity),
                'browser' => $this->parseBrowser($session->user_agent),
                'device' => $this->parseDevice($session->user_agent),
                'location' => $this->getLocationFromIP($session->ip_address),
                'is_current' => $session->id === Session::getId()
            ];
        });

        return view('backend.sessions.active-sessions', compact('processedSessions'));
    }

    public function destroy($sessionId)
    {
        // Prevent user from destroying their own session
        if ($sessionId === Session::getId()) {
            return redirect()->back()
                ->with('message', 'You cannot terminate your own session')
                ->with('alert-type', 'error');
        }

        try {
            DB::table('sessions')->where('id', $sessionId)->delete();
            
            return redirect()->back()
                ->with('message', 'Session terminated successfully')
                ->with('alert-type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Failed to terminate session')
                ->with('alert-type', 'error');
        }
    }

    public function destroyUserSessions($userId)
    {
        try {
            // Get current session to avoid destroying admin's session
            $currentSessionId = Session::getId();
            
            $deletedCount = DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId)
                ->delete();
            
            return redirect()->back()
                ->with('message', "Terminated {$deletedCount} session(s) for the user")
                ->with('alert-type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Failed to terminate user sessions')
                ->with('alert-type', 'error');
        }
    }

    public function destroyGuestSessions()
    {
        try {
            $deletedCount = DB::table('sessions')
                ->whereNull('user_id')
                ->delete();
            
            return redirect()->back()
                ->with('message', "Terminated {$deletedCount} guest session(s)")
                ->with('alert-type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message', 'Failed to terminate guest sessions')
                ->with('alert-type', 'error');
        }
    }

    public function getSessionDetails($sessionId)
    {
        $session = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select(
                'sessions.*',
                'users.name',
                'users.email',
                'users.role',
                'users.last_login_at'
            )
            ->where('sessions.id', $sessionId)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $sessionData = [
            'id' => $session->id,
            'user_id' => $session->user_id,
            'user_name' => $session->name ?? 'Guest',
            'user_email' => $session->email ?? 'N/A',
            'user_role' => $session->role ?? 'guest',
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'last_activity' => Carbon::createFromTimestamp($session->last_activity),
            'payload_size' => strlen($session->payload),
            'browser_details' => $this->parseBrowserDetails($session->user_agent),
            'location_details' => $this->getLocationDetails($session->ip_address),
            'session_duration' => $this->calculateSessionDuration($session->last_activity),
            'is_current' => $session->id === Session::getId()
        ];

        return response()->json(['session' => $sessionData]);
    }

    private function parseBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false) {
            return 'Opera';
        }
        return 'Unknown';
    }

    private function parseDevice($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false || strpos($userAgent, 'Android') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false || strpos($userAgent, 'iPad') !== false) {
            return 'Tablet';
        }
        return 'Desktop';
    }

    private function parseBrowserDetails($userAgent)
    {
        $browser = 'Unknown';
        $version = 'Unknown';

        // Chrome
        if (preg_match('/Chrome\/([0-9\.]+)/', $userAgent, $matches)) {
            $browser = 'Chrome';
            $version = $matches[1];
        }
        // Firefox
        elseif (preg_match('/Firefox\/([0-9\.]+)/', $userAgent, $matches)) {
            $browser = 'Firefox';
            $version = $matches[1];
        }
        // Safari
        elseif (preg_match('/Safari\/([0-9\.]+)/', $userAgent, $matches)) {
            $browser = 'Safari';
            $version = $matches[1];
        }

        return [
            'browser' => $browser,
            'version' => $version,
            'platform' => $this->parsePlatform($userAgent),
            'full_string' => $userAgent
        ];
    }

    private function parsePlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            return 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iOS') !== false) {
            return 'iOS';
        }
        return 'Unknown';
    }

    private function getLocationFromIP($ipAddress)
    {
        // Simple location detection - in production you might want to use a service like MaxMind
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'Local';
        }
        
        // You can integrate with services like:
        // - MaxMind GeoIP2
        // - ipapi.co
        // - ipgeolocation.io
        
        return 'Unknown Location';
    }

    private function getLocationDetails($ipAddress)
    {
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return [
                'country' => 'Local',
                'city' => 'Localhost',
                'region' => 'Local',
                'timezone' => 'Local'
            ];
        }

        // Placeholder for detailed location service integration
        return [
            'country' => 'Unknown',
            'city' => 'Unknown',
            'region' => 'Unknown',
            'timezone' => 'Unknown'
        ];
    }

    private function calculateSessionDuration($lastActivity)
    {
        $now = now();
        $lastActivityTime = Carbon::createFromTimestamp($lastActivity);
        
        return $now->diffForHumans($lastActivityTime, true);
    }
}
