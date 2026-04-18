<?php

namespace App\Http\Controllers;

use App\Models\NursingNote;
use App\Models\Visit;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class NurseController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $visits = Visit::with('patient')->where('current_stage', 'nurse')->latest()->get();

        return view('backend.hms.nurse.index', compact('visits'));
    }

    public function show(Visit $visit)
    {
        $visit->load('patient', 'nursingNotes', 'prescriptions', 'encounter');

        return view('backend.hms.nurse.show', compact('visit'));
    }

    public function storeNote(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'note' => 'required|string',
            'medication_administration' => 'nullable|string',
            'procedures' => 'nullable|string',
            'bed_allocation' => 'nullable|string|max:255',
            'fluid_balance' => 'nullable|string',
            'complete_visit' => 'nullable|boolean',
        ]);

        NursingNote::create([
            'visit_id' => $visit->id,
            'patient_id' => $visit->patient_id,
            'nurse_id' => auth()->id(),
            'note' => $validated['note'],
            'medication_administration' => $validated['medication_administration'] ?? null,
            'procedures' => $validated['procedures'] ?? null,
            'bed_allocation' => $validated['bed_allocation'] ?? null,
            'fluid_balance' => $validated['fluid_balance'] ?? null,
        ]);

        if (!empty($validated['complete_visit'])) {
            $this->workflow->discharge($visit, 'Nursing module completed visit');
        }

        return back()->with('message', 'Nursing note saved.')->with('alert-type', 'success');
    }
}
