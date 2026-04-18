@extends('admin.admin_master')
@section('title', 'Radiology Request')
@section('admin')
<div class="page-header">
    <h1>Radiology Request</h1>
    <p>{{ $order->visit->patient->full_name }} • {{ $order->request_name }}</p>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.radiology.report', $order) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Template</label><input class="form-control" name="template_name" value="{{ old('template_name', $order->template_name) }}"></div>
                <div class="col-md-6"><label class="form-label">Image Path / Upload Reference</label><input class="form-control" name="image_path" value="{{ old('image_path', $order->image_path) }}"></div>
                <div class="col-md-6"><label class="form-label">Comparison Notes</label><textarea class="form-control" rows="3" name="comparison_notes">{{ old('comparison_notes', $order->comparison_notes) }}</textarea></div>
                <div class="col-md-6">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="approve_now" value="1" id="rad_approve_now">
                        <label class="form-check-label" for="rad_approve_now">Approve immediately</label>
                    </div>
                </div>
                <div class="col-12"><label class="form-label">Radiologist Report</label><textarea class="form-control" rows="8" name="report_text">{{ old('report_text', $order->report_text) }}</textarea></div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Save Report</button>
        </form>
    </div>
</div>
@endsection
