<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LayingHandsRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'laying_hands_request',
        'reason_to_see_go',
        'parish',
        'province',
        'region',
        'gender',
        'date_of_birth',
        'contact_address',
        'prayer_category',
        'preferred_communication',
        'service_attended',
        'how_heard_about_program',
        'additional_notes',
        'state',
        'is_rccg_member',
        'status',
        'approved_at',
        'notified_at',
        'treated_at',
        'approved_by',
        'admin_notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'approved_at' => 'datetime',
        'notified_at' => 'datetime',
        'treated_at' => 'datetime',
        'is_rccg_member' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    public function scopeNotified($query)
    {
        return $query->where('status', 'notified');
    }

    public function scopeTreated($query)
    {
        return $query->where('status', 'treated');
    }

    public function scopeRccgMembers($query)
    {
        return $query->where('is_rccg_member', true);
    }

    public function scopeNonMembers($query)
    {
        return $query->where('is_rccg_member', false);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isDeclined()
    {
        return $this->status === 'declined';
    }

    public function isNotified()
    {
        return $this->status === 'notified';
    }

    public function isTreated()
    {
        return $this->status === 'treated';
    }

    // Accessor for formatted dates
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getFormattedApprovedAtAttribute()
    {
        return $this->approved_at ? $this->approved_at->format('M d, Y h:i A') : null;
    }

    public function getFormattedNotifiedAtAttribute()
    {
        return $this->notified_at ? $this->notified_at->format('M d, Y h:i A') : null;
    }

    public function getFormattedTreatedAtAttribute()
    {
        return $this->treated_at ? $this->treated_at->format('M d, Y h:i A') : null;
    }

    // Status badge helper
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning text-warning',
            'approved' => 'bg-success text-success',
            'declined' => 'bg-danger text-danger',
            'notified' => 'bg-info text-info',
            'treated' => 'bg-primary text-primary',
        ];

        return $badges[$this->status] ?? 'bg-secondary text-secondary';
    }

    // Status icon helper
    public function getStatusIconAttribute()
    {
        $icons = [
            'pending' => 'mdi-clock-outline',
            'approved' => 'mdi-check-circle-outline',
            'declined' => 'mdi-close-circle-outline',
            'notified' => 'mdi-bell-outline',
            'treated' => 'mdi-hands-pray',
        ];

        return $icons[$this->status] ?? 'mdi-help-circle-outline';
    }
}
