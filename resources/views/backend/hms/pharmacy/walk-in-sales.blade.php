@extends('admin.admin_master')
@section('title', 'Walk-in Sales')
@section('admin')
<div class="page-header">
    <h1>Walk-in Sales</h1>
    <p>Record OTC pharmacy sales for non-visit customers.</p>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="card-title">New Sale</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pharmacy.walk-in-sales.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><input class="form-control" name="customer_name" placeholder="Customer name"></div>
                <div class="col-md-4">
                    <select class="form-select" name="payment_method">
                        <option>Cash</option>
                        <option>POS</option>
                        <option>Transfer</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="items[0][inventory_item_id]">
                        <option value="">Select inventory item</option>
                        @foreach($inventory as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->stock_quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><input class="form-control" name="items[0][item_name]" placeholder="Item name"></div>
                <div class="col-md-4"><input class="form-control" type="number" name="items[0][quantity]" value="1" min="1"></div>
                <div class="col-md-4"><input class="form-control" type="number" step="0.01" name="items[0][unit_price]" placeholder="Unit price"></div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Record Sale</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Sale</th><th>Customer</th><th>Payment</th><th>Total</th><th>Time</th></tr></thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->sale_number }}</td>
                            <td>{{ $sale->customer_name ?: 'Walk-in' }}</td>
                            <td>{{ $sale->payment_method }}</td>
                            <td>N{{ number_format($sale->total_amount, 2) }}</td>
                            <td>{{ $sale->sold_at?->format('d M Y, h:i A') ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No walk-in sales recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $sales->links() }}</div>
    </div>
</div>
@endsection
