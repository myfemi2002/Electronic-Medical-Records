<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriageAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'triage_queue_id',
        'patient_id',
        'assessed_by',
        'assessed_by_role',
        'chief_complaints',
        'history_of_present_illness',
        'priority_level',
        'initial_assessment_notes',
        'system_generated_suggestions',
        'nurse_notes',
        'forwarded_to_department_id',
        'forwarded_to_staff_id',
        'forwarded_to_staff_role',
        'forwarded_at',
        'forwarding_reason',
    ];

    protected $casts = [
        'system_generated_suggestions' => 'array',
        'forwarded_at' => 'datetime',
    ];

    // Relationships
    public function triageQueue()
    {
        return $this->belongsTo(TriageQueue::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * DYNAMIC STAFF - Staff who did the assessment
     */
    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    /**
     * DYNAMIC STAFF - Staff who patient was forwarded to
     */
    public function forwardedToStaff()
    {
        return $this->belongsTo(User::class, 'forwarded_to_staff_id');
    }

    /**
     * DYNAMIC DEPARTMENT - Department patient was forwarded to
     */
    public function forwardedToDepartment()
    {
        return $this->belongsTo(Department::class, 'forwarded_to_department_id');
    }

    // Alias for backward compatibility
    public function forwardedToDoctor()
    {
        return $this->forwardedToStaff();
    }

    /**
     * Forward patient to any department/staff
     * 
     * @param int $departmentId - Department ID from departments table
     * @param string|null $reason - Forwarding reason
     * @param int|null $staffId - Specific staff member ID (optional)
     */
    public function forwardPatient($departmentId, $reason = null, $staffId = null)
    {
        $staffRole = null;
        
        // Auto-detect staff role if staff ID provided
        if ($staffId) {
            $user = User::find($staffId);
            $staffRole = $user->staff_type ?? $user->role ?? null;
        }

        $this->update([
            'forwarded_to_department_id' => $departmentId,
            'forwarded_to_staff_id' => $staffId,
            'forwarded_to_staff_role' => $staffRole,
            'forwarded_at' => now(),
            'forwarding_reason' => $reason,
        ]);

        // Update triage queue status
        $this->triageQueue->forward();
    }

    // Attributes
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'critical' => '<span class="badge bg-danger">Critical</span>',
            'moderate' => '<span class="badge bg-warning text-dark">Moderate</span>',
            'mild' => '<span class="badge bg-success">Mild</span>',
        ];

        return $badges[$this->priority_level] ?? '<span class="badge bg-secondary">Pending</span>';
    }

    public function getForwardedBadgeAttribute()
    {
        if (!$this->forwardedToDepartment) {
            return '<span class="badge bg-secondary">Not Forwarded</span>';
        }

        $colors = [
            'medical' => 'primary',
            'support' => 'info',
            'administrative' => 'secondary',
        ];

        $color = $colors[$this->forwardedToDepartment->type] ?? 'secondary';

        return '<span class="badge bg-' . $color . '">' . $this->forwardedToDepartment->name . '</span>';
    }

    /**
     * Get assessment summary text
     */
    public function getAssessmentSummaryAttribute()
    {
        $summary = "Priority: " . ucfirst($this->priority_level);
        
        if ($this->forwardedToDepartment) {
            $summary .= " | Forwarded to: " . $this->forwardedToDepartment->name;
            
            if ($this->forwardedToStaff) {
                $summary .= " (" . $this->forwardedToStaff->name . ")";
            }
        }
        
        return $summary;
    }

    /**
     * Get assessed by name with role
     */
    public function getAssessedByNameAttribute()
    {
        if (!$this->assessedBy) {
            return 'Unknown';
        }

        $name = $this->assessedBy->name;
        $role = $this->assessed_by_role ? ' (' . ucfirst($this->assessed_by_role) . ')' : '';
        
        return $name . $role;
    }

    /**
     * Get forwarded to name with role
     */
    public function getForwardedToNameAttribute()
    {
        if (!$this->forwardedToStaff) {
            return 'Not Forwarded';
        }

        $name = $this->forwardedToStaff->name;
        $role = $this->forwarded_to_staff_role ? ' (' . ucfirst($this->forwarded_to_staff_role) . ')' : '';
        
        return $name . $role;
    }
}