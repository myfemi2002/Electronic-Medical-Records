<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TriageQueue extends Model
{
    use HasFactory;

    protected $table = 'triage_queue';

    protected $fillable = [
        'patient_id',
        'visit_id',
        'queue_number',
        'status',
        'priority',
        'assigned_staff',
        'assigned_staff_role',
        'joined_queue_at',
        'started_at',
        'completed_at',
        'initial_complaint',
    ];

    protected $casts = [
        'joined_queue_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff');
    }

    public function vitals()
    {
        return $this->hasOne(TriageVital::class);
    }

    public function assessment()
    {
        return $this->hasOne(TriageAssessment::class);
    }

    // Generate unique queue number
    public static function generateQueueNumber()
    {
        $date = now()->format('Ymd');
        $lastQueue = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastQueue) {
            $lastNumber = (int) substr($lastQueue->queue_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return 'TRG-' . $date . '-' . $newNumber;
    }

    // Calculate wait time in minutes (DYNAMIC - not stored in DB)
    public function calculateWaitTime()
    {
        if ($this->started_at) {
            return $this->joined_queue_at->diffInMinutes($this->started_at);
        }
        
        return $this->joined_queue_at->diffInMinutes(now());
    }

    // Start triage with dynamic staff
    public function startTriage($staffId, $staffRole)
    {
        $this->update([
            'status' => 'in_progress',
            'assigned_staff' => $staffId,
            'assigned_staff_role' => $staffRole,
            'started_at' => now(),
        ]);
    }

    // Complete triage
    public function completeTriage()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    // Forward to department
    public function forward()
    {
        $this->update([
            'status' => 'forwarded',
        ]);
    }

    // Scopes
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    public function scopeModerate($query)
    {
        return $query->where('priority', 'moderate');
    }

    public function scopeMild($query)
    {
        return $query->where('priority', 'mild');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('joined_queue_at', today());
    }

    // Get waiting list ordered by priority and join time
    public static function getWaitingList()
    {
        return self::with(['patient', 'assignedStaff'])
            ->waiting()
            ->today()
            ->orderByRaw("FIELD(priority, 'critical', 'moderate', 'mild')")
            ->orderBy('joined_queue_at', 'asc')
            ->get();
    }

    // Get statistics (FIXED - Calculate wait time dynamically)
    public static function getStats()
    {
        $today = today();

        // Get all today's records to calculate average wait time
        $todayRecords = self::today()->get();
        $totalWaitTime = 0;
        $count = $todayRecords->count();

        if ($count > 0) {
            foreach ($todayRecords as $record) {
                $totalWaitTime += $record->calculateWaitTime();
            }
            $averageWaitTime = $totalWaitTime / $count;
        } else {
            $averageWaitTime = 0;
        }

        return [
            'waiting_count' => self::waiting()->today()->count(),
            'in_progress_count' => self::inProgress()->today()->count(),
            'completed_count' => self::where('status', 'completed')->whereDate('completed_at', $today)->count(),
            'critical_count' => self::critical()->today()->count(),
            'moderate_count' => self::moderate()->today()->count(),
            'mild_count' => self::mild()->today()->count(),
            'total_today' => $count,
            'average_wait_time' => round($averageWaitTime, 2),
        ];
    }

    /**
     * Add patient to triage queue from open file
     * Called automatically when consultancy is verified and file is opened
     */
    public static function addFromOpenFile($patientId, $initialComplaint = null)
    {
        // Check if patient already in queue today
        $existingQueue = self::where('patient_id', $patientId)
            ->today()
            ->whereIn('status', ['waiting', 'in_progress'])
            ->first();

        if ($existingQueue) {
            return $existingQueue; // Patient already in queue
        }

        // Create new queue entry
        return self::create([
            'patient_id' => $patientId,
            'queue_number' => self::generateQueueNumber(),
            'status' => 'waiting',
            'priority' => null, // Will be set after vital signs assessment
            'joined_queue_at' => now(),
            'initial_complaint' => $initialComplaint,
        ]);
    }

    public static function addFromVisit(Visit $visit): self
    {
        $existingQueue = self::where('visit_id', $visit->id)
            ->whereIn('status', ['waiting', 'in_progress', 'completed'])
            ->first();

        if ($existingQueue) {
            return $existingQueue;
        }

        return self::create([
            'patient_id' => $visit->patient_id,
            'visit_id' => $visit->id,
            'queue_number' => self::generateQueueNumber(),
            'status' => 'waiting',
            'priority' => null,
            'joined_queue_at' => now(),
            'initial_complaint' => $visit->chief_complaint,
        ]);
    }

    // Attributes
    public function getWaitTimeFormattedAttribute()
    {
        $minutes = $this->calculateWaitTime();
        
        if ($minutes < 60) {
            return $minutes . ' min';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        return $hours . 'h ' . $remainingMinutes . 'm';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'critical' => '<span class="badge bg-danger">Critical</span>',
            'moderate' => '<span class="badge bg-warning text-dark">Moderate</span>',
            'mild' => '<span class="badge bg-success">Mild</span>',
        ];

        return $badges[$this->priority] ?? '<span class="badge bg-secondary">Pending</span>';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'waiting' => '<span class="badge bg-info">Waiting</span>',
            'in_progress' => '<span class="badge bg-primary">In Progress</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'forwarded' => '<span class="badge bg-warning text-dark">Forwarded</span>',
        ];

        return $badges[$this->status] ?? '';
    }
}
