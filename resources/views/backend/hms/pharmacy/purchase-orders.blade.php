@extends('admin.admin_master')
@section('title', 'Purchase Orders')
@section('admin')
<div class="page-header">
    <h1>Purchase Orders</h1>
    <p>Create stock replenishment orders for pharmacy procurement.</p>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title">New Purchase Order</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pharmacy.purchase-orders.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><input class="form-control" name="supplier_name" placeholder="Supplier name"></div>
                <div class="col-md-6"><input class="form-control" name="items[0][item_name]" placeholder="Item name"></div>
                <div class="col-md-3"><input class="form-control" type="number" name="items[0][quantity]" value="1" min="1"></div>
                <div class="col-md-3"><input class="form-control" type="number" step="0.01" name="items[0][unit_cost]" placeholder="Unit cost"></div>
                <div class="col-md-6"><textarea class="form-control" rows="2" name="notes" placeholder="Notes"></textarea></div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Create Purchase Order</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>PO Number</th><th>Supplier</th><th>Status</th><th>Total</th><th>Ordered</th></tr></thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->supplier_name }}</td>
                            <td>{{ ucfirst($po->status) }}</td>
                            <td>N{{ number_format($po->total_amount, 2) }}</td>
                            <td>{{ $po->ordered_at?->format('d M Y, h:i A') ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No purchase orders available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $purchaseOrders->links() }}</div>
    </div>
</div>
@endsection
