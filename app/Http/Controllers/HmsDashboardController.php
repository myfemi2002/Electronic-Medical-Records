<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Payment;
use App\Models\ServiceOrder;
use App\Models\TriageQueue;
use App\Models\Visit;

class HmsDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'visits_today' => Visit::whereDate('created_at', today())->count(),
            'waiting_queue' => TriageQueue::whereIn('status', ['waiting', 'in_progress'])->count(),
            'revenue_today' => Payment::whereDate('paid_at', today())->sum('amount'),
            'pending_orders' => ServiceOrder::whereIn('status', ['requested', 'processing'])->count(),
            'low_stock' => InventoryItem::whereColumn('stock_quantity', '<=', 'reorder_level')->count(),
        ];

        return view('admin.index', compact('stats'));
    }
}
