<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPAccessLog extends Model
{
    use HasFactory;

    protected $table = 'i_p_access_logs';

    protected $fillable = [
        'ip_address',
        'user_id',
        'user_agent',
        'request_url',
        'http_method',
        'is_blocked',
        'location',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_blocked ? 
            '<span class="badge bg-danger">Blocked</span>' : 
            '<span class="badge bg-success">Allowed</span>';
    }

    public function getBrowserAttribute()
    {
        if (strpos($this->user_agent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($this->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($this->user_agent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($this->user_agent, 'Edge') !== false) {
            return 'Edge';
        }
        return 'Unknown';
    }
}
