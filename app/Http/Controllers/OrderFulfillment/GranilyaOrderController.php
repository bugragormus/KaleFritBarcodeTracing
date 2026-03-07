<?php

namespace App\Http\Controllers\OrderFulfillment;

use App\Http\Controllers\Controller;
use App\Models\GranilyaCompany;
use App\Models\GranilyaProduction;
use App\Models\GranilyaSize;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;

class GranilyaOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('type', Order::TYPE_GRANILYA)
            ->with(['createdBy', 'items'])
            ->latest();

        // Filtreler
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders              = $query->get();
        $totalGranilyaStock  = Order::getGranilyaStock();
        $companies           = GranilyaCompany::where('is_active', true)->orderBy('name')->get();

        return view('orders.granilya.index', compact('orders', 'totalGranilyaStock', 'companies'));
    }

    public function create(Request $request)
    {
        $companies = GranilyaCompany::where('is_active', true)->orderBy('name')->get();
        $stocks    = Stock::orderBy('code')->get(); // Granilya için tüm frit kodları
        $sizes     = GranilyaSize::orderBy('name')->get();

        // Satışa hazır granilya stoğu, frit kodu x boyut matris formatında (sidebar için paginate)
        $granilyaStocks = GranilyaProduction::where('status', GranilyaProduction::STATUS_CUSTOMER_TRANSFER)
            ->with(['stock', 'size'])
            ->selectRaw('stock_id, size_id, SUM(used_quantity) as total_kg')
            ->groupBy('stock_id', 'size_id')
            ->orderBy('stock_id')
            ->paginate(12);

        return view('orders.granilya.create', compact('companies', 'stocks', 'sizes', 'granilyaStocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'               => 'required|exists:granilya_companies,id',
            'items'                    => 'required|array|min:1',
            'items.*.quantity_kg'      => 'required|numeric|min:0.01',
        ]);

        $order = Order::create([
            'type'         => Order::TYPE_GRANILYA,
            'company_id'   => $request->company_id,
            'company_type' => Order::TYPE_GRANILYA,
            'notes'        => $request->notes,
            'status'       => Order::STATUS_OPEN,
            'created_by'   => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            if (empty($item['quantity_kg'])) {
                continue;
            }
            OrderItem::create([
                'order_id'      => $order->id,
                'stock_code'    => $item['stock_code'] ?? null,
                'granilya_size' => $item['granilya_size'] ?? null,
                'quantity_kg'   => $item['quantity_kg'],
            ]);
        }

        return redirect()->route('orders.granilya.index')
            ->with('success', 'Granilya siparişi oluşturuldu.');
    }

    public function show(Order $order)
    {
        if ($order->type !== Order::TYPE_GRANILYA) abort(404);
        $order->load(['items', 'createdBy']);
        $companies = GranilyaCompany::where('is_active', true)->orderBy('name')->get();
        $stocks    = Stock::orderBy('code')->get();
        $sizes     = GranilyaSize::orderBy('name')->get();
        $analysis  = $order->analyzeStock();
        $deleteRoute = route('orders.granilya.destroy', $order);
        return view('orders.show', compact('order', 'companies', 'stocks', 'sizes', 'analysis', 'deleteRoute'));
    }

    public function update(\Illuminate\Http\Request $request, Order $order)
    {
        if ($order->type !== Order::TYPE_GRANILYA) abort(403);

        $request->validate([
            'company_id' => 'required|exists:granilya_companies,id',
            'status'     => 'required|in:open,fulfilled,partial,cancelled',
            'items'      => 'required|array|min:1',
            'items.*.quantity_kg' => 'required|numeric|min:0.01',
        ]);

        $order->update([
            'company_id' => $request->company_id,
            'status'     => $request->status,
            'notes'      => $request->notes,
        ]);

        $order->items()->delete();
        foreach ($request->items as $item) {
            if (empty($item['quantity_kg'])) continue;
            \App\Models\OrderItem::create([
                'order_id'      => $order->id,
                'stock_code'    => $item['stock_code'] ?? null,
                'granilya_size' => $item['granilya_size'] ?? null,
                'quantity_kg'   => $item['quantity_kg'],
            ]);
        }

        return redirect()->route('orders.granilya.show', $order)
            ->with('success', 'Sipariş güncellendi.');
    }

    public function destroy(Order $order)
    {
        if ($order->type !== Order::TYPE_GRANILYA) {
            abort(403);
        }
        $order->delete();
        return redirect()->route('orders.granilya.index')
            ->with('success', 'Sipariş başarıyla silindi.');
    }
}
