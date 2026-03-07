<?php

namespace App\Http\Controllers\OrderFulfillment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class BulkOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['createdBy', 'items'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('company_ids')) {
            $query->whereIn('company_id', $request->company_ids);
        }
        if ($request->filled('stock_codes')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->whereIn('stock_code', $request->stock_codes);
            });
        }
        if ($request->filled('granilya_sizes')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->whereIn('granilya_size', $request->granilya_sizes);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(50);
        
        $companies = \App\Models\Company::orderBy('name')->get();
        $granilyaCompanies = \App\Models\GranilyaCompany::orderBy('name')->get();
        $stocks = \App\Models\Stock::orderBy('code')->get();
        $sizes = \App\Models\GranilyaSize::orderBy('name')->get();

        return view('orders.bulk_management', compact('orders', 'companies', 'granilyaCompanies', 'stocks', 'sizes'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:open,fulfilled,cancelled',
        ]);

        Order::whereIn('id', $request->order_ids)->update(['status' => $request->status]);

        return redirect()->back()->with('success', count($request->order_ids) . ' adet sipariş durumu güncellendi.');
    }
}
