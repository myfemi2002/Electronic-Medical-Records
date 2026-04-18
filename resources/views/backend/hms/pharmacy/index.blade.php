@extends('admin.admin_master')
@section('title', 'Pharmacy Queue')
@section('admin')
<div class="page-header">
    <h1>Pharmacy Module</h1>
    <p>Verify prescriptions, monitor inventory, and dispense only after doctor workflow reaches pharmacy.</p>
</div>
<div class="d-flex gap-2 mb-4">
    <a class="btn btn-outline-primary" href="{{ route('admin.pharmacy.inventory') }}">Inventory</a>
    <a class="btn btn-outline-primary" href="{{ route('admin.pharmacy.purchase-orders') }}">Purchase Orders</a>
    <a class="btn btn-outline-primary" href="{{ route('admin.pharmacy.walk-in-sales') }}">Walk-in Sales</a>
    <a class="btn btn-outline-primary" href="{{ route('admin.pharmacy.reports') }}">Reports</a>
</div>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Pending Prescriptions</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Patient</th><th>Drug</th><th>Instructions</th><th></th></tr></thead>
                        <tbody>
                            @forelse($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->visit->patient->full_name }}</td>
                                    <td>{{ $prescription->drug_name }}</td>
                                    <td>{{ $prescription->dosage }} / {{ $prescription->frequency }} / {{ $prescription->duration }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.pharmacy.dispense', $prescription) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit">Dispense</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No pending prescriptions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Drug Inventory Snapshot</h5></div>
            <div class="card-body">
                <p><strong>Walk-in Sales Today:</strong> N{{ number_format($salesToday, 2) }}</p>
                @forelse($inventory as $item)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $item->name }}</span>
                        <span>{{ $item->stock_quantity }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No drug stock records yet.</p>
                @endforelse
                <hr>
                <h6>Recent Purchase Orders</h6>
                @forelse($purchaseOrders as $po)
                    <div class="small border-bottom py-2">{{ $po->po_number }} • {{ $po->supplier_name }}</div>
                @empty
                    <p class="text-muted mb-0">No recent purchase orders.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
