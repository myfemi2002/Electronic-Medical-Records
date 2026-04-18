<?php

namespace App\Http\Controllers;

use App\Models\ClinicalEncounter;
use App\Models\Prescription;
use App\Models\ServiceOrder;
use App\Models\Visit;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $visits = Visit::with('patient', 'triageQueue.assessment')
            ->where('current_stage', 'doctor')
            ->latest()
            ->get();

        return view('backend.hms.doctor.index', compact('visits'));
    }

    public function show(Visit $visit)
    {
        $visit->load('patient', 'triageQueue.vitals', 'triageQueue.assessment', 'encounter', 'prescriptions', 'serviceOrders');

        return view('backend.hms.doctor.show', compact('visit'));
    }

    public function storeEncounter(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'subjective' => 'required|string',
            'objective' => 'nullable|string',
            'assessment' => 'required|string',
            'plan' => 'nullable|string',
            'icd10_code' => 'nullable|string|max:50',
            'diagnosis' => 'required|string|max:255',
            'disposition' => 'required|in:ongoing,nurse,pharmacy,lab,radiology,discharged',
            'referral_notes' => 'nullable|string',
            'discharge_notes' => 'nullable|string',
        ]);

        $this->workflow->markDoctorSeen($visit);

        ClinicalEncounter::updateOrCreate(
            ['visit_id' => $visit->id],
            array_merge($validated, [
                'patient_id' => $visit->patient_id,
                'doctor_id' => auth()->id(),
                'completed_at' => now(),
            ])
        );

        $nextStage = match ($validated['disposition']) {
            'nurse' => 'nurse',
            'pharmacy' => 'pharmacy',
            'lab' => 'laboratory',
            'radiology' => 'radiology',
            'discharged' => 'discharged',
            default => 'doctor',
        };

        if ($nextStage === 'discharged') {
            $this->workflow->discharge($visit, $validated['discharge_notes'] ?? 'Discharged by doctor');
        } elseif ($nextStage !== 'doctor') {
            $this->workflow->moveToStage($visit, $nextStage, 'awaiting_' . $nextStage, 'Forwarded by doctor');
        }

        return redirect()->route('admin.doctor.show', $visit)
            ->with('message', 'Consultation saved successfully.')
            ->with('alert-type', 'success');
    }

    public function addPrescription(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'drug_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        Prescription::create(array_merge($validated, [
            'visit_id' => $visit->id,
            'patient_id' => $visit->patient_id,
            'encounter_id' => optional($visit->encounter)->id,
            'prescribed_by' => auth()->id(),
        ]));

        return back()->with('message', 'Prescription added.')->with('alert-type', 'success');
    }

    public function addServiceOrder(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:laboratory,radiology',
            'request_name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        ServiceOrder::create(array_merge($validated, [
            'visit_id' => $visit->id,
            'patient_id' => $visit->patient_id,
            'encounter_id' => optional($visit->encounter)->id,
            'requested_by' => auth()->id(),
        ]));

        return back()->with('message', 'Service request created.')->with('alert-type', 'success');
    }
}
