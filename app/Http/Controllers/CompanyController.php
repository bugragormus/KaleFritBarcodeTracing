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
                    // Günlük - son 7 gün
                    $startDate = now()->subDays(7)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak son 7 gün
            $startDate = now()->subDays(7)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $companies = Company::withCount([
            // Toplam sipariş sayısı: oluşturulma tarihine göre
            'barcodes' => function($query) use ($startDate, $endDate) {
                if ($startDate) { $query->where('created_at', '>=', $startDate); }
                if ($endDate) { $query->where('created_at', '<=', $endDate . ' 23:59:59'); }
            },
            // Müşteri transfer sayısı: company_transferred_at tarihine göre
            'barcodes as customer_transfer_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER);
                if ($startDate) { $query->where('company_transferred_at', '>=', $startDate); }
                if ($endDate) { $query->where('company_transferred_at', '<=', $endDate . ' 23:59:59'); }
            },
            // Teslim sayısı: delivered_at tarihine göre
            'barcodes as delivered_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_DELIVERED);
                if ($startDate) { $query->where('delivered_at', '>=', $startDate); }
                if ($endDate) { $query->where('delivered_at', '<=', $endDate . ' 23:59:59'); }
            }
        ]);

        // Firma adına göre arama
        $searchName = request('name');
        if ($searchName) {
            $companies = $companies->where('name', 'like', '%' . $searchName . '%');
        }

        $companies = $companies->get();

        // Her firma için detaylı istatistikler
        foreach ($companies as $company) {
            // Toplam alım miktarı (KG) - müşteri transfer ve teslim edilenler
            $totalTransfer = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER)
                ->when($startDate, function($q) use ($startDate) { return $q->where('company_transferred_at', '>=', $startDate); })
                ->when($endDate, function($q) use ($endDate) { return $q->where('company_transferred_at', '<=', $endDate . ' 23:59:59'); })
                ->with('quantity')
                ->get()
                ->sum(function($barcode) { return $barcode->quantity ? $barcode->quantity->quantity : 0; });
            $totalDelivered = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                ->when($startDate, function($q) use ($startDate) { return $q->where('delivered_at', '>=', $startDate); })
                ->when($endDate, function($q) use ($endDate) { return $q->where('delivered_at', '<=', $endDate . ' 23:59:59'); })
                ->with('quantity')
                ->get()
                ->sum(function($barcode) { return $barcode->quantity ? $barcode->quantity->quantity : 0; });
            $company->total_purchase = $totalTransfer + $totalDelivered;
            
            // Son 30 günlük alım (KG) - tarih filtresinden bağımsız
            $company->last_30_days_purchase = (
                $company->barcodes()
                    ->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER)
                    ->where('company_transferred_at', '>=', now()->subDays(30))
                    ->with('quantity')
                    ->get()
                    ->sum(function($barcode) { return $barcode->quantity ? $barcode->quantity->quantity : 0; })
            ) + (
                $company->barcodes()
                    ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                    ->where('delivered_at', '>=', now()->subDays(30))
                    ->with('quantity')
                    ->get()
                    ->sum(function($barcode) { return $barcode->quantity ? $barcode->quantity->quantity : 0; })
            );
            
            // Durum bazında KG miktarları
            $company->customer_transfer_kg = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER)
                ->when($startDate, function($query) use ($startDate) { return $query->where('company_transferred_at', '>=', $startDate); })
                ->when($endDate, function($query) use ($endDate) { return $query->where('company_transferred_at', '<=', $endDate . ' 23:59:59'); })
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
            
            $company->delivered_kg = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                ->when($startDate, function($query) use ($startDate) { return $query->where('delivered_at', '>=', $startDate); })
                ->when($endDate, function($query) use ($endDate) { return $query->where('delivered_at', '<=', $endDate . ' 23:59:59'); })
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
            
            // Teslim oranı (teslim edildi / toplam müşteri transfer + teslim edildi) - ilgili tarih alanlarına göre
            $totalRelevantBarcodes = (
                $company->barcodes()
                    ->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER)
                    ->when($startDate, function($q) use ($startDate) { return $q->where('company_transferred_at', '>=', $startDate); })
                    ->when($endDate, function($q) use ($endDate) { return $q->where('company_transferred_at', '<=', $endDate . ' 23:59:59'); })
                    ->count()
            ) + (
                $company->barcodes()
                    ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                    ->when($startDate, function($q) use ($startDate) { return $q->where('delivered_at', '>=', $startDate); })
                    ->when($endDate, function($q) use ($endDate) { return $q->where('delivered_at', '<=', $endDate . ' 23:59:59'); })
                    ->count()
            );
            $deliveredBarcodes = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                ->when($startDate, function($q) use ($startDate) { return $q->where('delivered_at', '>=', $startDate); })
                ->when($endDate, function($q) use ($endDate) { return $q->where('delivered_at', '<=', $endDate . ' 23:59:59'); })
                ->count();
            $company->delivery_rate = $totalRelevantBarcodes > 0 ? round(($deliveredBarcodes / $totalRelevantBarcodes) * 100, 2) : 0;
            
            // Son alım tarihi: company_transferred_at ve delivered_at tarihlerinin maksimumu
            $lastTransfer = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER)
                ->orderByDesc('company_transferred_at')
                ->value('company_transferred_at');
            $lastDelivered = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                ->orderByDesc('delivered_at')
                ->value('delivered_at');
            $company->last_purchase_date = max($lastTransfer ?? '1970-01-01', $lastDelivered ?? '1970-01-01');
            
            // Ortalama sipariş büyüklüğü (KG)
            $company->average_order_size = $totalRelevantBarcodes > 0 ? round($company->total_purchase / $totalRelevantBarcodes, 0) : 0;
            
            // Aktiflik skoru (son 90 gün içinde alım yapıp yapmadığı)
            $recentActivity = $company->barcodes()
                ->where('created_at', '>=', now()->subDays(90))
                ->whereIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED
                ])
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

        try {
            $company = Company::findOrFail($id);

            // Check if company can be deleted
            if ($this->canCompanyBeDeleted($company)) {
                $company->delete();
                return response()->json(['success' => true, 'message' => 'Firma başarıyla silindi!']);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Bu firma silinemez çünkü sistemde aktif olarak kullanılmaktadır. İlişkili kayıtlar silinene kadar bu firma silinemez.'
                ], 422);
            }
        } catch (\Exception $e) {
            \Log::error('Company deletion error: ' . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Bu firma silinemez çünkü sistemde aktif olarak kullanılmaktadır. İlişkili kayıtlar silinene kadar bu firma silinemez.'
                ], 422);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Firma silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if company can be safely deleted
     */
    private function canCompanyBeDeleted($company)
    {
        // Check if company has any related barcodes
        return !$company->barcodes()->exists();
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
                    // Günlük - son 7 gün
                    $startDate = now()->subDays(7)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak son 7 gün
            $startDate = now()->subDays(7)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $company = Company::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->with(['stock', 'kiln', 'warehouse', 'quantity']);

            // Tarih filtreleme: teslim ve transfer olayları kendi tarih alanlarına göre, diğerleri created_at
            if ($startDate || $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    // Customer transfer
                    $q->where(function($q2) use ($startDate, $endDate) {
                        $q2->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER);
                        if ($startDate) { $q2->where('company_transferred_at', '>=', $startDate); }
                        if ($endDate) { $q2->where('company_transferred_at', '<=', $endDate . ' 23:59:59'); }
                    })
                    // Delivered
                    ->orWhere(function($q3) use ($startDate, $endDate) {
                        $q3->where('status', \App\Models\Barcode::STATUS_DELIVERED);
                        if ($startDate) { $q3->where('delivered_at', '>=', $startDate); }
                        if ($endDate) { $q3->where('delivered_at', '<=', $endDate . ' 23:59:59'); }
                    })
                    // Other statuses by created_at
                    ->orWhere(function($q4) use ($startDate, $endDate) {
                        $q4->whereNotIn('status', [\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER, \App\Models\Barcode::STATUS_DELIVERED]);
                        if ($startDate) { $q4->where('created_at', '>=', $startDate); }
                        if ($endDate) { $q4->where('created_at', '<=', $endDate . ' 23:59:59'); }
                    });
                });
            }
        }])->findOrFail($id);

        // Detaylı istatistikler
        $company->total_purchase = $company->barcodes->sum(function($barcode) {
            return $barcode->quantity ? $barcode->quantity->quantity : 0;
        });
        $company->total_barcodes = $company->barcodes->count();
        
        // Durum bazında dağılım
        $statusDistribution = $company->barcodes->groupBy('status')->map->count();
        
        // Aylık alım trendi (son 12 ay) - tarih filtresinden bağımsız, tüm veriler
        $monthlyPurchase = $company->barcodes()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total_barcodes')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // KG değerlerini ayrıca hesapla - tüm veriler
        foreach ($monthlyPurchase as $month) {
            $month->total_quantity = $company->barcodes()
                ->whereRaw('MONTH(created_at) = ? AND YEAR(created_at) = ?', [$month->month, $month->year])
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
        }

        // En çok alınan stoklar (pagination ile) - tarih filtresinden bağımsız, tüm veriler
        $perPage = 10;
        $topStocksQuery = $company->barcodes()
            ->with('stock')
            ->selectRaw('stock_id, COUNT(*) as total_barcodes')
            ->groupBy('stock_id')
            ->orderByDesc('total_barcodes');
        
        $topStocksTotal = $topStocksQuery->get()->count();
        $topStocks = $topStocksQuery->skip((request('stocks_page', 1) - 1) * $perPage)->take($perPage)->get();
        
        // KG değerlerini ayrıca hesapla - tüm veriler
        foreach ($topStocks as $stock) {
            $stock->total_quantity = $company->barcodes()
                ->where('stock_id', $stock->stock_id)
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
        }
        
        $topStocksPagination = [
            'data' => $topStocks,
            'total' => $topStocksTotal,
            'per_page' => $perPage,
            'current_page' => request('stocks_page', 1),
            'last_page' => ceil($topStocksTotal / $perPage)
        ];

        // En çok çalışılan fırınlar (pagination ile) - tarih filtresinden bağımsız, tüm veriler
        $topKilnsQuery = $company->barcodes()
            ->with('kiln')
            ->selectRaw('kiln_id, COUNT(*) as total_barcodes')
            ->whereNotNull('kiln_id')
            ->groupBy('kiln_id')
            ->orderByDesc('total_barcodes');
        
        $topKilnsTotal = $topKilnsQuery->get()->count();
        $topKilns = $topKilnsQuery->skip((request('kilns_page', 1) - 1) * $perPage)->take($perPage)->get();
        
        // KG değerlerini ayrıca hesapla - tüm veriler
        foreach ($topKilns as $kiln) {
            $kiln->total_quantity = $company->barcodes()
                ->where('kiln_id', $kiln->kiln_id)
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
        }
        
        $topKilnsPagination = [
            'data' => $topKilns,
            'total' => $topKilnsTotal,
            'per_page' => $perPage,
            'current_page' => request('kilns_page', 1),
            'last_page' => ceil($topKilnsTotal / $perPage)
        ];

        return view('admin.company.analysis', compact('company', 'statusDistribution', 'monthlyPurchase', 'topStocks', 'topKilns', 'topStocksPagination', 'topKilnsPagination'));
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
                    $startDate = null;
                    $endDate = null;
                    break;
                default:
                    // Günlük - son 7 gün
                    $startDate = now()->subDays(7)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak son 7 gün
            $startDate = now()->subDays(7)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $company = Company::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->with(['stock', 'kiln', 'warehouse', 'quantity']);

            // Tarih filtreleme - teslim ve transferler kendi tarih alanlarına göre dahil edilir
            if ($startDate || $endDate) {
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->where(function($q2) use ($startDate, $endDate) {
                        $q2->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER);
                        if ($startDate) { $q2->where('company_transferred_at', '>=', $startDate); }
                        if ($endDate) { $q2->where('company_transferred_at', '<=', $endDate . ' 23:59:59'); }
                    })
                    ->orWhere(function($q3) use ($startDate, $endDate) {
                        $q3->where('status', \App\Models\Barcode::STATUS_DELIVERED);
                        if ($startDate) { $q3->where('delivered_at', '>=', $startDate); }
                        if ($endDate) { $q3->where('delivered_at', '<=', $endDate . ' 23:59:59'); }
                    })
                    ->orWhere(function($q4) use ($startDate, $endDate) {
                        $q4->whereNotIn('status', [\App\Models\Barcode::STATUS_CUSTOMER_TRANSFER, \App\Models\Barcode::STATUS_DELIVERED]);
                        if ($startDate) { $q4->where('created_at', '>=', $startDate); }
                        if ($endDate) { $q4->where('created_at', '<=', $endDate . ' 23:59:59'); }
                    });
                });
            }
        }])->findOrFail($id);

        // Excel export için veri hazırlama
        $data = [];
        
        // Debug bilgisi ekle
        \Log::info('Company Report Export Debug', [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period,
            'total_barcodes' => $company->barcodes->count(),
            'barcodes_sample' => $company->barcodes->take(5)->map(function($b) {
                return [
                    'id' => $b->id,
                    'status' => $b->status,
                    'created_at' => $b->created_at,
                    'stock_name' => $b->stock ? $b->stock->name : null,
                    'quantity' => $b->quantity ? $b->quantity->quantity : null
                ];
            })
        ]);
        
        // Eğer hiç barcode yoksa, boş veri döndür
        if ($company->barcodes->isEmpty()) {
            \Log::warning('Company has no barcodes for export', [
                'company_id' => $company->id,
                'company_name' => $company->name
            ]);
        }
        
        foreach ($company->barcodes as $barcode) {
            $data[] = [
                'Barkod ID' => $barcode->id,
                'Stok Adı' => $barcode->stock ? $barcode->stock->name : '-',
                'Miktar (KG)' => $barcode->quantity ? $barcode->quantity->quantity : 0,
                'Durum' => \App\Models\Barcode::STATUSES[$barcode->status] ?? 'Bilinmiyor',
                'Fırın' => $barcode->kiln ? $barcode->kiln->name : '-',
                'Yüklenen Depo' => $barcode->warehouse ? $barcode->warehouse->name : '-',
                'Oluşturulma Tarihi' => $barcode->created_at ? $barcode->created_at->format('d.m.Y H:i') : '-',
                'Müşteri Transfer Tarihi' => $barcode->company_transferred_at ? \Carbon\Carbon::parse($barcode->company_transferred_at)->format('d.m.Y H:i') : '-',
                'Teslim Tarihi' => $barcode->delivered_at ? \Carbon\Carbon::parse($barcode->delivered_at)->format('d.m.Y H:i') : '-',
            ];
        }

        // Dosya adına tarih bilgisi ekle
        $fileName = 'Firma_Raporu_' . $company->name;
        if ($startDate && $endDate) {
            if ($startDate === $endDate) {
                $fileName .= '_' . \Carbon\Carbon::parse($startDate)->format('d-m-Y');
            } else {
                $fileName .= '_' . \Carbon\Carbon::parse($startDate)->format('d-m-Y') . '-to-' . \Carbon\Carbon::parse($endDate)->format('d-m-Y');
            }
        } elseif ($period) {
            $periodNames = [
                'monthly' => 'aylik',
                'quarterly' => '3-aylik', 
                'yearly' => 'yillik',
                'all' => 'tum-zamanlar'
            ];
            $fileName .= '_' . ($periodNames[$period] ?? 'gunluk');
        } else {
            $fileName .= '_' . \Carbon\Carbon::now()->format('d-m-Y');
        }
        $fileName .= '.xlsx';

        return \Excel::download(new \App\Exports\CompanyReportExport($data), $fileName);
    }

    /**
     * Download Excel report for all companies.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExcel()
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
                    // Günlük - son 7 gün
                    $startDate = now()->subDays(7)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        } elseif (!$startDate && !$endDate) {
            // Varsayılan olarak son 7 gün
            $startDate = now()->subDays(7)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $companies = Company::withCount([
            'barcodes' => function($query) use ($startDate, $endDate) {
                if ($startDate) { $query->where('created_at', '>=', $startDate); }
                if ($endDate) { $query->where('created_at', '<=', $endDate . ' 23:59:59'); }
            }
        ])->get();

        // Her firma için detaylı istatistikler
        foreach ($companies as $company) {
            $barcodesQuery = $company->barcodes();
            if ($startDate) { $barcodesQuery->where('created_at', '>=', $startDate); }
            if ($endDate) { $barcodesQuery->where('created_at', '<=', $endDate . ' 23:59:59'); }
            
            // Toplam alım miktarı (KG) - sadece müşteri transfer ve teslim edildi
            $company->total_purchase = $barcodesQuery
                ->whereIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED
                ])
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
            
            // Durum bazında KG miktarları
            $company->customer_transfer_kg = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER)
                ->when($startDate, function($query) use ($startDate) {
                    return $query->where('created_at', '>=', $startDate);
                })
                ->when($endDate, function($query) use ($endDate) {
                    return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                })
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
            
            $company->delivered_kg = $company->barcodes()
                ->where('status', \App\Models\Barcode::STATUS_DELIVERED)
                ->when($startDate, function($query) use ($startDate) {
                    return $query->where('created_at', '>=', $startDate);
                })
                ->when($endDate, function($query) use ($endDate) {
                    return $query->where('created_at', '<=', $endDate . ' 23:59:59');
                })
                ->with('quantity')
                ->get()
                ->sum(function($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity : 0;
                });
            
            // Teslim oranı (teslim edildi / toplam müşteri transfer + teslim edildi)
            $totalRelevantBarcodes = $barcodesQuery
                ->whereIn('status', [
                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                    \App\Models\Barcode::STATUS_DELIVERED
                ])
                ->count();
            $deliveredBarcodes = $barcodesQuery->where('status', \App\Models\Barcode::STATUS_DELIVERED)->count();
            $company->delivery_rate = $totalRelevantBarcodes > 0 ? round(($deliveredBarcodes / $totalRelevantBarcodes) * 100, 2) : 0;
            
            // Son alım tarihi
            $company->last_purchase_date = $barcodesQuery->latest('created_at')->value('created_at');
            
            // Ortalama sipariş büyüklüğü (KG)
            $company->average_order_size = $totalRelevantBarcodes > 0 ? round($company->total_purchase / $totalRelevantBarcodes, 0) : 0;
        }

        // Dosya adına tarih bilgisi ekle
        $fileName = 'firmalar';
        if ($startDate && $endDate) {
            if ($startDate === $endDate) {
                $fileName .= '_' . \Carbon\Carbon::parse($startDate)->format('d-m-Y');
            } else {
                $fileName .= '_' . \Carbon\Carbon::parse($startDate)->format('d-m-Y') . '-to-' . \Carbon\Carbon::parse($endDate)->format('d-m-Y');
            }
        } elseif ($period) {
            $periodNames = [
                'monthly' => 'aylik',
                'quarterly' => '3-aylik', 
                'yearly' => 'yillik',
                'all' => 'tum-zamanlar'
            ];
            $fileName .= '_' . ($periodNames[$period] ?? 'gunluk');
        } else {
            $fileName .= '_' . \Carbon\Carbon::now()->format('d-m-Y');
        }
        $fileName .= '.xlsx';

        return \Excel::download(new \App\Exports\CompanyExport($companies, $startDate, $endDate, $period), $fileName);
    }
}
