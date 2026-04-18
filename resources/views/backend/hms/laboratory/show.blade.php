@extends('admin.admin_master')
@section('title', 'Laboratory Request')
@section('admin')
<div class="page-header">
    <h1>Laboratory Request</h1>
    <p>{{ $order->visit->patient->full_name }} • {{ $order->request_name }}</p>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.laboratory.result', $order) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Sample Barcode</label><input class="form-control" name="sample_barcode" value="{{ old('sample_barcode', $order->sample_barcode) }}"></div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="approve_now" value="1" id="approve_now">
                        <label class="form-check-label" for="approve_now">Approve immediately</label>
                    </div>
                </div>
                <div class="col-12"><label class="form-label">Result</label><textarea class="form-control" rows="8" name="result_text">{{ old('result_text', $order->result_text) }}</textarea></div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Save Result</button>
        </form>
    </div>
</div>
@endsection
