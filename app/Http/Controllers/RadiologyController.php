<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class RadiologyController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $orders = ServiceOrder::with('visit.patient')
            ->where('service_type', 'radiology')
            ->latest()
            ->get();

        return view('backend.hms.radiology.index', compact('orders'));
    }

    public function show(ServiceOrder $order)
    {
        $order->load('visit.patient');
        return view('backend.hms.radiology.show', compact('order'));
    }

    public function storeReport(Request $request, ServiceOrder $order)
    {
        $validated = $request->validate([
            'report_text' => 'required|string',
            'template_name' => 'nullable|string|max:255',
            'comparison_notes' => 'nullable|string',
            'image_path' => 'nullable|string|max:255',
            'approve_now' => 'nullable|boolean',
        ]);

        $order->update([
            'report_text' => $validated['report_text'],
            'template_name' => $validated['template_name'] ?? null,
            'comparison_notes' => $validated['comparison_notes'] ?? null,
            'image_path' => $validated['image_path'] ?? null,
            'status' => !empty($validated['approve_now']) ? 'approved' : 'processing',
            'processed_by' => auth()->id(),
            'approved_by' => !empty($validated['approve_now']) ? auth()->id() : null,
            'processed_at' => now(),
            'approved_at' => !empty($validated['approve_now']) ? now() : null,
        ]);

        if (!empty($validated['approve_now']) && $order->visit && $order->visit->current_stage === VisitWorkflowService::STAGE_RADIOLOGY) {
            $this->workflow->moveToStage($order->visit, VisitWorkflowService::STAGE_DOCTOR, 'results_ready', 'Radiology report approved and returned to doctor');
        }

        return back()->with('message', 'Radiology report saved successfully.')->with('alert-type', 'success');
    }

    public function reports()
    {
        $stats = [
            'total_requests_today' => ServiceOrder::where('service_type', 'radiology')->whereDate('created_at', today())->count(),
            'processing' => ServiceOrder::where('service_type', 'radiology')->where('status', 'processing')->count(),
            'approved_today' => ServiceOrder::where('service_type', 'radiology')->whereDate('approved_at', today())->count(),
            'with_images' => ServiceOrder::where('service_type', 'radiology')->whereNotNull('image_path')->count(),
        ];

        $recentOrders = ServiceOrder::with('visit.patient')
            ->where('service_type', 'radiology')
            ->latest()
            ->take(20)
            ->get();

        return view('backend.hms.radiology.reports', compact('stats', 'recentOrders'));
    }
}
