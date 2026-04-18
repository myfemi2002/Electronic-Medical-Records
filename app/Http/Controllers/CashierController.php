<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentRefund;
use App\Models\Visit;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $visits = Visit::with('patient', 'invoice')
            ->where('current_stage', 'cashier')
            ->latest()
            ->get();

        $stats = [
            'queue' => $visits->count(),
            'paid_today' => Payment::whereDate('paid_at', today())->sum('amount'),
            'unpaid_invoices' => Invoice::where('payment_status', '!=', 'paid')->count(),
            'refunds_today' => PaymentRefund::whereDate('refunded_at', today())->sum('amount'),
        ];

        return view('backend.hms.cashier.index', compact('visits', 'stats'));
    }

    public function show(Visit $visit)
    {
        $visit->load('patient', 'invoice.items', 'invoice.payments.refunds');
        return view('backend.hms.cashier.show', compact('visit'));
    }

    public function generateInvoice(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'payer_type' => 'required|in:self,hmo,corporate',
            'items' => 'required|array|min:1',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.category' => 'nullable|string|max:100',
        ]);

        $this->workflow->createInvoice($visit, $validated['items'], $validated['payer_type']);

        return redirect()->route('admin.cashier.show', $visit)
            ->with('message', 'Invoice generated successfully.')
            ->with('alert-type', 'success');
    }

    public function confirmPayment(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:Cash,POS,Transfer,HMO',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
        ]);

        $this->workflow->confirmPayment($visit, $validated);

        return redirect()->route('admin.cashier.show', $visit)
            ->with('message', 'Payment confirmed. Visit has been forwarded to triage.')
            ->with('alert-type', 'success');
    }

    public function refund(Request $request, Payment $payment)
    {
        abort_unless(auth()->user()->can('refund payment'), 403);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'reason' => 'required|string',
        ]);

        $payment->refunds()->create([
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
            'refunded_by' => auth()->id(),
            'refunded_at' => now(),
        ]);

        return back()
            ->with('message', 'Refund recorded successfully.')
            ->with('alert-type', 'success');
    }

    public function reports()
    {
        $stats = [
            'invoices_today' => Invoice::whereDate('created_at', today())->count(),
            'revenue_today' => Payment::whereDate('paid_at', today())->sum('amount'),
            'hmo_payments_today' => Payment::whereDate('paid_at', today())->where('payment_method', 'HMO')->sum('amount'),
            'refunds_today' => PaymentRefund::whereDate('refunded_at', today())->sum('amount'),
        ];

        $recentInvoices = Invoice::with('patient', 'visit')
            ->latest()
            ->take(20)
            ->get();

        return view('backend.hms.cashier.reports', compact('stats', 'recentInvoices'));
    }
}
