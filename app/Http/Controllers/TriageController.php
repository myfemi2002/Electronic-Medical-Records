<?php

namespace App\Http\Controllers;

use App\Models\TriageQueue;
use App\Models\TriageVital;
use App\Models\TriageAssessment;
use App\Models\Patient;
use App\Models\Department;
use App\Models\User;
use App\Services\VitalInterpreter;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class TriageController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }
    /**
     * Display triage dashboard
     */
    public function index()
    {
        $stats = TriageQueue::getStats();
        
        $criticalPatients = TriageQueue::with(['patient', 'assignedStaff'])
            ->critical()
            ->whereIn('status', ['waiting', 'in_progress'])
            ->today()
            ->get();

        return view('backend.triage.index', compact('stats', 'criticalPatients'));
    }

    /**
     * Display comprehensive queue management with tabs for all statuses
     * THIS IS THE NEW METHOD - Use this instead of waitingList()
     */
    public function queueManagement()
    {
        $stats = TriageQueue::getStats();

        // Get all patients today with relationships
        $baseQuery = TriageQueue::with(['patient', 'assignedStaff', 'vitals', 'assessment'])
            ->today()
            ->orderByRaw("FIELD(priority, 'critical', 'moderate', 'mild')")
            ->orderBy('joined_queue_at', 'asc');

        // All patients
        $allPatients = (clone $baseQuery)->get();

        // Waiting patients (no vitals captured yet OR vitals captured but in waiting status)
        $waitingPatients = (clone $baseQuery)->where('status', 'waiting')->get();

        // In Progress patients (vitals being captured or assessment in progress)
        $inProgressPatients = (clone $baseQuery)->where('status', 'in_progress')->get();

        // Completed patients (assessment done but not forwarded)
        $completedPatients = (clone $baseQuery)->where('status', 'completed')->get();

        // Forwarded patients
        $forwardedPatients = (clone $baseQuery)->where('status', 'forwarded')->get();

        return view('backend.triage.queue-management', compact(
            'stats',
            'allPatients',
            'waitingPatients',
            'inProgressPatients',
            'completedPatients',
            'forwardedPatients'
        ));
    }

    /**
     * Original waiting list method - kept for backward compatibility
     * You can update routes to use queueManagement() instead
     */
    public function waitingList()
    {
        // Redirect to new comprehensive view
        return redirect()->route('admin.triage.queue-management');
    }

    /**
     * Show vitals capture form
     */
    public function captureVitals($queueId)
    {
        $queue = TriageQueue::with('patient', 'vitals')->findOrFail($queueId);

        // Start triage if not started
        if ($queue->status === 'waiting') {
            $userRole = auth()->user()->staff_type ?? auth()->user()->role;
            $queue->startTriage(auth()->id(), $userRole);
        }

        return view('backend.triage.capture-vitals', compact('queue'));
    }

    /**
     * Store vitals and run interpretation
     */
    public function storeVitals(Request $request, $queueId)
    {
        $queue = TriageQueue::findOrFail($queueId);

        $validated = $request->validate([
            'blood_pressure' => 'required|string',
            'temperature' => 'required|numeric|min:30|max:45',
            'pulse_rate' => 'required|integer|min:30|max:200',
            'respiratory_rate' => 'required|integer|min:5|max:60',
            'oxygen_saturation' => 'required|integer|min:70|max:100',
            'weight' => 'required|numeric|min:1|max:300',
            'height' => 'required|numeric|min:30|max:250',
            'notes' => 'nullable|string',
        ]);

        $userRole = auth()->user()->staff_type ?? auth()->user()->role;

        // Check if vitals already exist (updating)
        if ($queue->vitals) {
            $vital = $queue->vitals;
            $vital->update([
                'recorded_by' => auth()->id(),
                'recorded_by_role' => $userRole,
                'blood_pressure' => $validated['blood_pressure'],
                'temperature' => $validated['temperature'],
                'pulse_rate' => $validated['pulse_rate'],
                'respiratory_rate' => $validated['respiratory_rate'],
                'oxygen_saturation' => $validated['oxygen_saturation'],
                'weight' => $validated['weight'],
                'height' => $validated['height'],
                'notes' => $validated['notes'],
            ]);
        } else {
            // Create new vital record
            $vital = TriageVital::create([
                'triage_queue_id' => $queue->id,
                'patient_id' => $queue->patient_id,
                'recorded_by' => auth()->id(),
                'recorded_by_role' => $userRole,
                'blood_pressure' => $validated['blood_pressure'],
                'temperature' => $validated['temperature'],
                'pulse_rate' => $validated['pulse_rate'],
                'respiratory_rate' => $validated['respiratory_rate'],
                'oxygen_saturation' => $validated['oxygen_saturation'],
                'weight' => $validated['weight'],
                'height' => $validated['height'],
                'notes' => $validated['notes'],
            ]);
        }

        // Calculate BMI
        $vital->calculateBMI();

        // Run interpretation
        $analysis = VitalInterpreter::analyze($vital);

        // Update vital with interpretations
        $vital->update([
            'bp_interpretation' => $analysis['interpretations']['bp'],
            'temp_interpretation' => $analysis['interpretations']['temp'],
            'pulse_interpretation' => $analysis['interpretations']['pulse'],
            'rr_interpretation' => $analysis['interpretations']['rr'],
            'spo2_interpretation' => $analysis['interpretations']['spo2'],
            'bmi_interpretation' => $analysis['interpretations']['bmi'],
            'overall_priority' => $analysis['overall_priority'],
            'clinical_alerts' => $analysis['alerts'],
        ]);

        // Update queue priority
        $queue->update(['priority' => $analysis['overall_priority']]);

        return redirect()->route('admin.triage.assessment', $queue->id)
            ->with('message', 'Vitals captured and analyzed successfully')
            ->with('alert-type', 'success');
    }

    /**
     * Show assessment form with vitals interpretation
     */
    public function assessment($queueId)
    {
        $queue = TriageQueue::with(['patient', 'vitals', 'assessment'])->findOrFail($queueId);

        // Check if vitals exist
        if (!$queue->vitals) {
            return redirect()->route('admin.triage.capture-vitals', $queue->id)
                ->with('message', 'Please capture vitals first')
                ->with('alert-type', 'warning');
        }

        // Check if assessment already completed and forwarded
        if ($queue->assessment && $queue->status === 'forwarded') {
            return redirect()->route('admin.triage.queue-management')
                ->with('message', 'This patient has already been assessed and forwarded')
                ->with('alert-type', 'info');
        }

        // Get system suggestions from vitals
        $analysis = VitalInterpreter::analyze($queue->vitals);
        
        // Get departments for forwarding
        $departments = Department::getTriageForwardingOptions();

        return view('backend.triage.assessment', compact('queue', 'analysis', 'departments'));
    }

    /**
     * Store assessment and forward patient
     */
    public function storeAssessment(Request $request, $queueId)
    {
        $queue = TriageQueue::with('vitals')->findOrFail($queueId);

        // Check if already forwarded
        if ($queue->status === 'forwarded') {
            return redirect()->route('admin.triage.queue-management')
                ->with('message', 'This patient has already been forwarded')
                ->with('alert-type', 'warning');
        }

        $validated = $request->validate([
            'chief_complaints' => 'required|string',
            'history_of_present_illness' => 'nullable|string',
            'priority_level' => 'required|in:mild,moderate,critical',
            'initial_assessment_notes' => 'nullable|string',
            'nurse_notes' => 'nullable|string',
            'forwarded_to_department_id' => 'required|exists:departments,id',
            'forwarded_to_staff_id' => 'nullable|exists:users,id',
            'forwarding_reason' => 'required|string',
        ]);

        $userRole = auth()->user()->staff_type ?? auth()->user()->role;

        // Re-run interpretation to get suggestions
        $analysis = VitalInterpreter::analyze($queue->vitals);

        // Check if assessment already exists
        if ($queue->assessment) {
            // Update existing assessment
            $assessment = $queue->assessment;
            $assessment->update([
                'assessed_by' => auth()->id(),
                'assessed_by_role' => $userRole,
                'chief_complaints' => $validated['chief_complaints'],
                'history_of_present_illness' => $validated['history_of_present_illness'],
                'priority_level' => $validated['priority_level'],
                'initial_assessment_notes' => $validated['initial_assessment_notes'],
                'nurse_notes' => $validated['nurse_notes'],
                'system_generated_suggestions' => $analysis['suggestions'],
            ]);
        } else {
            // Create new assessment
            $assessment = TriageAssessment::create([
                'triage_queue_id' => $queue->id,
                'patient_id' => $queue->patient_id,
                'assessed_by' => auth()->id(),
                'assessed_by_role' => $userRole,
                'chief_complaints' => $validated['chief_complaints'],
                'history_of_present_illness' => $validated['history_of_present_illness'],
                'priority_level' => $validated['priority_level'],
                'initial_assessment_notes' => $validated['initial_assessment_notes'],
                'nurse_notes' => $validated['nurse_notes'],
                'system_generated_suggestions' => $analysis['suggestions'],
            ]);
        }

        // Forward patient
        $assessment->forwardPatient(
            departmentId: $validated['forwarded_to_department_id'],
            reason: $validated['forwarding_reason'],
            staffId: $validated['forwarded_to_staff_id']
        );

        $department = Department::find($validated['forwarded_to_department_id']);

        if ($queue->visit) {
            $stage = match (strtolower($department->code ?? $department->name ?? 'doctor')) {
                'emg', 'opd' => VisitWorkflowService::STAGE_DOCTOR,
                'lab', 'laboratory' => VisitWorkflowService::STAGE_LAB,
                'rad', 'radiology' => VisitWorkflowService::STAGE_RADIOLOGY,
                'phm', 'pharmacy' => VisitWorkflowService::STAGE_PHARMACY,
                'acc', 'accounts' => VisitWorkflowService::STAGE_CASHIER,
                default => VisitWorkflowService::STAGE_DOCTOR,
            };

            if ($stage === VisitWorkflowService::STAGE_DOCTOR) {
                $this->workflow->markTriaged($queue->visit);
            } else {
                $this->workflow->moveToStage($queue->visit, $stage, 'awaiting_' . $stage, 'Forwarded from triage to ' . $department->name);
            }
        }

        return redirect()->route('admin.triage.queue-management')
            ->with('message', 'Patient assessed and forwarded to ' . $department->name)
            ->with('alert-type', 'success');
    }

    /**
     * Display triage reports
     */
    public function reports()
    {
        $stats = TriageQueue::getStats();
        
        $todayQueue = TriageQueue::with(['patient', 'assignedStaff'])
            ->today()
            ->orderBy('joined_queue_at', 'desc')
            ->get();

        $priorityDistribution = [
            'critical' => TriageQueue::critical()->today()->count(),
            'moderate' => TriageQueue::moderate()->today()->count(),
            'mild' => TriageQueue::mild()->today()->count(),
        ];

        return view('backend.triage.reports', compact('stats', 'todayQueue', 'priorityDistribution'));
    }

    /**
     * Get department staff (AJAX)
     */
    public function getDepartmentStaff($departmentId)
    {
        $staff = User::where('department_id', $departmentId)
            ->where('status', 'active')
            ->select('id', 'name', 'staff_type', 'staff_id')
            ->get();

        return response()->json($staff);
    }
}
