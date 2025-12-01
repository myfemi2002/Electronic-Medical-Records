<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'location',
        'phone',
        'email',
        'head_of_department_id',
        'status',
        'display_order',
        'can_receive_triage_patients',
        'requires_appointment',
        'working_hours',
    ];

    protected $casts = [
        'can_receive_triage_patients' => 'boolean',
        'requires_appointment' => 'boolean',
        'working_hours' => 'array',
    ];

    // Relationships
    public function headOfDepartment()
    {
        return $this->belongsTo(User::class, 'head_of_department_id');
    }

    public function staff()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    public function triageAssessments()
    {
        return $this->hasMany(TriageAssessment::class, 'forwarded_to_department_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMedical($query)
    {
        return $query->where('type', 'medical');
    }

    public function scopeSupport($query)
    {
        return $query->where('type', 'support');
    }

    public function scopeAdministrative($query)
    {
        return $query->where('type', 'administrative');
    }

    public function scopeCanReceiveTriage($query)
    {
        return $query->where('can_receive_triage_patients', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    // Get departments that can receive triage patients
    public static function getTriageForwardingOptions()
    {
        return self::active()
            ->canReceiveTriage()
            ->ordered()
            ->get();
    }

    // Get all active departments grouped by type
    public static function getGroupedDepartments()
    {
        return self::active()
            ->ordered()
            ->get()
            ->groupBy('type');
    }

    // Get staff count for department
    public function getStaffCountAttribute()
    {
        return $this->staff()->count();
    }

    // Check if department has a head
    public function hasHead()
    {
        return $this->head_of_department_id !== null;
    }

    // Get department with head name
    public function getFullNameAttribute()
    {
        $name = $this->name;
        
        if ($this->headOfDepartment) {
            $name .= ' (Head: ' . $this->headOfDepartment->name . ')';
        }
        
        return $name;
    }

    // Get badge HTML for department type
    public function getTypeBadgeAttribute()
    {
        $badges = [
            'medical' => '<span class="badge bg-success">Medical</span>',
            'support' => '<span class="badge bg-info">Support</span>',
            'administrative' => '<span class="badge bg-secondary">Administrative</span>',
        ];

        return $badges[$this->type] ?? '<span class="badge bg-secondary">Other</span>';
    }

    // Get status badge HTML
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active' 
            ? '<span class="badge bg-success">Active</span>' 
            : '<span class="badge bg-danger">Inactive</span>';
    }

    // Check if department is open now (based on working hours)
    public function isOpenNow()
    {
        if (!$this->working_hours) {
            return true; // Assume 24/7 if no hours specified
        }

        $today = strtolower(now()->format('l')); // 'monday', 'tuesday', etc.
        $currentTime = now()->format('H:i');

        if (!isset($this->working_hours[$today])) {
            return false; // Closed today
        }

        $hours = $this->working_hours[$today];
        
        if ($hours['closed'] ?? false) {
            return false;
        }

        return $currentTime >= $hours['open'] && $currentTime <= $hours['close'];
    }

    // Get display text for dropdowns
    public function getDisplayTextAttribute()
    {
        $text = $this->name;
        
        if ($this->location) {
            $text .= ' - ' . $this->location;
        }
        
        return $text;
    }
}