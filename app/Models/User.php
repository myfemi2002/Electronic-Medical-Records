<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\SecurityTracking;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SecurityTracking;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'profile' => 'array',
        'last_login_at' => 'datetime',
        'last_failed_login_at' => 'datetime',
        'is_logged_in' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
        // Add these casts for ban-related datetime fields
        'banned_at' => 'datetime',
        'ban_lifted_at' => 'datetime',
    ];

    /**
     * Generate a new 2FA secret
     */
    public function generateTwoFactorSecret()
    {
        $google2fa = new Google2FA();
        $this->two_factor_secret = Crypt::encrypt($google2fa->generateSecretKey());
        $this->save();
    }

    /**
     * Get decrypted 2FA secret
     */
    public function getTwoFactorSecretAttribute($value)
    {
        return $value ? Crypt::decrypt($value) : null;
    }

    /**
     * Get 2FA QR Code URL
     */
    public function getTwoFactorQrCodeUrl()
    {
        $google2fa = new Google2FA();
        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $this->email,
            $this->two_factor_secret
        );
    }

    /**
     * Verify 2FA code
     */
    public function verifyTwoFactorCode($code)
    {
        $google2fa = new Google2FA();
        return $google2fa->verifyKey($this->two_factor_secret, $code);
    }

    /**
     * Enable 2FA
     */
    public function enableTwoFactor()
    {
        $this->two_factor_enabled = true;
        $this->generateRecoveryCodes();
        $this->save();
    }

    /**
     * Disable 2FA
     */
    public function disableTwoFactor()
    {
        $this->two_factor_enabled = false;
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->save();
    }

    /**
     * Generate recovery codes
     */
    public function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10));
        }
        $this->two_factor_recovery_codes = $codes;
        $this->save();
        return $codes;
    }

    /**
     * Use recovery code
     */
    public function useRecoveryCode($code)
    {
        $codes = $this->two_factor_recovery_codes ?? [];
        $key = array_search(strtoupper($code), $codes);
        
        if ($key !== false) {
            unset($codes[$key]);
            $this->two_factor_recovery_codes = array_values($codes);
            $this->save();
            return true;
        }
        
        return false;
    }



    // Ban-related relationships
    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function banLiftedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ban_lifted_by');
    }

    // Users this admin has banned
    public function bannedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'banned_by');
    }

    // Users this admin has lifted bans for
    public function banLiftsGiven(): HasMany
    {
        return $this->hasMany(User::class, 'ban_lifted_by');
    }

    // Ban-related methods
    public function banUser(string $reason, User $bannedBy): bool
    {
        return $this->update([
            'is_banned' => true,
            'banned_at' => now(),
            'ban_reason' => $reason,
            'banned_by' => $bannedBy->id,
            'ban_lifted_at' => null,
            'ban_lifted_by' => null,
            'ban_lift_reason' => null,
            'is_logged_in' => 0, // Force logout
        ]);
    }

    public function liftBan(string $reason, User $liftedBy): bool
    {
        return $this->update([
            'is_banned' => false,
            'ban_lifted_at' => now(),
            'ban_lifted_by' => $liftedBy->id,
            'ban_lift_reason' => $reason,
        ]);
    }

    public function isBanned(): bool
    {
        return $this->is_banned;
    }

    public function getBanStatus(): string
    {
        if (!$this->is_banned) {
            return 'Active';
        }

        return 'Banned';
    }

    public function getBanDetails(): array
    {
        if (!$this->is_banned) {
            return [];
        }

        return [
            'banned_at' => $this->banned_at,
            'ban_reason' => $this->ban_reason,
            'banned_by' => $this->bannedBy,
            'ban_lifted_at' => $this->ban_lifted_at,
            'ban_lifted_by' => $this->banLiftedBy,
            'ban_lift_reason' => $this->ban_lift_reason,
        ];
    }

    // Scope for non-banned users
    public function scopeNotBanned($query)
    {
        return $query->where('is_banned', false);
    }

    // Scope for banned users
    public function scopeBanned($query)
    {
        return $query->where('is_banned', true);
    }

    public static function getPermissionGroups()
    {
        return Cache::remember('permission_groups', 60, function () {
            return DB::table('permissions')
                ->select('group_name')
                ->groupBy('group_name')
                ->get();
        });
    }

    public static function getPermissionByGroupName($group_name)
    {
        return Cache::remember("permissions_by_group_{$group_name}", 60, function () use ($group_name) {
            return DB::table('permissions')
                ->select('id', 'sidebar_name')
                ->where('group_name', $group_name)
                ->get();
        });
    }

    public static function roleHasPermissions($role, $permissions)
    {
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get profile field value
     */
    public function getProfileField($key, $default = null)
    {
        $profile = $this->profile ?? [];
        return $profile[$key] ?? $default;
    }

    /**
     * Set profile field value
     */
    public function setProfileField($key, $value)
    {
        $profile = $this->profile ?? [];
        $profile[$key] = $value;
        $this->update(['profile' => $profile]);
    }

    
}