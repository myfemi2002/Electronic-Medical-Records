@extends('admin.admin_master')
@section('title', 'Laboratory Reports')
@section('admin')
<div class="page-header">
    <h1>Laboratory Reports</h1>
    <p>Request volume, pending samples, processing load, and approved results.</p>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stats-card"><h6>Requests Today</h6><h3>{{ $stats['total_requests_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Processing</h6><h3>{{ $stats['processing'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Approved Today</h6><h3>{{ $stats['approved_today'] }}</h3></div></div>
    <div class="col-md-3"><div class="stats-card"><h6>Pending Collection</h6><h3>{{ $stats['pending_collection'] }}</h3></div></div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Patient</th><th>Request</th><th>Barcode</th><th>Status</th><th>Created</th></tr></thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>{{ $order->visit->patient->full_name }}</td>
                            <td>{{ $order->request_name }}</td>
                            <td>{{ $order->sample_barcode ?: 'Pending' }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No recent laboratory requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
