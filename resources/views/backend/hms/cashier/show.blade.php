@extends('admin.admin_master')
@section('title', 'Cashier Workspace')
@section('admin')
<div class="page-header">
    <h1>Cashier Workspace</h1>
    <p>{{ $visit->patient->full_name }} • {{ $visit->visit_number }}</p>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header"><h5 class="card-title">Generate Invoice</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cashier.invoice', $visit) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Payer Type</label>
                            <select class="form-select" name="payer_type">
                                <option value="self">Self</option>
                                <option value="hmo">HMO</option>
                                <option value="corporate">Corporate</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Service</label>
                            <input class="form-control" name="items[0][service_name]" value="Consultation">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Qty</label>
                            <input class="form-control" type="number" name="items[0][quantity]" value="1" min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Amount</label>
                            <input class="form-control" type="number" step="0.01" name="items[0][unit_price]" value="5000">
                            <input type="hidden" name="items[0][category]" value="consultation">
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit">Generate Invoice</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="card-title">Confirm Payment</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cashier.payment', $visit) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Method</label>
                            <select class="form-select" name="payment_method">
                                <option>Cash</option>
                                <option>POS</option>
                                <option>Transfer</option>
                                <option>HMO</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Amount</label>
                            <input class="form-control" type="number" step="0.01" name="amount" value="{{ old('amount', optional($visit->invoice)->balance) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reference</label>
                            <input class="form-control" name="reference">
                        </div>
                    </div>
                    <button class="btn btn-success mt-3" type="submit">Confirm Payment and Forward to Triage</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Invoice Summary</h5></div>
            <div class="card-body">
                @if($visit->invoice)
                    <p><strong>Invoice:</strong> {{ $visit->invoice->invoice_number }}</p>
                    <p><strong>Total:</strong> N{{ number_format($visit->invoice->total, 2) }}</p>
                    <p><strong>Paid:</strong> N{{ number_format($visit->invoice->amount_paid, 2) }}</p>
                    <p><strong>Balance:</strong> N{{ number_format($visit->invoice->balance, 2) }}</p>
                    <hr>
                    <h6>Payments</h6>
                    @forelse($visit->invoice->payments as $payment)
                        <div class="border rounded p-2 mb-2">
                            <div>{{ $payment->receipt_number }} • {{ $payment->payment_method }}</div>
                            <strong>N{{ number_format($payment->amount, 2) }}</strong>
                            @can('refund payment')
                                <form class="mt-2" method="POST" action="{{ route('admin.cashier.refund', $payment) }}">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input class="form-control" type="number" step="0.01" name="amount" placeholder="Refund amount">
                                        <input class="form-control" name="reason" placeholder="Reason">
                                        <button class="btn btn-outline-danger" type="submit">Refund</button>
                                    </div>
                                </form>
                            @endcan
                        </div>
                    @empty
                        <p class="text-muted">No payments yet.</p>
                    @endforelse
                @else
                    <p class="text-muted">Generate an invoice to continue.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
