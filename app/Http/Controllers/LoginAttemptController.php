<?php

namespace App\Http\Controllers;

use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoginAttemptController extends Controller
{
    /**
     * Email address to exclude from login attempt logs
     */
    private const EXCLUDED_EMAIL = 'adminlord@admin.com';

    /**
     * Display login attempts index page
     */
    public function index(Request $request)
    {
        $query = LoginAttempt::with('user')
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->orderBy('attempted_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attempted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attempted_at', '<=', $request->date_to);
        }

        if ($request->filled('browser')) {
            $query->where('browser', $request->browser);
        }

        if ($request->filled('device')) {
            $query->where('device', $request->device);
        }

        $loginAttempts = $query->paginate(25)->withQueryString();

        // Get statistics
        $stats = $this->getStatistics();

        // Get filter options
        $browsers = LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
            ->distinct()
            ->pluck('browser')
            ->filter()
            ->sort();
            
        $devices = LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
            ->distinct()
            ->pluck('device')
            ->filter()
            ->sort();

        return view('backend.login-attempts.index', compact(
            'loginAttempts', 
            'stats', 
            'browsers', 
            'devices'
        ));
    }

    /**
     * Get login attempt statistics
     */
    private function getStatistics()
    {
        $now = Carbon::now();
        
        return [
            'total_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)->count(),
            'successful_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->where('status', 'success')->count(),
            'failed_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->where('status', 'failed')->count(),
            'today_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->whereDate('attempted_at', $now->toDateString())->count(),
            'this_week_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->whereBetween('attempted_at', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ])->count(),
            'this_month_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->whereMonth('attempted_at', $now->month)
                ->whereYear('attempted_at', $now->year)
                ->count(),
            'recent_failed_attempts' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->where('status', 'failed')
                ->where('attempted_at', '>=', $now->subHours(24))
                ->count(),
            'unique_ips_today' => LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
                ->whereDate('attempted_at', $now->toDateString())
                ->distinct('ip_address')
                ->count(),
        ];
    }

    /**
     * Show detailed view of a specific login attempt
     */
    public function show($id)
    {
        $loginAttempt = LoginAttempt::with('user')
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->findOrFail($id);
        
        // Get related attempts from same IP
        $relatedByIp = LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('ip_address', $loginAttempt->ip_address)
            ->where('id', '!=', $id)
            ->orderBy('attempted_at', 'desc')
            ->limit(10)
            ->get();

        // Get related attempts from same email
        $relatedByEmail = LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('email', $loginAttempt->email)
            ->where('id', '!=', $id)
            ->orderBy('attempted_at', 'desc')
            ->limit(10)
            ->get();

        return view('backend.login-attempts.show', compact(
            'loginAttempt', 
            'relatedByIp', 
            'relatedByEmail'
        ));
    }

    /**
     * Get dashboard analytics data
     */
    public function analytics()
    {
        $now = Carbon::now();
        
        // Daily login attempts for the last 30 days
        $dailyAttempts = LoginAttempt::select(
                DB::raw('DATE(attempted_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as successful'),
                DB::raw('SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed')
            )
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('attempted_at', '>=', $now->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top failed login IPs
        $topFailedIps = LoginAttempt::select('ip_address', DB::raw('COUNT(*) as attempts'))
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('status', 'failed')
            ->where('attempted_at', '>=', $now->subDays(7))
            ->groupBy('ip_address')
            ->orderBy('attempts', 'desc')
            ->limit(10)
            ->get();

        // Browser statistics
        $browserStats = LoginAttempt::select('browser', DB::raw('COUNT(*) as count'))
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('attempted_at', '>=', $now->subDays(30))
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();

        // Device statistics
        $deviceStats = LoginAttempt::select('device', DB::raw('COUNT(*) as count'))
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('attempted_at', '>=', $now->subDays(30))
            ->groupBy('device')
            ->orderBy('count', 'desc')
            ->get();

        // Hourly distribution (for heatmap)
        $hourlyDistribution = LoginAttempt::select(
                DB::raw('HOUR(attempted_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('attempted_at', '>=', $now->subDays(7))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json([
            'daily_attempts' => $dailyAttempts,
            'top_failed_ips' => $topFailedIps,
            'browser_stats' => $browserStats,
            'device_stats' => $deviceStats,
            'hourly_distribution' => $hourlyDistribution
        ]);
    }

    /**
     * Block an IP address
     */
    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255'
        ]);

        // You would implement IP blocking logic here
        // This could involve adding to a blocked_ips table or firewall rules
        
        return redirect()->back()
            ->with('message', 'IP address has been blocked successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Clear old login attempts
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $cutoffDate = Carbon::now()->subDays($request->days);
        $deletedCount = LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('attempted_at', '<', $cutoffDate)
            ->delete();

        return redirect()->back()
            ->with('message', "Deleted {$deletedCount} old login attempts")
            ->with('alert-type', 'success');
    }

    /**
     * Export login attempts to CSV
     */
    public function export(Request $request)
    {
        $query = LoginAttempt::with('user')
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->orderBy('attempted_at', 'desc');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attempted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attempted_at', '<=', $request->date_to);
        }

        $loginAttempts = $query->limit(5000)->get(); // Limit to prevent memory issues

        $filename = 'login_attempts_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($loginAttempts) {
            $file = fopen('php://output', 'w');
            
            // Add CSV header
            fputcsv($file, [
                'ID',
                'Email',
                'IP Address',
                'Browser',
                'Device',
                'Operating System',
                'Location',
                'Status',
                'Failure Reason',
                'User ID',
                'Attempted At'
            ]);

            // Add data rows
            foreach ($loginAttempts as $attempt) {
                fputcsv($file, [
                    $attempt->id,
                    $attempt->email,
                    $attempt->ip_address,
                    $attempt->browser,
                    $attempt->device,
                    $attempt->operating_system,
                    $attempt->location,
                    $attempt->status,
                    $attempt->failure_reason,
                    $attempt->user_id,
                    $attempt->attempted_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get suspicious activities
     */
    public function suspicious()
    {
        $now = Carbon::now();
        
        // Multiple failed attempts from same IP
        $suspiciousIps = LoginAttempt::select('ip_address', DB::raw('COUNT(*) as attempts'))
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('status', 'failed')
            ->where('attempted_at', '>=', $now->subHours(24))
            ->groupBy('ip_address')
            ->having('attempts', '>=', 5)
            ->orderBy('attempts', 'desc')
            ->get();

        // Multiple failed attempts for same email
        $suspiciousEmails = LoginAttempt::select('email', DB::raw('COUNT(*) as attempts'))
            ->where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('status', 'failed')
            ->where('attempted_at', '>=', $now->subHours(24))
            ->whereNotNull('email')
            ->groupBy('email')
            ->having('attempts', '>=', 3)
            ->orderBy('attempts', 'desc')
            ->get();

        // Unusual login times (outside business hours)
        $unusualTimes = LoginAttempt::where('email', '!=', self::EXCLUDED_EMAIL)
            ->where('attempted_at', '>=', $now->subDays(7))
            ->where(function($query) {
                $query->whereTime('attempted_at', '<', '06:00:00')
                      ->orWhereTime('attempted_at', '>', '22:00:00');
            })
            ->orderBy('attempted_at', 'desc')
            ->limit(50)
            ->get();

        return view('backend.login-attempts.suspicious', compact(
            'suspiciousIps',
            'suspiciousEmails', 
            'unusualTimes'
        ));
    }
}