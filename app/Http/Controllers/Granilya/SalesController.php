<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GranilyaProduction;
use App\Models\GranilyaCompany;
use App\Models\GranilyaProductionHistory;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the sales screen with grouped pallets ready for sale.
     */
    public function index(Request $request)
    {
        // Query for pallets in "Customer Transfer" status
        $query = GranilyaProduction::with(['stock', 'size'])
            ->where('status', GranilyaProduction::STATUS_CUSTOMER_TRANSFER);

        // Filters
        if ($request->filled('pallet_no')) {
            $palletNos = array_filter(array_map('trim', explode(',', $request->pallet_no)));
            $query->where(function($q) use ($palletNos) {
                foreach ($palletNos as $no) {
                    $q->orWhere('pallet_number', 'LIKE', $no . '-%')
                      ->orWhere('pallet_number', $no);
                }
            });
        }

        if ($request->filled('stock_id')) {
            $stockIds = is_array($request->stock_id) ? $request->stock_id : [$request->stock_id];
            $query->whereIn('stock_id', $stockIds);
        }

        if ($request->filled('load_number')) {
            $loadNumbers = array_filter(array_map('trim', explode(',', $request->load_number)));
            $query->whereIn('load_number', $loadNumbers);
        }

        // Group by base_pallet_number
        $readyPallets = $query->get()
            ->groupBy(function($item) {
                return $item->base_pallet_number;
            });

        // Fetch active companies for the customer dropdown
        $companies = GranilyaCompany::where('is_active', true)->orderBy('name')->get();
        // Fetch stocks for filter
        $stocks = \App\Models\Stock::all();

        return view('granilya.sales.index', compact('readyPallets', 'companies', 'stocks'));
    }

    /**
     * Process the sale of selected pallet groups.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Satış işlemi tetiklendi', $request->all());

        $request->validate([
            'base_pallet_numbers' => 'required|array',
            'base_pallet_numbers.*' => 'string',
            'company_id' => 'required|exists:granilya_companies,id',
        ]);

        $basePalletNumbers = $request->base_pallet_numbers;
        $companyId = $request->company_id;
        $company = GranilyaCompany::find($companyId);

        DB::beginTransaction();
        try {
            $processedCount = 0;
            \Illuminate\Support\Facades\Log::info('Satış döngüsü başlıyor', ['basePalletNumbers' => $basePalletNumbers]);
            foreach ($basePalletNumbers as $baseNo) {
                // Find all active pallets in this group that are ready for transfer
                $pallets = GranilyaProduction::where(function($q) use ($baseNo) {
                        $q->where('pallet_number', 'LIKE', $baseNo . '-%')
                          ->orWhere('pallet_number', $baseNo);
                    })
                    ->where('status', GranilyaProduction::STATUS_CUSTOMER_TRANSFER)
                    ->get();

                foreach ($pallets as $pallet) {
                    $pallet->update([
                        'status' => GranilyaProduction::STATUS_DELIVERED,
                        'delivery_company_id' => $companyId,
                        'delivered_at' => now(),
                        'user_id' => auth()->id(),
                    ]);

                    GranilyaProductionHistory::create([
                        'production_id' => $pallet->id,
                        'status' => GranilyaProduction::STATUS_DELIVERED,
                        'user_id' => auth()->id(),
                        'description' => "Satış gerçekleştirildi. Müşteri: {$company->name}",
                    ]);
                    $processedCount++;
                }
            }

            if ($processedCount === 0) {
                \Illuminate\Support\Facades\Log::warning('Hiçbir palet işlenemedi (Durum 9 olan kayıt bulunamadı)');
                throw new \Exception('Seçilen palet grupları (veya içindeki çuvallar) "Müşteri Transfer" durumunda bulunamadı. Lütfen sayfayı yenileyip tekrar deneyin.');
            }

            DB::commit();
            \Illuminate\Support\Facades\Log::info('Satış başarıyla tamamlandı', ['count' => $processedCount]);
            
            return redirect()->route('granilya.sales')->with('success', $processedCount . ' adet üretim (palet grubu/çuvalları) başarıyla satıldı ve teslim edildi.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Satış hatası: ' . $e->getMessage());
            return back()->with('error', 'Satış işlemi sırasında bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Satış geçmişini görüntüler.
     */
    public function history(Request $request)
    {
        $startDate = $request->date_start ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->date_end ?? now()->format('Y-m-d');

        $query = GranilyaProduction::where('status', GranilyaProduction::STATUS_DELIVERED)
            ->with(['stock', 'deliveryCompany', 'user', 'size']);

        // Filtreler
        if ($request->pallet_no) {
            $query->where('pallet_number', 'LIKE', '%' . $request->pallet_no . '%');
        }

        if ($request->filled('stock_id')) {
            $query->whereIn('stock_id', (array)$request->stock_id);
        }

        if ($request->company_id) {
            $query->where('delivery_company_id', $request->company_id);
        }
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $query->whereDate('delivered_at', '>=', $startDate)
              ->whereDate('delivered_at', '<=', $endDate);

        // KPI Calculations
        $allResults = (clone $query)->get();
        $stats = [
            'total_weight' => $allResults->sum('used_quantity'),
            'total_sales'  => $allResults->count(),
            'top_customer' => $allResults->groupBy('delivery_company_id')->map->count()->sortDesc()->keys()->first(),
            'top_product'  => $allResults->groupBy('stock_id')->map->count()->sortDesc()->keys()->first(),
        ];

        $stats['top_customer_name'] = $stats['top_customer'] ? GranilyaCompany::find($stats['top_customer'])->name : '-';
        $stats['top_product_name'] = $stats['top_product'] ? \App\Models\Stock::find($stats['top_product'])->name : '-';

        $sales = $query->orderBy('delivered_at', 'desc')->paginate(50);

        $stocks = \App\Models\Stock::all();
        $companies = GranilyaCompany::all();
        $users = \App\Models\User::all();

        return view('granilya.sales.history', compact('sales', 'stocks', 'companies', 'users', 'stats', 'startDate', 'endDate'));
    }

    /**
     * Export sales history to Excel.
     */
    public function export(Request $request)
    {
        $startDate = $request->date_start ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $request->date_end ?? now()->format('Y-m-d');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\Granilya\SalesHistoryExport($request->all()), 
            'granilya_satis_gecmisi_' . date('Ymd_His') . '.xlsx'
        );
    }
}
