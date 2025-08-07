<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'browser',
        'device',
        'operating_system',
        'location',
        'status',
        'failure_reason',
        'user_id',
        'attempted_at'
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful login attempts
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed login attempts
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for recent attempts (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('attempted_at', '>=', Carbon::now()->subDay());
    }

    /**
     * Scope for attempts by IP address
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Scope for attempts by email
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Get status badge class for display
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'success' ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get formatted attempt time
     */
    public function getFormattedAttemptTimeAttribute()
    {
        return $this->attempted_at->format('M d, Y g:i A');
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute()
    {
        return $this->attempted_at->diffForHumans();
    }

    /**
     * Log a login attempt
     */
    public static function logAttempt($email, $request, $status, $failureReason = null, $userId = null)
    {
        return self::create([
            'email' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'browser' => self::getBrowser($request->header('User-Agent')),
            'device' => self::getDevice($request->header('User-Agent')),
            'operating_system' => self::getOS($request->header('User-Agent')),
            'location' => self::getLocation($request->ip()),
            'status' => $status,
            'failure_reason' => $failureReason,
            'user_id' => $userId,
            'attempted_at' => now()
        ]);
    }

    /**
     * Extract browser information from User Agent
     */
    private static function getBrowser($userAgent)
    {
        $browser = 'Unknown';
        
        if (preg_match('/MSIE/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Edge';
        }
        
        return $browser;
    }

    /**
     * Extract device type from User Agent
     */
    private static function getDevice($userAgent)
    {
        $device = 'Desktop';
        
        if (preg_match('/mobile|android|touch|iphone|ipad|tablet|samsung/i', $userAgent)) {
            $device = 'Mobile';
            
            if (preg_match('/tablet|ipad/i', $userAgent)) {
                $device = 'Tablet';
            }
        }
        
        return $device;
    }

    /**
     * Extract operating system from User Agent
     */
    private static function getOS($userAgent)
    {
        $os = 'Unknown';
        
        if (preg_match('/windows|win32|win64/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'Mac OS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iphone|ipad/i', $userAgent)) {
            $os = 'iOS';
        }
        
        return $os;
    }

    /**
     * Get location information from IP
     */
    private static function getLocation($ip)
    {
        // For local development
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Local Development';
        }
        
        try {
            // Basic implementation - for production, consider using a geolocation service
            return $ip;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Get failed attempts count for email in last 24 hours
     */
    public static function getFailedAttemptsCount($email, $hours = 24)
    {
        return self::where('email', $email)
            ->where('status', 'failed')
            ->where('attempted_at', '>=', Carbon::now()->subHours($hours))
            ->count();
    }

    /**
     * Get failed attempts count for IP in last 24 hours
     */
    public static function getFailedAttemptsCountByIp($ip, $hours = 24)
    {
        return self::where('ip_address', $ip)
            ->where('status', 'failed')
            ->where('attempted_at', '>=', Carbon::now()->subHours($hours))
            ->count();
    }

    /**
     * Check if account should be locked based on failed attempts
     */
    public static function shouldLockAccount($email, $maxAttempts = 5, $hours = 24)
    {
        return self::getFailedAttemptsCount($email, $hours) >= $maxAttempts;
    }

    /**
     * Check if IP should be blocked based on failed attempts
     */
    public static function shouldBlockIp($ip, $maxAttempts = 10, $hours = 24)
    {
        return self::getFailedAttemptsCountByIp($ip, $hours) >= $maxAttempts;
    }
}
