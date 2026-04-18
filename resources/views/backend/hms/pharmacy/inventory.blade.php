@extends('admin.admin_master')
@section('title', 'Drug Inventory')
@section('admin')
<div class="page-header">
    <h1>Drug Inventory</h1>
    <p>Track stock levels, expiry dates, batches, and reorder thresholds.</p>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title">Add Inventory Item</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pharmacy.inventory.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><input class="form-control" name="name" placeholder="Drug name"></div>
                <div class="col-md-4"><input class="form-control" name="sku" placeholder="SKU"></div>
                <div class="col-md-4"><input class="form-control" name="batch_number" placeholder="Batch number"></div>
                <div class="col-md-3"><input class="form-control" type="date" name="expiry_date"></div>
                <div class="col-md-3"><input class="form-control" type="number" name="stock_quantity" placeholder="Stock"></div>
                <div class="col-md-3"><input class="form-control" type="number" name="reorder_level" placeholder="Reorder level"></div>
                <div class="col-md-3"><input class="form-control" type="number" step="0.01" name="unit_price" placeholder="Unit price"></div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Save Item</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Name</th><th>SKU</th><th>Batch</th><th>Expiry</th><th>Stock</th><th>Reorder</th><th>Price</th></tr></thead>
                <tbody>
                    @forelse($inventory as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->batch_number ?: '-' }}</td>
                            <td>{{ $item->expiry_date?->format('d M Y') ?: '-' }}</td>
                            <td>{{ $item->stock_quantity }}</td>
                            <td>{{ $item->reorder_level }}</td>
                            <td>N{{ number_format($item->unit_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No inventory items found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $inventory->links() }}</div>
    </div>
</div>
@endsection
