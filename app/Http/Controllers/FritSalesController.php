<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Company;
use App\Models\Stock;
use App\Models\Kiln;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Database\Eloquent\Builder;

class FritSalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $barcodes = Barcode::query()
                ->with(['stock', 'kiln', 'quantity', 'company'])
                ->whereIn('status', [Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_REJECTED, Barcode::STATUS_CUSTOMER_TRANSFER])
                ->select('barcodes.*')
                ->when($request->filled('stock_id'), function (Builder $query) use ($request) {
                    $stockIds = (array) $request->input('stock_id');
                    $query->whereIn('stock_id', $stockIds);
                })
                ->when($request->filled('barcode_id'), function (Builder $query) use ($request) {
                    $barcodeIds = (array) $request->input('barcode_id');
                    $query->whereIn('id', $barcodeIds);
                })
                ->when($request->filled('load_number'), function (Builder $query) use ($request) {
                    $loadNumbers = (array) $request->input('load_number');
                    $query->whereIn('load_number', $loadNumbers);
                })
                ->when($request->filled('status'), function (Builder $query) use ($request) {
                    $statuses = (array) $request->input('status');
                    $query->whereIn('status', $statuses);
                });

            return DataTables::of($barcodes)
                ->addColumn('checkbox', function ($barcode) {
                    return '<input type="checkbox" class="barcode-checkbox" value="' . $barcode->id . '">';
                })
                ->addColumn('stock_name', function ($barcode) {
                    return $barcode->stock->name;
                })
                ->addColumn('kiln_name', function ($barcode) {
                    return $barcode->kiln->name;
                })
                ->addColumn('quantity_kg', function ($barcode) {
                    return ($barcode->quantity->quantity ?? 0) . ' KG';
                })
                ->addColumn('status_label', function ($barcode) {
                    $statusClass = '';
                    switch($barcode->status) {
                        case Barcode::STATUS_SHIPMENT_APPROVED: $statusClass = 'status-shipment-approved'; break;
                        case Barcode::STATUS_REJECTED: $statusClass = 'status-rejected'; break;
                        case Barcode::STATUS_CUSTOMER_TRANSFER: $statusClass = 'status-customer-transfer'; break;
                    }
                    return '<span class="status-badge ' . $statusClass . '">' . Barcode::getStatusName($barcode->status) . '</span>';
                })
                ->rawColumns(['checkbox', 'status_label'])
                ->make(true);
        }

        $stocks = Stock::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        return view('admin.barcode.sales', compact('stocks', 'companies'));
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $barcodes = Barcode::query()
                ->with(['stock', 'quantity', 'company', 'deliveredBy'])
                ->whereIn('status', [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])
                ->select('barcodes.*')
                ->when($request->filled('stock_id'), function (Builder $query) use ($request) {
                    $stockIds = (array) $request->input('stock_id');
                    $query->whereIn('stock_id', $stockIds);
                })
                ->when($request->filled('barcode_id'), function (Builder $query) use ($request) {
                    $barcodeIds = (array) $request->input('barcode_id');
                    $query->whereIn('id', $barcodeIds);
                })
                ->when($request->filled('load_number'), function (Builder $query) use ($request) {
                    $loadNumbers = (array) $request->input('load_number');
                    $query->whereIn('load_number', $loadNumbers);
                })
                ->when($request->filled('company_id'), function (Builder $query) use ($request) {
                    $companyIds = (array) $request->input('company_id');
                    $query->whereIn('company_id', $companyIds);
                })
                ->when($request->filled('user_id'), function (Builder $query) use ($request) {
                    $userIds = (array) $request->input('user_id');
                    $query->whereIn('delivered_by', $userIds);
                })
                ->when($request->filled('start_date'), function (Builder $query) use ($request) {
                    $query->whereDate('delivered_at', '>=', Carbon::createFromFormat('d/m/Y', $request->input('start_date')));
                })
                ->when($request->filled('end_date'), function (Builder $query) use ($request) {
                    $query->whereDate('delivered_at', '<=', Carbon::createFromFormat('d/m/Y', $request->input('end_date')));
                });

            return DataTables::of($barcodes)
                ->addColumn('stock_name', function ($barcode) {
                    return $barcode->stock->name ?? '-';
                })
                ->addColumn('company_name', function ($barcode) {
                    return $barcode->company->name ?? '-';
                })
                ->addColumn('user_name', function ($barcode) {
                    return $barcode->deliveredBy->name ?? '-';
                })
                ->addColumn('quantity_kg', function ($barcode) {
                    return ($barcode->quantity->quantity ?? 0) . ' KG';
                })
                ->addColumn('status_label', function ($barcode) {
                    $statusClass = $barcode->status == Barcode::STATUS_DELIVERED ? 'status-delivered' : 'status-customer-transfer';
                    return '<span class="status-badge ' . $statusClass . '">' . Barcode::getStatusName($barcode->status) . '</span>';
                })
                ->rawColumns(['status_label'])
                ->make(true);
        }

        // Calculate Stats for KPI Cards
        $startDate = $request->get('start_date') ? Carbon::createFromFormat('d/m/Y', $request->get('start_date'))->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->get('end_date') ? Carbon::createFromFormat('d/m/Y', $request->get('end_date'))->endOfDay() : now()->endOfDay();

        $statsQuery = Barcode::query()
            ->whereIn('status', [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])
            ->whereBetween('delivered_at', [$startDate, $endDate]);

        $totalWeight = (clone $statsQuery)->join('quantities', 'barcodes.quantity_id', '=', 'quantities.id')->sum('quantities.quantity');
        $totalPallets = (clone $statsQuery)->count();

        $topCustomer = (clone $statsQuery)
            ->select('company_id', \DB::raw('count(*) as count'))
            ->groupBy('company_id')
            ->orderByDesc('count')
            ->with('company')
            ->first();

        $topProduct = (clone $statsQuery)
            ->select('stock_id', \DB::raw('count(*) as count'))
            ->groupBy('stock_id')
            ->orderByDesc('count')
            ->with('stock')
            ->first();

        $stocks = Stock::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.barcode.history', compact('stocks', 'companies', 'users', 'totalWeight', 'totalPallets', 'topCustomer', 'topProduct', 'startDate', 'endDate'));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate) {
            $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
        } else {
            $startDate = now()->subDays(30)->startOfDay();
        }

        if ($endDate) {
            $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();
        } else {
            $endDate = now()->endOfDay();
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\FritSalesHistoryExport($startDate, $endDate, $request->all()),
            'frit_satis_gecmisi_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode_ids' => 'required|array',
            'barcode_ids.*' => 'exists:barcodes,id',
            'company_id' => 'required|exists:companies,id',
            'status' => 'required|in:' . Barcode::STATUS_CUSTOMER_TRANSFER . ',' . Barcode::STATUS_DELIVERED,
        ]);

        $barcodeIds = $request->input('barcode_ids');
        $companyId = $request->input('company_id');
        $targetStatus = (int) $request->input('status');

        \DB::beginTransaction();
        try {
            foreach ($barcodeIds as $id) {
                $barcode = Barcode::findOrFail($id);
                
                // Önce firmayı ata
                $barcode->update(['company_id' => $companyId]);
                
                // Sonra statü geçişini yap (transitionTo model içinde company_transferred_at gibi alanları da doldurur)
                $barcode->transitionTo($targetStatus);
            }
            \DB::commit();
            return response()->json(['success' => true, 'message' => count($barcodeIds) . ' adet barkod başarıyla güncellendi.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Hata oluştu: ' . $e->getMessage()], 500);
        }
    }
}
