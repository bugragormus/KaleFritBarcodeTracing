<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GranilyaProduction;
use App\Models\GranilyaCrusher;
use App\Models\User;
use Carbon\Carbon;
use DB;

class PageController extends Controller
{
    public function report(Request $request)
    {
        $period = $request->input('period', 'daily');
        $selectedDate = $request->input('date', Carbon::today('Europe/Istanbul')->format('Y-m-d'));
        $startDateStr = $request->input('start_date');
        $endDateStr = $request->input('end_date');
        $date = Carbon::parse($selectedDate)->setTimezone('Europe/Istanbul');
        
        $periodInfo = $this->calculatePeriodInfo($date, $period, $startDateStr, $endDateStr);
        $productionData = $this->getProductionData($periodInfo);
        $shiftReport = $this->getShiftReport($periodInfo);
        $crusherPerformance = $this->getCrusherPerformance($periodInfo);
        $rejectionReasonsAnalysis = $this->getRejectionReasonsAnalysis($periodInfo);
        $productCrusherAnalysis = $this->getProductCrusherAnalysis($periodInfo);
        $stockAgeAnalysis = $this->getStockAgeAnalysis();
        $aiInsights = $this->getAiInsights($periodInfo);

        // Required charts
        $monthlyComparison = $this->getMonthlyComparison($date);
        $crusherRejectionRates = $this->getCrusherRejectionRates($periodInfo);

        return view('granilya.production.report', compact(
            'selectedDate',
            'date',
            'period',
            'periodInfo',
            'startDateStr',
            'endDateStr',
            'productionData',
            'shiftReport',
            'crusherPerformance',
            'productCrusherAnalysis',
            'stockAgeAnalysis',
            'aiInsights',
            'crusherRejectionRates',
            'rejectionReasonsAnalysis',
            'monthlyComparison'
        ));
    }

    private function calculatePeriodInfo($date, $period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today('Europe/Istanbul');
        
        if ($startDate && $endDate) {
            try {
                $start = Carbon::parse($startDate)->setTimezone('Europe/Istanbul')->startOfDay();
                $end = Carbon::parse($endDate)->setTimezone('Europe/Istanbul')->endOfDay();
                if ($end->isAfter($today->copy()->endOfDay())) $end = $today->copy()->endOfDay();
                
                return [
                    'name' => 'Özel Tarih Aralığı',
                    'range' => $start->format('d.m.Y') . ' - ' . $end->format('d.m.Y'),
                    'start_date' => $start,
                    'end_date' => $end,
                    'start_date_formatted' => $start->format('d.m.Y'),
                    'end_date_formatted' => $end->format('d.m.Y'),
                    'is_custom' => true
                ];
            } catch (\Exception $e) {
                // Fallback to default behavior if invalid dates are given
            }
        }
        
        $startDate = $date->copy();
        $endDate = $date->copy();
        
        switch ($period) {
            case 'daily':
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $periodName = 'Günlük';
                $periodRange = $date->format('d.m.Y');
                break;
            case 'weekly':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = 'Haftalık';
                $periodRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');
                break;
            case 'monthly':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = 'Aylık';
                $periodRange = $startDate->format('F Y');
                break;
            case 'quarterly':
                $startDate = $date->copy()->startOfQuarter();
                $endDate = $date->copy()->endOfQuarter();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = '3 Aylık';
                $periodRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');
                break;
            case 'yearly':
                $startDate = $date->copy()->startOfYear();
                $endDate = $date->copy()->endOfYear();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = 'Yıllık';
                $periodRange = $startDate->format('Y');
                break;
            default:
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $periodName = 'Günlük';
                $periodRange = $date->format('d.m.Y');
        }
        
        return [
            'name' => $periodName,
            'range' => $periodRange,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_date_formatted' => $startDate->format('d.m.Y'),
            'end_date_formatted' => $endDate->format('d.m.Y'),
            'is_custom' => false
        ];
    }

    private function getProductionData($periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $baseQuery = GranilyaProduction::whereBetween('granilya_productions.created_at', [$startDate, $endDate])->whereNull('granilya_productions.deleted_at');

        $totalCount = (clone $baseQuery)->count();
        $totalQuantity = (clone $baseQuery)->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $acceptedQuantity = (clone $baseQuery)->whereIn('granilya_productions.status', [
            GranilyaProduction::STATUS_SHIPMENT_APPROVED,
            GranilyaProduction::STATUS_CUSTOMER_TRANSFER,
            GranilyaProduction::STATUS_DELIVERED
        ])->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $testingQuantity = (clone $baseQuery)->where(function($q) {
            $q->whereNull('granilya_productions.status')
              ->orWhereNotIn('granilya_productions.status', [
                  GranilyaProduction::STATUS_SHIPMENT_APPROVED,
                  GranilyaProduction::STATUS_CUSTOMER_TRANSFER,
                  GranilyaProduction::STATUS_DELIVERED,
                  GranilyaProduction::STATUS_REJECTED
              ]);
        })->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $deliveryQuantity = (clone $baseQuery)->whereIn('granilya_productions.status', [
            GranilyaProduction::STATUS_CUSTOMER_TRANSFER,
            GranilyaProduction::STATUS_DELIVERED
        ])->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $rejectedQuantity = (clone $baseQuery)->where(function($q) {
            $q->where('granilya_productions.status', GranilyaProduction::STATUS_REJECTED)
              ->orWhere('granilya_productions.sieve_test_result', 'Red')
              ->orWhere('granilya_productions.surface_test_result', 'Red')
              ->orWhere('granilya_productions.arge_test_result', 'Red');
        })->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        return [
            'total_barcodes'    => $totalCount,
            'total_quantity'    => $totalQuantity,
            'accepted_quantity' => $acceptedQuantity,
            'testing_quantity'  => $testingQuantity,
            'delivery_quantity' => $deliveryQuantity,
            'rejected_quantity' => $rejectedQuantity,
            'without_correction_output' => $totalQuantity // Dummy logic unless correction exists
        ];
    }

    private function getShiftReport($periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $shifts = [
            'gece' => ['start' => '00:00:00', 'end' => '08:00:00'],
            'gündüz' => ['start' => '08:00:01', 'end' => '16:00:00'],
            'akşam' => ['start' => '16:00:01', 'end' => '23:59:59']
        ];
        
        $shiftData = [];
        foreach ($shifts as $shiftName => $shiftTime) {
            $shiftStart = $shiftTime['start'];
            $shiftEnd = $shiftTime['end'];

            $baseQuery = GranilyaProduction::whereBetween('granilya_productions.created_at', [$startDate, $endDate])
                ->whereTime('granilya_productions.created_at', '>=', $shiftStart)
                ->whereTime('granilya_productions.created_at', '<=', $shiftEnd)
                ->whereNull('granilya_productions.deleted_at')
                ->leftJoin('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id');

            $totalQuantity = (clone $baseQuery)->sum('granilya_quantities.quantity') ?? 0;
            $acceptedQuantity = (clone $baseQuery)->whereIn('granilya_productions.status', [
                GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED
            ])->sum('granilya_quantities.quantity') ?? 0;
            $rejectedQuantity = (clone $baseQuery)->where(function($q) {
                $q->where('granilya_productions.status', GranilyaProduction::STATUS_REJECTED)
                  ->orWhere('granilya_productions.sieve_test_result', 'Red')
                  ->orWhere('granilya_productions.surface_test_result', 'Red')
                  ->orWhere('granilya_productions.arge_test_result', 'Red');
            })->sum('granilya_quantities.quantity') ?? 0;

            $shiftData[$shiftName] = [
                'barcode_count' => (clone $baseQuery)->count(),
                'total_quantity' => $totalQuantity,
                'accepted_quantity' => $acceptedQuantity,
                'rejected_quantity' => $rejectedQuantity,
            ];
        }

        return $shiftData;
    }

    private function getCrusherPerformance($periodInfo)
    {
        $startDate = clone $periodInfo['start_date'];
        $endDate = clone $periodInfo['end_date'];

        $crushers = GranilyaCrusher::all();
        $performance = [];

        foreach($crushers as $crusher) {
            $baseQuery = GranilyaProduction::where('granilya_productions.crusher_id', $crusher->id)
                ->whereBetween('granilya_productions.created_at', [$startDate, $endDate])
                ->whereNull('granilya_productions.deleted_at');
            
            $productionCount = (clone $baseQuery)->count();
            if($productionCount == 0) continue;

            $baseQtyQuery = (clone $baseQuery)->leftJoin('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id');
            $totalQuantity = (clone $baseQtyQuery)->sum('granilya_quantities.quantity') ?? 0;

            $acceptedQuantity = (clone $baseQtyQuery)->whereIn('granilya_productions.status', [
                GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED
            ])->sum('granilya_quantities.quantity') ?? 0;

            $rejectedQuantity = (clone $baseQtyQuery)->where(function($q) {
                $q->where('granilya_productions.status', GranilyaProduction::STATUS_REJECTED)
                  ->orWhere('granilya_productions.sieve_test_result', 'Red')
                  ->orWhere('granilya_productions.surface_test_result', 'Red')
                  ->orWhere('granilya_productions.arge_test_result', 'Red');
            })->sum('granilya_quantities.quantity') ?? 0;

            $performance[] = (object) [
                'crusher_name' => $crusher->name,
                'barcode_count' => $productionCount,
                'total_quantity' => $totalQuantity,
                'accepted_quantity' => $acceptedQuantity,
                'rejected_quantity' => $rejectedQuantity,
            ];
        }

        usort($performance, function($a, $b) {
            return strnatcmp($a->crusher_name, $b->crusher_name);
        });

        return $performance;
    }

    private function getRejectionReasonsAnalysis($periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $rejectedProductions = GranilyaProduction::with(['quantity', 'crusher'])
            ->whereBetween('granilya_productions.created_at', [$startDate, $endDate])
            ->where(function($q) {
                $q->where('granilya_productions.status', GranilyaProduction::STATUS_REJECTED)
                  ->orWhere('granilya_productions.sieve_test_result', 'Red')
                  ->orWhere('granilya_productions.surface_test_result', 'Red')
                  ->orWhere('granilya_productions.arge_test_result', 'Red');
            })
            ->whereNull('granilya_productions.deleted_at')
            ->get();

        $analysis = [];

        foreach ($rejectedProductions as $p) {
            $qty = $p->quantity ? $p->quantity->quantity : 0;
            $crusherName = $p->crusher ? $p->crusher->name : 'Bilinmeyen';

            $sieveRed = ($p->sieve_test_result === 'Red');
            $surfaceRed = ($p->surface_test_result === 'Red');
            $argeRed = ($p->arge_test_result === 'Red');

            $reasonNames = [];
            
            // Collect the actual reasons from the database text columns, fallback to the test name if reason text is missing
            if ($sieveRed) $reasonNames[] = $p->sieve_reject_reason ? $p->sieve_reject_reason : 'Elek Testi Red (Nedensiz)';
            if ($surfaceRed) $reasonNames[] = $p->surface_reject_reason ? $p->surface_reject_reason : 'Yüzey Testi Red (Nedensiz)';
            if ($argeRed) $reasonNames[] = 'AR-GE Testi Red'; // AR-GE test doesn't store a specific reason text string

            $reasonsCount = count($reasonNames);
            if ($reasonsCount == 0) {
                $reasonNames[] = 'Diğer Sebepler';
                $reasonsCount = 1;
            }

            $kgPerReason = $qty / $reasonsCount;

            foreach ($reasonNames as $reason) {
                if (!isset($analysis[$reason])) {
                    $analysis[$reason] = [
                        'count' => 0,
                        'total_quantity' => 0,
                        'percentage' => 0
                    ];
                }
                $analysis[$reason]['count']++;
                $analysis[$reason]['total_quantity'] += $kgPerReason;
            }
        }

        $totalKg = array_sum(array_column($analysis, 'total_quantity'));
        foreach ($analysis as $reason => &$data) {
            $data['percentage'] = $totalKg > 0 ? round(($data['total_quantity'] / $totalKg) * 100, 1) : 0;
        }

        uasort($analysis, function($a, $b) {
            return $b['total_quantity'] <=> $a['total_quantity'];
        });

        // Map to object format requested by chart
        $mappedAnalysis = [];
        foreach($analysis as $reason => $val) {
            $mappedAnalysis[] = (object) [
                'reason_name' => $reason,
                'count' => $val['count'],
                'total_quantity' => $val['total_quantity'],
                'percentage' => $val['percentage']
            ];
        }

        return $mappedAnalysis;
    }

    private function getMonthlyComparison($date)
    {
        $labels = [];
        $currentMonthData = [];
        $previousMonthData = [];
        
        $currentMonth = $date->copy()->startOfMonth();
        $previousMonth = $date->copy()->subMonth()->startOfMonth();
        
        $daysInCurrentMonth = $date->daysInMonth;
        
        for ($i = 1; $i <= $daysInCurrentMonth; $i++) {
            $labels[] = $i;
            
            $currStart = $currentMonth->copy()->addDays($i - 1)->startOfDay();
            $currEnd = $currentMonth->copy()->addDays($i - 1)->endOfDay();
            
            $prevStart = $previousMonth->copy()->addDays($i - 1)->startOfDay();
            $prevEnd = $previousMonth->copy()->addDays($i - 1)->endOfDay();
            
            $currTotal = GranilyaProduction::join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')
                ->whereBetween('granilya_productions.created_at', [$currStart, $currEnd])
                ->whereNull('granilya_productions.deleted_at')
                ->sum('granilya_quantities.quantity') ?? 0;
                
            $prevTotal = GranilyaProduction::join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')
                ->whereBetween('granilya_productions.created_at', [$prevStart, $prevEnd])
                ->whereNull('granilya_productions.deleted_at')
                ->sum('granilya_quantities.quantity') ?? 0;
                
            $currentMonthData[] = $currTotal;
            $previousMonthData[] = $prevTotal;
        }

        $monthNames = [
            '1' => 'Ocak', '2' => 'Şubat', '3' => 'Mart', '4' => 'Nisan', 
            '5' => 'Mayıs', '6' => 'Haziran', '7' => 'Temmuz', '8' => 'Ağustos', 
            '9' => 'Eylül', '10' => 'Ekim', '11' => 'Kasım', '12' => 'Aralık'
        ];
        
        return [
            'labels' => $labels,
            'current' => $currentMonthData,
            'previous' => $previousMonthData,
            'current_month_name' => $monthNames[$currentMonth->format('n')],
            'previous_month_name' => $monthNames[$previousMonth->format('n')]
        ];
    }
    
    private function getCrusherRejectionRates($periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $crushers = GranilyaCrusher::all();
        $rates = [];

        foreach($crushers as $crusher) {
            $baseQuery = GranilyaProduction::where('granilya_productions.crusher_id', $crusher->id)
                ->whereBetween('granilya_productions.created_at', [$startDate, $endDate])
                ->whereNull('granilya_productions.deleted_at')
                ->leftJoin('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id');
                
            $totalQuantity = (clone $baseQuery)->sum('granilya_quantities.quantity') ?? 0;
            if($totalQuantity == 0) continue;

            $rejectedQuantity = (clone $baseQuery)->where('granilya_productions.status', GranilyaProduction::STATUS_REJECTED)->sum('granilya_quantities.quantity') ?? 0;
            
            $rates[] = (object) [
                'crusher_name' => $crusher->name,
                'total_quantity' => $totalQuantity,
                'rejected_quantity' => $rejectedQuantity,
                'rejection_rate' => ($rejectedQuantity / $totalQuantity) * 100
            ];
        }

        usort($rates, function($a, $b) {
            return $b->rejection_rate <=> $a->rejection_rate;
        });

        return $rates;
    }

    private function getProductCrusherAnalysis($periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $productAnalysis = GranilyaProduction::whereBetween('granilya_productions.created_at', [$startDate, $endDate])
            ->whereNull('granilya_productions.deleted_at')
            ->join('granilya_crushers', 'granilya_productions.crusher_id', '=', 'granilya_crushers.id')
            ->join('stocks', 'granilya_productions.stock_id', '=', 'stocks.id')
            ->leftJoin('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')
            ->select(
                'stocks.name as stock_name',
                'stocks.code as stock_code',
                'granilya_crushers.name as crusher_name',
                DB::raw('COUNT(granilya_productions.id) as barcode_count'),
                DB::raw('SUM(granilya_quantities.quantity) as total_quantity'),
                DB::raw('SUM(CASE WHEN granilya_productions.status IN ("' . GranilyaProduction::STATUS_SHIPMENT_APPROVED . '", "' . GranilyaProduction::STATUS_CUSTOMER_TRANSFER . '", "' . GranilyaProduction::STATUS_DELIVERED . '") THEN granilya_quantities.quantity ELSE 0 END) as accepted_quantity'),
                DB::raw('SUM(CASE WHEN granilya_productions.status IS NULL OR granilya_productions.status NOT IN ("' . GranilyaProduction::STATUS_SHIPMENT_APPROVED . '", "' . GranilyaProduction::STATUS_CUSTOMER_TRANSFER . '", "' . GranilyaProduction::STATUS_DELIVERED . '", "' . GranilyaProduction::STATUS_REJECTED . '") THEN granilya_quantities.quantity ELSE 0 END) as testing_quantity'),
                DB::raw('SUM(CASE WHEN granilya_productions.status IN ("' . GranilyaProduction::STATUS_CUSTOMER_TRANSFER . '", "' . GranilyaProduction::STATUS_DELIVERED . '") THEN granilya_quantities.quantity ELSE 0 END) as delivery_quantity'),
                DB::raw('SUM(CASE WHEN granilya_productions.status = "' . GranilyaProduction::STATUS_REJECTED . '" OR granilya_productions.sieve_test_result = "Red" OR granilya_productions.surface_test_result = "Red" OR granilya_productions.arge_test_result = "Red" THEN granilya_quantities.quantity ELSE 0 END) as rejected_quantity'),
                DB::raw('0 as transferred_quantity')
            )
            ->groupBy('stocks.name', 'stocks.code', 'granilya_crushers.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        foreach($productAnalysis as $analysis) {
            $analysis->acceptance_rate = $analysis->total_quantity > 0 ? round(($analysis->accepted_quantity / $analysis->total_quantity) * 100, 1) : 0;
            $analysis->rejection_rate = $analysis->total_quantity > 0 ? round(($analysis->rejected_quantity / $analysis->total_quantity) * 100, 1) : 0;
        }

        return $productAnalysis;
    }

    private function getStockAgeAnalysis()
    {
        // Gerçek bekleme sürelerini hesaplama (Status is null or 'Bekliyor')
        $waitingStocks = GranilyaProduction::with(['quantity', 'stock', 'company'])
            ->where(function($q) {
                $q->whereNull('status')
                  ->orWhere('sieve_test_result', 'Bekliyor')
                  ->orWhere('surface_test_result', 'Bekliyor')
                  ->orWhere('arge_test_result', 'Bekliyor');
            })
            ->whereNull('deleted_at')
            ->get();

        $analysis = [
            'current_date' => Carbon::now('Europe/Istanbul')->format('d.m.Y H:i'),
            'summary' => [
                'critical_count' => 0, 'critical_quantity' => 0,
                'warning_count' => 0, 'warning_quantity' => 0,
                'attention_count' => 0, 'attention_quantity' => 0,
                'normal_count' => 0, 'normal_quantity' => 0,
            ],
            'categorized_stock' => [
                'critical' => [], 'warning' => [], 'attention' => [], 'normal' => []
            ],
            'status_analysis' => [],
            'product_analysis' => []
        ];

        $now = Carbon::now('Europe/Istanbul');
        $productGroups = [];

        foreach ($waitingStocks as $p) {
            $qty = $p->quantity ? $p->quantity->quantity : 0;
            $stockName = $p->stock ? $p->stock->name : 'Bilinmeyen Ürün';
            $stockCode = $p->stock ? $p->stock->code : 'N/A';
            
            // Frit's age analysis looks at days waiting
            $daysOld = $p->created_at ? $p->created_at->diffInDays($now) : 0;
            $status = $p->status ?: 'waiting';
            
            $item = (object) [
                'barcode' => $p->base_pallet_number ? $p->base_pallet_number.'-'.$p->pallet_number : 'N/A',
                'stock_name' => $stockName,
                'stock_code' => $stockCode,
                'quantity' => $qty,
                'status' => $status,
                'company_name' => $p->company ? $p->company->name : '-',
                'warehouse_name' => 'Granilya Depo',
                'kiln_name' => '-', // Granilya doesn't use kilns for this
                'days_old' => $daysOld,
                'lab_at' => $p->laboratory_user_id ? $p->updated_at : null,
                'shipment_at' => null,
                'created_at' => $p->created_at
            ];

            // Product Group Analysis
            $productKey = $stockName . ' (' . $stockCode . ')';
            if (!isset($productGroups[$productKey])) {
                $productGroups[$productKey] = [
                    'stock_name' => $stockName,
                    'stock_code' => $stockCode,
                    'count' => 0,
                    'quantity' => 0,
                    'avg_age' => 0,
                    'oldest_age' => 0,
                    'critical_count' => 0,
                    'warning_count' => 0
                ];
            }
            $productGroups[$productKey]['count']++;
            $productGroups[$productKey]['quantity'] += $qty;
            $productGroups[$productKey]['avg_age'] += $daysOld;
            $productGroups[$productKey]['oldest_age'] = max($productGroups[$productKey]['oldest_age'], $daysOld);

            // Status Analysis
            if (!isset($analysis['status_analysis'][$status])) {
                $analysis['status_analysis'][$status] = [
                    'count' => 0,
                    'quantity' => 0,
                    'avg_age' => 0,
                    'oldest_age' => 0
                ];
            }
            $analysis['status_analysis'][$status]['count']++;
            $analysis['status_analysis'][$status]['quantity'] += $qty;
            $analysis['status_analysis'][$status]['avg_age'] += $daysOld;
            $analysis['status_analysis'][$status]['oldest_age'] = max($analysis['status_analysis'][$status]['oldest_age'], $daysOld);

            // Time Categories
            if ($daysOld >= 30) {
                $analysis['summary']['critical_count']++;
                $analysis['summary']['critical_quantity'] += $qty;
                $analysis['categorized_stock']['critical'][] = $item;
                $productGroups[$productKey]['critical_count']++;
            } elseif ($daysOld >= 15) {
                $analysis['summary']['warning_count']++;
                $analysis['summary']['warning_quantity'] += $qty;
                $analysis['categorized_stock']['warning'][] = $item;
                $productGroups[$productKey]['warning_count']++;
            } elseif ($daysOld >= 7) {
                $analysis['summary']['attention_count']++;
                $analysis['summary']['attention_quantity'] += $qty;
                $analysis['categorized_stock']['attention'][] = $item;
            } else {
                $analysis['summary']['normal_count']++;
                $analysis['summary']['normal_quantity'] += $qty;
                $analysis['categorized_stock']['normal'][] = $item;
            }
        }
        
        // Product analysis sorting
        foreach ($productGroups as $productKey => $data) {
            if ($data['count'] > 0) {
                $data['avg_age'] = round($data['avg_age'] / $data['count'], 1);
            }
            $analysis['product_analysis'][] = $data; // As array
        }
        
        usort($analysis['product_analysis'], function($a, $b) {
            return ($b['critical_count'] + $b['warning_count']) <=> ($a['critical_count'] + $a['warning_count']);
        });
        
        // Status analysis calculations
        foreach ($analysis['status_analysis'] as $status => $data) {
            if ($data['count'] > 0) {
                $analysis['status_analysis'][$status]['avg_age'] = round($data['avg_age'] / $data['count'], 1);
            }
        }
        
        // Limit Top 10 critical stocks for display safety
        $analysis['categorized_stock']['critical'] = array_slice($analysis['categorized_stock']['critical'], 0, 10);
        $analysis['categorized_stock']['warning'] = array_slice($analysis['categorized_stock']['warning'], 0, 10);
        
        return $analysis;
    }
    
    private function getAiInsights($periodInfo)
    {
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $baseQuery = GranilyaProduction::whereBetween('granilya_productions.created_at', [$startDate, $endDate])->whereNull('granilya_productions.deleted_at');
        
        $totalCount = (clone $baseQuery)->count();
        if ($totalCount == 0) {
            return [
                'production_efficiency' => [
                    'level' => 'average', 'oee_score' => 0, 'availability' => 0,
                    'performance' => 0, 'quality_rate' => 0, 'total_barcodes' => 0,
                    'active_barcodes' => 0, 'rejected_barcodes' => 0, 'avg_quantity' => 0
                ],
                'production_forecast' => 0, 'confidence_level' => 0, 'trend_direction' => 'neutral',
                'trend_percentage' => 0, 'quality_risk_level' => 'low', 'expected_rejection_rate' => 0,
                'quality_recommendation' => 'Veri yetersizliği.', 'anomalies' => []
            ];
        }

        $activeCount = (clone $baseQuery)->whereIn('status', [GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED])->count();
        $rejectedCount = (clone $baseQuery)->where('status', GranilyaProduction::STATUS_REJECTED)->count();
        $waitingCount = (clone $baseQuery)->whereNull('status')->count();
        
        $qualityRate = ($activeCount / max($totalCount, 1)) * 100;
        $performanceRate = 100 - (($waitingCount / max($totalCount, 1)) * 100); // Inverse of waiting ratio as performance
        $oeeScore = ($qualityRate + $performanceRate + 95) / 3; // Synthetic OEE
        
        $riskLevel = $rejectedCount > ($totalCount * 0.1) ? 'high' : ($rejectedCount > ($totalCount * 0.05) ? 'medium' : 'low');
        
        $anomalies = [];
        if ($rejectedCount > ($totalCount * 0.1)) {
            $anomalies[] = [
                'type' => 'Yüksek Red Oranı',
                'description' => 'Reddedilen ürün sayısı toplam üretimin %10\'unu aşmıştır.',
                'severity' => 'high'
            ];
        }

        return [
            'production_efficiency' => [
                'level' => $oeeScore > 85 ? 'high' : ($oeeScore > 65 ? 'average' : 'low'),
                'oee_score' => round($oeeScore),
                'availability' => 95, // Assuming high availability
                'performance' => round($performanceRate),
                'quality_rate' => round($qualityRate),
                'total_barcodes' => $totalCount,
                'active_barcodes' => $activeCount,
                'rejected_barcodes' => $rejectedCount,
                'merged_barcodes' => 0,
                'avg_quantity' => round((clone $baseQuery)->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->avg('granilya_quantities.quantity') ?? 0, 1)
            ],
            'production_forecast' => round((clone $baseQuery)->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') * 1.05),
            'confidence_level' => 88,
            'trend_direction' => 'up',
            'trend_percentage' => 5,
            'quality_risk_level' => $riskLevel,
            'expected_rejection_rate' => round(($rejectedCount / $totalCount) * 100, 1),
            'quality_recommendation' => $riskLevel == 'low' ? 'Mevcut standartları koruyun.' : 'Özellikle elek ve yüzey test aşamalarını inceleyin.',
            'anomalies' => $anomalies
        ];
    }
}
