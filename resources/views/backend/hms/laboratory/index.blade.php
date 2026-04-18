@extends('admin.admin_master')
@section('title', 'Laboratory Queue')
@section('admin')
<div class="page-header">
    <h1>Laboratory Module</h1>
    <p>Track samples, enter results, approve findings, and expose them back to doctors.</p>
</div>
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Lab Requests</h5></div>
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
                                    <td class="text-end"><a class="btn btn-sm btn-primary" href="{{ route('admin.laboratory.show', $order) }}">Open</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No laboratory requests.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Reagent Snapshot</h5></div>
            <div class="card-body">
                @forelse($reagents as $item)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>{{ $item->name }}</span>
                        <span>{{ $item->stock_quantity }}</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No reagent inventory yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
