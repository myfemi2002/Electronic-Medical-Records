@extends('admin.admin_master')
@section('title', 'Radiology Queue')
@section('admin')
<div class="page-header">
    <h1>Radiology Module</h1>
    <p>Manage imaging requests, upload scan references, record radiologist reports, and compare studies.</p>
</div>
<div class="card">
    <div class="card-header"><h5 class="card-title">Imaging Requests</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Patient</th><th>Request</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->visit->patient->full_name }}</td>
                            <td>{{ $order->request_name }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('admin.radiology.show', $order) }}">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">No imaging requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
