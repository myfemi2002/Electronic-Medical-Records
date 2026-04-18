<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $visits = Visit::with('patient', 'invoice')->latest()->paginate(15);
        $stats = [
            'registered' => Visit::where('current_stage', 'records')->count(),
            'awaiting_payment' => Visit::where('current_stage', 'cashier')->count(),
            'in_triage' => Visit::where('current_stage', 'triage')->count(),
            'active' => Visit::whereNotIn('status', ['discharged'])->count(),
        ];

        return view('backend.hms.visits.index', compact('visits', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_type' => 'required|string|max:50',
            'chief_complaint' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_emergency' => 'nullable|boolean',
        ]);

        $visit = $this->workflow->createVisit($validated);
        $this->workflow->pushToCashier($visit, 'Visit sent to cashier from records');

        return redirect()->route('admin.hms.visits.index')
            ->with('message', 'Visit created and pushed to cashier successfully.')
            ->with('alert-type', 'success');
    }

    public function show(Visit $visit)
    {
        $visit->load([
            'patient',
            'invoice.items',
            'invoice.payments.refunds',
            'triageQueue.vitals',
            'triageQueue.assessment',
            'encounter',
            'prescriptions',
            'serviceOrders',
            'nursingNotes',
            'stageLogs',
        ]);

        return view('backend.hms.visits.show', compact('visit'));
    }

    public function createForPatient(Patient $patient)
    {
        return view('backend.hms.visits.create', compact('patient'));
    }
}
