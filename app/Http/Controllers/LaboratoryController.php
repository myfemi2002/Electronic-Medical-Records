<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ServiceOrder;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $orders = ServiceOrder::with('visit.patient')
            ->where('service_type', 'laboratory')
            ->latest()
            ->get();
        $reagents = InventoryItem::where('category', 'reagent')->latest()->take(10)->get();

        return view('backend.hms.laboratory.index', compact('orders', 'reagents'));
    }

    public function show(ServiceOrder $order)
    {
        $order->load('visit.patient');
        return view('backend.hms.laboratory.show', compact('order'));
    }

    public function storeResult(Request $request, ServiceOrder $order)
    {
        $validated = $request->validate([
            'sample_barcode' => 'nullable|string|max:255',
            'result_text' => 'required|string',
            'approve_now' => 'nullable|boolean',
        ]);

        $order->update([
            'sample_barcode' => $validated['sample_barcode'] ?: ($order->sample_barcode ?: 'LAB-' . strtoupper(uniqid())),
            'result_text' => $validated['result_text'],
            'status' => !empty($validated['approve_now']) ? 'approved' : 'processing',
            'processed_by' => auth()->id(),
            'approved_by' => !empty($validated['approve_now']) ? auth()->id() : null,
            'processed_at' => now(),
            'approved_at' => !empty($validated['approve_now']) ? now() : null,
        ]);

        if (!empty($validated['approve_now']) && $order->visit && $order->visit->current_stage === VisitWorkflowService::STAGE_LAB) {
            $this->workflow->moveToStage($order->visit, VisitWorkflowService::STAGE_DOCTOR, 'results_ready', 'Laboratory result approved and returned to doctor');
        }

        return back()->with('message', 'Laboratory result saved successfully.')->with('alert-type', 'success');
    }

    public function reports()
    {
        $stats = [
            'total_requests_today' => ServiceOrder::where('service_type', 'laboratory')->whereDate('created_at', today())->count(),
            'processing' => ServiceOrder::where('service_type', 'laboratory')->where('status', 'processing')->count(),
            'approved_today' => ServiceOrder::where('service_type', 'laboratory')->whereDate('approved_at', today())->count(),
            'pending_collection' => ServiceOrder::where('service_type', 'laboratory')->whereNull('sample_barcode')->count(),
        ];

        $recentOrders = ServiceOrder::with('visit.patient')
            ->where('service_type', 'laboratory')
            ->latest()
            ->take(20)
            ->get();

        return view('backend.hms.laboratory.reports', compact('stats', 'recentOrders'));
    }
}
