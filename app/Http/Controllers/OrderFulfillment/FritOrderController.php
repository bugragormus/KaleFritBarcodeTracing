<?php

namespace App\Http\Controllers\OrderFulfillment;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;

class FritOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('type', Order::TYPE_FRIT)
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

        $orders          = $query->get();
        $totalFritStock  = Order::getFritStock();
        $companies       = Company::orderBy('name')->get();

        return view('orders.frit.index', compact('orders', 'totalFritStock', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $stocks    = Stock::orderBy('code')->paginate(10); // sidebar pagination
        return view('orders.frit.create', compact('companies', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'          => 'required|exists:companies,id',
            'items'               => 'required|array|min:1',
            'items.*.quantity_kg' => 'required|numeric|min:0.01',
        ]);

        $order = Order::create([
            'type'         => Order::TYPE_FRIT,
            'company_id'   => $request->company_id,
            'company_type' => Order::TYPE_FRIT,
            'notes'        => $request->notes,
            'status'       => Order::STATUS_OPEN,
            'created_by'   => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            if (empty($item['quantity_kg'])) {
                continue;
            }
            OrderItem::create([
                'order_id'   => $order->id,
                'stock_code' => $item['stock_code'] ?? null,
                'quantity_kg' => $item['quantity_kg'],
            ]);
        }

        return redirect()->route('orders.frit.index')
            ->with('success', 'Frit siparişi oluşturuldu.');
    }

    public function show(Order $order)
    {
        if ($order->type !== Order::TYPE_FRIT) {
            abort(404);
        }
        $order->load(['items', 'createdBy']);
        $companies  = Company::orderBy('name')->get();
        $stocks     = Stock::orderBy('code')->get();
        $analysis     = $order->analyzeStock();
        $deleteRoute  = route('orders.frit.destroy', $order);
        return view('orders.show', compact('order', 'companies', 'stocks', 'analysis', 'deleteRoute'));
    }

    public function update(\Illuminate\Http\Request $request, Order $order)
    {
        if ($order->type !== Order::TYPE_FRIT) abort(403);

        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'status'     => 'required|in:open,fulfilled,partial,cancelled',
            'items'      => 'required|array|min:1',
            'items.*.quantity_kg' => 'required|numeric|min:0.01',
        ]);

        $order->update([
            'company_id' => $request->company_id,
            'status'     => $request->status,
            'notes'      => $request->notes,
        ]);

        // Kalemleri güncelle: tüm eskilerini sil, yenilerini ekle
        $order->items()->delete();
        foreach ($request->items as $item) {
            if (empty($item['quantity_kg'])) continue;
            \App\Models\OrderItem::create([
                'order_id'   => $order->id,
                'stock_code' => $item['stock_code'] ?? null,
                'quantity_kg' => $item['quantity_kg'],
            ]);
        }

        return redirect()->route('orders.frit.show', $order)
            ->with('success', 'Sipariş güncellendi.');
    }

    public function destroy(Order $order)
    {
        if ($order->type !== Order::TYPE_FRIT) {
            abort(403);
        }
        $order->delete();
        return redirect()->route('orders.frit.index')
            ->with('success', 'Sipariş başarıyla silindi.');
    }
}
