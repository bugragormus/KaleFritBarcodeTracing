<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kiln\KilnStoreRequest;
use App\Http\Requests\Kiln\KilnUpdateRequest;
use App\Models\Kiln;
use App\Models\Permission;

class KilnController extends Controller
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
        $kilnId = request('kiln_id');
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

        // Fırınları getir
        $kilnsQuery = Kiln::query();
        
        // Fırın ID filtresi
        if ($kilnId) {
            $kilnsQuery->where('id', $kilnId);
        }
        
        $kilns = $kilnsQuery->withCount([
            'barcodes' => function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as total_quantity' => function($query) use ($startDate, $endDate) {
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
                $query->select(\DB::raw('SUM(quantity_id)'));
            },
            'barcodes as waiting_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_WAITING);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as control_repeat_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as pre_approved_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as shipment_approved_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_SHIPMENT_APPROVED);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as customer_transfer_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as delivered_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_DELIVERED);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as rejected_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_REJECTED);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            },
            'barcodes as merged_count' => function($query) use ($startDate, $endDate) {
                $query->where('status', \App\Models\Barcode::STATUS_MERGED);
                if ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->where('created_at', '<=', $endDate . ' 23:59:59');
                }
            }
        ])->get();

        // Her fırın için detaylı istatistikler
        foreach ($kilns as $kiln) {
            // Tarih filtrelenmiş barcodes sorgusu
            $barcodesQuery = $kiln->barcodes();
            if ($startDate) {
                $barcodesQuery->where('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $barcodesQuery->where('created_at', '<=', $endDate . ' 23:59:59');
            }
            
            // Toplam üretim miktarı
            $kiln->total_production = $barcodesQuery->sum('quantity_id');
            
            // Son 30 günlük üretim (tarih filtresi varsa sadece o aralıkta)
            if ($startDate || $endDate) {
                $kiln->last_30_days_production = $kiln->total_production;
            } else {
                $kiln->last_30_days_production = $kiln->barcodes()
                    ->where('created_at', '>=', now()->subDays(30))
                    ->sum('quantity_id');
            }
            
            // Günlük ortalama üretim
            $barcodes = $barcodesQuery->get();
            $activeDays = $barcodes->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })->count();
            $kiln->daily_average_30_days = $activeDays > 0 ? round($kiln->total_production / $activeDays, 2) : 0;
            
            // Aylık ortalama üretim (son 12 ay)
            $last12MonthsBarcodes = $kiln->barcodes()
                ->where('created_at', '>=', now()->subMonths(12))
                ->get();
            $activeMonths12 = $last12MonthsBarcodes->groupBy(function($item) {
                return $item->created_at->format('Y-m');
            })->count();
            $totalProduction12Months = $last12MonthsBarcodes->sum('quantity_id');
            $kiln->monthly_average_12_months = $activeMonths12 > 0 ? round($totalProduction12Months / $activeMonths12, 2) : 0;
            
            // Genel günlük ortalama (tüm zamanlar)
            $allBarcodes = $kiln->barcodes()->get();
            $activeDaysTotal = $allBarcodes->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })->count();
            $kiln->daily_average_total = $activeDaysTotal > 0 ? round($kiln->total_production / $activeDaysTotal, 2) : 0;
            
            // Red oranı
            $totalBarcodes = $barcodes->count();
            $rejectedBarcodes = $barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            $kiln->rejection_rate = $totalBarcodes > 0 ? round(($rejectedBarcodes / $totalBarcodes) * 100, 2) : 0;
            
            // Teslim oranı
            $deliveredBarcodes = $barcodes->where('status', \App\Models\Barcode::STATUS_DELIVERED)->count();
            $kiln->delivery_rate = $totalBarcodes > 0 ? round(($deliveredBarcodes / $totalBarcodes) * 100, 2) : 0;
        }

        return view('admin.kiln.index', compact('kilns'));
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

        return view('admin.kiln.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Kiln\KilnStoreRequest $request
     * @return string
     */
    public function store(KilnStoreRequest $request)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();

        $kiln = Kiln::create($data);

        if (!$kiln) {
            toastr()->error('Fırın girişi yapılamadı.');
        }

        toastr()->success('Fırın girişi başarılı.');

        return redirect()->route('kiln.index');
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

        $kiln = Kiln::findOrFail($id);

        return view('admin.kiln.edit', compact('kiln'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Kiln\KilnUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(KilnUpdateRequest $request, $id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();

        $kiln = Kiln::findOrFail($id);

        $kiln->update($data);

        if (!$kiln) {
            toastr()->error('Fırın girişi düzenlenemedi.');
        }

        toastr()->success('Fırın girişi başarıyla düzenlendi.');

        return redirect()->route('kiln.index');
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

        $kiln = Kiln::findOrFail($id);

        $kiln->delete();

        if (!$kiln) {
            toastr()->error('Fırın girişi silinemedi.');
        }

        return response()->json(['message' => 'Fırın girişi silindi!']);
    }

    /**
     * Show detailed analysis for a specific kiln.
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

        $kiln = Kiln::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->with(['stock', 'company', 'warehouse']);
            
            // Tarih filtreleme
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('created_at', '<=', $endDate . ' 23:59:59');
            }
        }])->findOrFail($id);

        // Detaylı istatistikler
        $kiln->total_production = $kiln->barcodes->sum('quantity_id');
        $kiln->total_barcodes = $kiln->barcodes->count();
        
        // Durum bazında dağılım
        $statusDistribution = $kiln->barcodes->groupBy('status')->map->count();
        
        // Aylık üretim trendi (son 12 ay)
        $monthlyProduction = $kiln->barcodes()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(quantity_id) as total_quantity, COUNT(*) as total_barcodes')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Red oranı trendi
        $rejectionTrend = $kiln->barcodes()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, 
                        COUNT(*) as total_barcodes,
                        SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejected_count', [\App\Models\Barcode::STATUS_REJECTED])
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // En çok üretilen stoklar
        $topStocks = $kiln->barcodes()
            ->with('stock')
            ->selectRaw('stock_id, SUM(quantity_id) as total_quantity, COUNT(*) as total_barcodes')
            ->groupBy('stock_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // En çok çalışılan müşteriler
        $topCompanies = $kiln->barcodes()
            ->with('company')
            ->selectRaw('company_id, SUM(quantity_id) as total_quantity, COUNT(*) as total_barcodes')
            ->whereNotNull('company_id')
            ->groupBy('company_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return view('admin.kiln.analysis', compact('kiln', 'statusDistribution', 'monthlyProduction', 'rejectionTrend', 'topStocks', 'topCompanies'));
    }

    /**
     * Download Excel report for a specific kiln.
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

        $kiln = Kiln::with(['barcodes' => function($query) {
            $query->with(['stock', 'company', 'warehouse']);
        }])->findOrFail($id);

        // Excel export için veri hazırlama
        $data = [];
        foreach ($kiln->barcodes as $barcode) {
            $data[] = [
                'Barkod ID' => $barcode->id,
                'Stok Adı' => $barcode->stock ? $barcode->stock->name : '-',
                'Miktar (KG)' => $barcode->quantity_id,
                'Durum' => \App\Models\Barcode::STATUSES[$barcode->status] ?? 'Bilinmiyor',
                'Müşteri' => $barcode->company ? $barcode->company->name : '-',
                'Depo' => $barcode->warehouse ? $barcode->warehouse->name : '-',
                'Oluşturulma Tarihi' => $barcode->created_at ? $barcode->created_at->format('d.m.Y H:i') : '-',
                'Laboratuvar Tarihi' => $barcode->lab_at ? \Carbon\Carbon::parse($barcode->lab_at)->format('d.m.Y H:i') : '-',
                'Depo Transfer Tarihi' => $barcode->warehouse_transferred_at ? \Carbon\Carbon::parse($barcode->warehouse_transferred_at)->format('d.m.Y H:i') : '-',
                'Müşteri Transfer Tarihi' => $barcode->company_transferred_at ? \Carbon\Carbon::parse($barcode->company_transferred_at)->format('d.m.Y H:i') : '-',
                'Teslim Tarihi' => $barcode->delivered_at ? \Carbon\Carbon::parse($barcode->delivered_at)->format('d.m.Y H:i') : '-',
            ];
        }

        $filename = 'Firin_Raporu_' . $kiln->name . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return \Excel::download(new \App\Exports\KilnReportExport($data), $filename);
    }
}
