<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\Permission;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        // Tarih filtreleme parametreleri
        $startDate = request('start_date');
        $endDate = request('end_date');
        $period = request('period');
        
        // Period parametresine göre varsayılan tarihleri ayarla
        if (!$startDate && !$endDate && $period) {
            switch ($period) {
                case 'monthly':
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
                case 'quarterly':
                    $startDate = now()->startOfQuarter()->format('Y-m-d');
                    $endDate = now()->endOfQuarter()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->endOfYear()->format('Y-m-d');
                    break;
                case 'all':
                    // Tüm zamanlar için tarih filtresi yok
                    break;
                default:
                    // Günlük - bugün
                    $startDate = now()->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak bugün
            $startDate = now()->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $companies = Company::withCount([
            'barcodes' => function($query) use ($startDate, $endDate) {
                if ($startDate) { $query->where('created_at', '>=', $startDate); }
                if ($endDate) { $query->where('created_at', '<=', $endDate . ' 23:59:59'); }
            },
            'barcodes as total_quantity' => function($query) use ($startDate, $endDate) {
                $query->select(\DB::raw('SUM(quantity_id)'));
                if ($startDate) { $query->where('created_at', '>=', $startDate); }
                if ($endDate) { $query->where('created_at', '<=', $endDate . ' 23:59:59'); }
            },
            'barcodes as customer_transfer_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER);
                if ($startDate) { $query->where('created_at', '>=', $startDate); }
                if ($endDate) { $query->where('created_at', '<=', $endDate . ' 23:59:59'); }
            },
            'barcodes as delivered_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_DELIVERED);
                if ($startDate) { $query->where('created_at', '>=', $startDate); }
                if ($endDate) { $query->where('created_at', '<=', $endDate . ' 23:59:59'); }
            }
        ])->get();

        // Her firma için detaylı istatistikler
        foreach ($companies as $company) {
            $barcodesQuery = $company->barcodes();
            if ($startDate) { $barcodesQuery->where('created_at', '>=', $startDate); }
            if ($endDate) { $barcodesQuery->where('created_at', '<=', $endDate . ' 23:59:59'); }
            
            // Toplam alım miktarı (sadece müşteri transfer ve teslim edildi)
            $company->total_purchase = $barcodesQuery->sum('quantity_id');
            
            // Son 30 günlük alım
            $last30DaysQuery = $company->barcodes();
            if ($startDate) { $last30DaysQuery->where('created_at', '>=', $startDate); }
            if ($endDate) { $last30DaysQuery->where('created_at', '<=', $endDate . ' 23:59:59'); }
            $company->last_30_days_purchase = $last30DaysQuery
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('quantity_id');
            
            // Teslim oranı (teslim edildi / toplam)
            $totalBarcodes = $barcodesQuery->count();
            $deliveredBarcodes = $barcodesQuery->where('status', \App\Models\Barcode::STATUS_DELIVERED)->count();
            $company->delivery_rate = $totalBarcodes > 0 ? round(($deliveredBarcodes / $totalBarcodes) * 100, 2) : 0;
            
            // Son alım tarihi
            $company->last_purchase_date = $barcodesQuery->latest('created_at')->value('created_at');
            
            // Ortalama sipariş büyüklüğü
            $company->average_order_size = $totalBarcodes > 0 ? round($company->total_purchase / $totalBarcodes, 0) : 0;
            
            // Aktiflik skoru (son 90 gün içinde alım yapıp yapmadığı)
            $recentActivity = $barcodesQuery
                ->where('created_at', '>=', now()->subDays(90))
                ->count();
            $company->is_active = $recentActivity > 0;
        }

        return view('admin.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Company\CompanyStoreRequest $request
     * @return string
     */
    public function store(CompanyStoreRequest $request)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();

        $company = Company::create($data);

        if (!$company) {
            toastr()->error('Firma oluşturulamadı.');
        }

        toastr()->success('Firma başarıyla oluşturuldu.');

        return redirect()->route('company.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $company = Company::findOrFail($id);

        return view('admin.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Company\CompanyUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(CompanyUpdateRequest $request, $id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();

        $company = Company::findOrFail($id);

        $company->update($data);

        if (!$company) {
            toastr()->error('Firma düzenlenemedi.');
        }

        toastr()->success('Firma başarıyla düzenlendi.');

        return redirect()->route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $company = Company::findOrFail($id);

        $company->delete();

        if (!$company) {
            toastr()->error('Firma silinemedi.');
        }

        return response()->json(['message' => 'Firma silindi!']);
    }

    /**
     * Show detailed analysis for a specific company.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function analysis($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        // Tarih filtreleme parametreleri
        $startDate = request('start_date');
        $endDate = request('end_date');
        $period = request('period');
        
        // Period parametresine göre varsayılan tarihleri ayarla
        if (!$startDate && !$endDate && $period) {
            switch ($period) {
                case 'monthly':
                    $startDate = now()->startOfMonth()->format('Y-m-d');
                    $endDate = now()->endOfMonth()->format('Y-m-d');
                    break;
                case 'quarterly':
                    $startDate = now()->startOfQuarter()->format('Y-m-d');
                    $endDate = now()->endOfQuarter()->format('Y-m-d');
                    break;
                case 'yearly':
                    $startDate = now()->startOfYear()->format('Y-m-d');
                    $endDate = now()->endOfYear()->format('Y-m-d');
                    break;
                case 'all':
                    // Tüm zamanlar için tarih filtresi yok
                    break;
                default:
                    // Günlük - bugün
                    $startDate = now()->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak bugün
            $startDate = now()->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $company = Company::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->with(['stock', 'kiln', 'warehouse']);
            
            // Tarih filtreleme
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('created_at', '<=', $endDate . ' 23:59:59');
            }
        }])->findOrFail($id);

        // Detaylı istatistikler
        $company->total_purchase = $company->barcodes->sum('quantity_id');
        $company->total_barcodes = $company->barcodes->count();
        
        // Durum bazında dağılım
        $statusDistribution = $company->barcodes->groupBy('status')->map->count();
        
        // Aylık alım trendi (son 12 ay)
        $monthlyPurchase = $company->barcodes()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(quantity_id) as total_quantity, COUNT(*) as total_barcodes')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // En çok alınan stoklar
        $topStocks = $company->barcodes()
            ->with('stock')
            ->selectRaw('stock_id, SUM(quantity_id) as total_quantity, COUNT(*) as total_barcodes')
            ->groupBy('stock_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // En çok çalışılan fırınlar
        $topKilns = $company->barcodes()
            ->with('kiln')
            ->selectRaw('kiln_id, SUM(quantity_id) as total_quantity, COUNT(*) as total_barcodes')
            ->whereNotNull('kiln_id')
            ->groupBy('kiln_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return view('admin.company.analysis', compact('company', 'statusDistribution', 'monthlyPurchase', 'topStocks', 'topKilns'));
    }

    /**
     * Download Excel report for a specific company.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadReport($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $company = Company::with(['barcodes' => function($query) {
            $query->with(['stock', 'kiln', 'warehouse']);
        }])->findOrFail($id);

        // Excel export için veri hazırlama
        $data = [];
        foreach ($company->barcodes as $barcode) {
            $data[] = [
                'Barkod ID' => $barcode->id,
                'Stok Adı' => $barcode->stock ? $barcode->stock->name : '-',
                'Miktar (Ton)' => $barcode->quantity_id,
                'Durum' => \App\Models\Barcode::STATUSES[$barcode->status] ?? 'Bilinmiyor',
                'Fırın' => $barcode->kiln ? $barcode->kiln->name : '-',
                'Depo' => $barcode->warehouse ? $barcode->warehouse->name : '-',
                'Oluşturulma Tarihi' => $barcode->created_at ? $barcode->created_at->format('d.m.Y H:i') : '-',
                'Müşteri Transfer Tarihi' => $barcode->company_transferred_at ? \Carbon\Carbon::parse($barcode->company_transferred_at)->format('d.m.Y H:i') : '-',
                'Teslim Tarihi' => $barcode->delivered_at ? \Carbon\Carbon::parse($barcode->delivered_at)->format('d.m.Y H:i') : '-',
            ];
        }

        $filename = 'Firma_Raporu_' . $company->name . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return \Excel::download(new \App\Exports\CompanyReportExport($data), $filename);
    }
}
