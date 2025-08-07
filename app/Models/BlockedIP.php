<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedIP extends Model
{
    use HasFactory;

    protected $table = 'blocked_i_p_s';

    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_by',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isPermanent()
    {
        return is_null($this->expires_at);
    }

    public function getStatusAttribute()
    {
        if ($this->isPermanent()) {
            return 'Permanent';
        }
        
        if ($this->isExpired()) {
            return 'Expired';
        }
        
        return 'Active until ' . $this->expires_at->format('M d, Y H:i');
    }
}
