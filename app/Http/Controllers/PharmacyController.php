<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\Prescription;
use App\Models\Visit;
use App\Models\WalkInSale;
use App\Services\VisitWorkflowService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function __construct(private readonly VisitWorkflowService $workflow)
    {
    }

    public function index()
    {
        $prescriptions = Prescription::with('visit.patient')
            ->where('status', 'pending')
            ->latest()
            ->get();
        $inventory = InventoryItem::where('category', 'drug')->latest()->take(10)->get();
        $purchaseOrders = PurchaseOrder::latest()->take(5)->get();
        $salesToday = WalkInSale::whereDate('sold_at', today())->sum('total_amount');

        return view('backend.hms.pharmacy.index', compact('prescriptions', 'inventory', 'purchaseOrders', 'salesToday'));
    }

    public function show(Visit $visit)
    {
        $visit->load('patient', 'prescriptions');
        $inventory = InventoryItem::where('category', 'drug')->latest()->get();

        return view('backend.hms.pharmacy.show', compact('visit', 'inventory'));
    }

    public function dispense(Request $request, Prescription $prescription)
    {
        $prescription->update([
            'status' => 'dispensed',
            'dispensed_by' => auth()->id(),
            'dispensed_at' => now(),
        ]);

        $visit = $prescription->visit_id ? Visit::find($prescription->visit_id) : null;
        if ($visit && $visit->current_stage === 'pharmacy') {
            $this->workflow->moveToStage($visit, 'nurse', 'awaiting_nurse', 'Medication dispensed');
        }

        return back()->with('message', 'Medication dispensed successfully.')->with('alert-type', 'success');
    }

    public function inventory()
    {
        $inventory = InventoryItem::where('category', 'drug')->latest()->paginate(20);

        return view('backend.hms.pharmacy.inventory', compact('inventory'));
    }

    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:inventory_items,sku',
            'batch_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        InventoryItem::create(array_merge($validated, [
            'category' => 'drug',
            'status' => 'active',
        ]));

        return back()->with('message', 'Drug inventory item created successfully.')->with('alert-type', 'success');
    }

    public function purchaseOrders()
    {
        $purchaseOrders = PurchaseOrder::with('items')->latest()->paginate(20);

        return view('backend.hms.pharmacy.purchase-orders', compact('purchaseOrders'));
    }

    public function storePurchaseOrder(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'po_number' => sprintf('PO-%s-%04d', now()->format('Ymd'), PurchaseOrder::count() + 1),
            'supplier_name' => $validated['supplier_name'],
            'status' => 'ordered',
            'created_by' => auth()->id(),
            'ordered_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $lineTotal = $item['quantity'] * $item['unit_cost'];
            $total += $lineTotal;
            $purchaseOrder->items()->create([
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'line_total' => $lineTotal,
            ]);
        }

        $purchaseOrder->update(['total_amount' => $total]);

        return back()->with('message', 'Purchase order created successfully.')->with('alert-type', 'success');
    }

    public function walkInSales()
    {
        $sales = WalkInSale::with('items')->latest()->paginate(20);
        $inventory = InventoryItem::where('category', 'drug')->latest()->get();

        return view('backend.hms.pharmacy.walk-in-sales', compact('sales', 'inventory'));
    }

    public function storeWalkInSale(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:Cash,POS,Transfer',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'nullable|exists:inventory_items,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $sale = WalkInSale::create([
            'sale_number' => sprintf('WIS-%s-%04d', now()->format('Ymd'), WalkInSale::count() + 1),
            'customer_name' => $validated['customer_name'] ?? null,
            'payment_method' => $validated['payment_method'],
            'sold_by' => auth()->id(),
            'sold_at' => now(),
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $lineTotal = $item['quantity'] * $item['unit_price'];
            $total += $lineTotal;

            $sale->items()->create([
                'inventory_item_id' => $item['inventory_item_id'] ?? null,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'line_total' => $lineTotal,
            ]);

            if (!empty($item['inventory_item_id'])) {
                InventoryItem::whereKey($item['inventory_item_id'])->decrement('stock_quantity', $item['quantity']);
            }
        }

        $sale->update(['total_amount' => $total]);

        return back()->with('message', 'Walk-in sale recorded successfully.')->with('alert-type', 'success');
    }

    public function reports()
    {
        $stats = [
            'pending_prescriptions' => Prescription::where('status', 'pending')->count(),
            'dispensed_today' => Prescription::whereDate('dispensed_at', today())->count(),
            'walk_in_sales_today' => WalkInSale::whereDate('sold_at', today())->sum('total_amount'),
            'low_stock_count' => InventoryItem::where('category', 'drug')->whereColumn('stock_quantity', '<=', 'reorder_level')->count(),
            'expiring_items' => InventoryItem::where('category', 'drug')->whereDate('expiry_date', '<=', now()->addDays(30))->count(),
        ];

        $lowStockItems = InventoryItem::where('category', 'drug')
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->latest()
            ->get();

        return view('backend.hms.pharmacy.reports', compact('stats', 'lowStockItems'));
    }
}
