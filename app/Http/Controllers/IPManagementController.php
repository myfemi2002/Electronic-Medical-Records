<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedIP;
use App\Models\IPWhitelist;
use App\Models\IPAccessLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class IPManagementController extends Controller
{
    public function index()
    {
        $blockedIPs = BlockedIP::orderBy('created_at', 'desc')->paginate(10, ['*'], 'blocked_page');
        $whitelistedIPs = IPWhitelist::orderBy('created_at', 'desc')->paginate(10, ['*'], 'whitelist_page');
        
        // Get recent access attempts
        $recentAccess = IPAccessLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get statistics
        $stats = [
            'total_blocked' => BlockedIP::count(),
            'total_whitelisted' => IPWhitelist::count(),
            'blocked_today' => BlockedIP::whereDate('created_at', today())->count(),
            'access_attempts_today' => IPAccessLog::whereDate('created_at', today())->count(),
            'unique_ips_today' => IPAccessLog::whereDate('created_at', today())->distinct('ip_address')->count(),
            'failed_attempts_today' => IPAccessLog::whereDate('created_at', today())->where('is_blocked', true)->count()
        ];

        return view('backend.security.ip-management', compact('blockedIPs', 'whitelistedIPs', 'recentAccess', 'stats'));
    }

    public function blockIP(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'required|string|max:255',
            'duration' => 'nullable|integer|min:1', // Duration in hours, null for permanent
        ]);

        // Check if IP is already blocked
        $existingBlock = BlockedIP::where('ip_address', $request->ip_address)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($existingBlock) {
            return redirect()->back()
                ->with('message', 'IP address is already blocked')
                ->with('alert-type', 'warning');
        }

        // Remove from whitelist if exists
        IPWhitelist::where('ip_address', $request->ip_address)->delete();

        // Calculate expiration time
        $expiresAt = $request->duration ? now()->addHours($request->duration) : null;

        BlockedIP::create([
            'ip_address' => $request->ip_address,
            'reason' => $request->reason,
            'blocked_by' => auth()->id(),
            'expires_at' => $expiresAt,
        ]);

        // Clear cache
        Cache::forget('blocked_ips');

        return redirect()->back()
            ->with('message', 'IP address blocked successfully')
            ->with('alert-type', 'success');
    }

    public function unblockIP($id)
    {
        $blockedIP = BlockedIP::findOrFail($id);
        $blockedIP->delete();

        // Clear cache
        Cache::forget('blocked_ips');

        return redirect()->back()
            ->with('message', 'IP address unblocked successfully')
            ->with('alert-type', 'success');
    }

    public function whitelistIP(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'description' => 'required|string|max:255',
        ]);

        // Check if IP is already whitelisted
        if (IPWhitelist::where('ip_address', $request->ip_address)->exists()) {
            return redirect()->back()
                ->with('message', 'IP address is already whitelisted')
                ->with('alert-type', 'warning');
        }

        // Remove from blocked list if exists
        BlockedIP::where('ip_address', $request->ip_address)->delete();

        IPWhitelist::create([
            'ip_address' => $request->ip_address,
            'description' => $request->description,
            'added_by' => auth()->id(),
        ]);

        // Clear cache
        Cache::forget('blocked_ips');
        Cache::forget('whitelisted_ips');

        return redirect()->back()
            ->with('message', 'IP address whitelisted successfully')
            ->with('alert-type', 'success');
    }

    public function removeFromWhitelist($id)
    {
        $whitelistedIP = IPWhitelist::findOrFail($id);
        $whitelistedIP->delete();

        // Clear cache
        Cache::forget('whitelisted_ips');

        return redirect()->back()
            ->with('message', 'IP address removed from whitelist')
            ->with('alert-type', 'success');
    }

    public function bulkBlock(Request $request)
    {
        $request->validate([
            'ip_list' => 'required|string',
            'reason' => 'required|string|max:255',
            'duration' => 'nullable|integer|min:1',
        ]);

        $ipAddresses = array_filter(array_map('trim', explode("\n", $request->ip_list)));
        $blockedCount = 0;
        $errors = [];

        foreach ($ipAddresses as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $errors[] = "Invalid IP: {$ip}";
                continue;
            }

            // Check if already blocked
            $exists = BlockedIP::where('ip_address', $ip)
                ->where(function($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                })
                ->exists();

            if (!$exists) {
                $expiresAt = $request->duration ? now()->addHours($request->duration) : null;
                
                BlockedIP::create([
                    'ip_address' => $ip,
                    'reason' => $request->reason,
                    'blocked_by' => auth()->id(),
                    'expires_at' => $expiresAt,
                ]);

                // Remove from whitelist if exists
                IPWhitelist::where('ip_address', $ip)->delete();
                
                $blockedCount++;
            }
        }

        // Clear cache
        Cache::forget('blocked_ips');
        Cache::forget('whitelisted_ips');

        $message = "Blocked {$blockedCount} IP address(es)";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', $errors);
        }

        return redirect()->back()
            ->with('message', $message)
            ->with('alert-type', $blockedCount > 0 ? 'success' : 'warning');
    }

    public function clearExpiredBlocks()
    {
        $deletedCount = BlockedIP::where('expires_at', '<=', now())->delete();

        // Clear cache
        Cache::forget('blocked_ips');

        return redirect()->back()
            ->with('message', "Cleared {$deletedCount} expired IP block(s)")
            ->with('alert-type', 'success');
    }

    public function getAccessLogs(Request $request)
    {
        $query = IPAccessLog::with('user');

        // Apply filters
        if ($request->ip_filter) {
            $query->where('ip_address', $request->ip_filter);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->status_filter === 'blocked') {
            $query->where('is_blocked', true);
        } elseif ($request->status_filter === 'allowed') {
            $query->where('is_blocked', false);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('backend.security.access-logs', compact('logs'));
    }

    public function exportAccessLogs(Request $request)
    {
        $query = IPAccessLog::with('user');

        // Apply same filters as getAccessLogs
        if ($request->ip_filter) {
            $query->where('ip_address', $request->ip_filter);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->status_filter === 'blocked') {
            $query->where('is_blocked', true);
        } elseif ($request->status_filter === 'allowed') {
            $query->where('is_blocked', false);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'access_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date/Time',
                'IP Address',
                'User',
                'User Agent',
                'Request URL',
                'HTTP Method',
                'Status',
                'Location'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->ip_address,
                    $log->user ? $log->user->name : 'Guest',
                    $log->user_agent,
                    $log->request_url,
                    $log->http_method,
                    $log->is_blocked ? 'Blocked' : 'Allowed',
                    $log->location ?? 'Unknown'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public static function isIPBlocked($ipAddress)
    {
        // Check cache first
        $blockedIPs = Cache::remember('blocked_ips', 300, function () {
            return BlockedIP::where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })->pluck('ip_address')->toArray();
        });

        return in_array($ipAddress, $blockedIPs);
    }

    public static function isIPWhitelisted($ipAddress)
    {
        // Check cache first
        $whitelistedIPs = Cache::remember('whitelisted_ips', 300, function () {
            return IPWhitelist::pluck('ip_address')->toArray();
        });

        return in_array($ipAddress, $whitelistedIPs);
    }

    public static function logAccess($request, $isBlocked = false)
    {
        IPAccessLog::create([
            'ip_address' => $request->ip(),
            'user_id' => auth()->id(),
            'user_agent' => $request->userAgent(),
            'request_url' => $request->fullUrl(),
            'http_method' => $request->method(),
            'is_blocked' => $isBlocked,
            'location' => self::getLocationFromIP($request->ip()),
        ]);
    }

    private static function getLocationFromIP($ipAddress)
    {
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return 'Local';
        }
        
        // Integrate with location service here
        return 'Unknown';
    }
}
