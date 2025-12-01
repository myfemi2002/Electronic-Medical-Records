<?php

namespace App\Http\Controllers;

use App\Models\TriageQueue;
use App\Models\TriageVital;
use App\Models\TriageAssessment;
use App\Models\Patient;
use App\Models\Department;
use App\Models\User;
use App\Services\VitalInterpreter;
use Illuminate\Http\Request;

class TriageController extends Controller
{
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
     * Display waiting list
     */
    public function waitingList()
    {
        $patients = TriageQueue::getWaitingList();
        $stats = TriageQueue::getStats();

        return view('backend.triage.waiting-list', compact('patients', 'stats'));
    }

    /**
     * Show vitals capture form
     */
    public function captureVitals($queueId)
    {
        $queue = TriageQueue::with('patient')->findOrFail($queueId);

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

        // Create vital record
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
        $queue = TriageQueue::with(['patient', 'vitals'])->findOrFail($queueId);

        if (!$queue->vitals) {
            return redirect()->route('admin.triage.capture-vitals', $queue->id)
                ->with('message', 'Please capture vitals first')
                ->with('alert-type', 'warning');
        }

        // Get system suggestions
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

        // Create assessment
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

        // Forward patient
        $assessment->forwardPatient(
            departmentId: $validated['forwarded_to_department_id'],
            reason: $validated['forwarding_reason'],
            staffId: $validated['forwarded_to_staff_id']
        );

        $department = Department::find($validated['forwarded_to_department_id']);

        return redirect()->route('admin.triage.waiting-list')
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
