@extends('admin.admin_master')
@section('title', 'Pharmacy Reports')
@section('admin')
<div class="page-header">
    <h1>Pharmacy Reports</h1>
    <p>Daily dispensing, OTC sales, stock alerts, and expiry monitoring.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Pending Prescriptions</h6><h3>{{ $stats['pending_prescriptions'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Dispensed Today</h6><h3>{{ $stats['dispensed_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Walk-in Sales Today</h6><h3>N{{ number_format($stats['walk_in_sales_today'], 2) }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Expiring in 30 Days</h6><h3>{{ $stats['expiring_items'] }}</h3></div></div>
</div>

<div class="card">
    <div class="card-header"><h5 class="card-title">Low Stock Items</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Name</th><th>SKU</th><th>Stock</th><th>Reorder</th><th>Expiry</th></tr></thead>
                <tbody>
                    @forelse($lowStockItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->stock_quantity }}</td>
                            <td>{{ $item->reorder_level }}</td>
                            <td>{{ $item->expiry_date?->format('d M Y') ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No low stock items at the moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
