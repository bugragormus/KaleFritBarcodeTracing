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

        $baseQuery = GranilyaProduction::whereBetween('created_at', [$startDate, $endDate])->whereNull('deleted_at');

        $totalCount = (clone $baseQuery)->count();
        $totalQuantity = (clone $baseQuery)->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $acceptedQuantity = (clone $baseQuery)->whereIn('status', [
            GranilyaProduction::STATUS_SHIPMENT_APPROVED,
            GranilyaProduction::STATUS_CUSTOMER_TRANSFER,
            GranilyaProduction::STATUS_DELIVERED
        ])->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        // Waiting statuses in Granilya: empty status or "Bekliyor" test result
        $testingQuantity = (clone $baseQuery)->where(function($q) {
            $q->whereNull('status')->orWhere('sieve_test_result', 'Bekliyor')
              ->orWhere('surface_test_result', 'Bekliyor')
              ->orWhere('arge_test_result', 'Bekliyor');
        })->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $deliveryQuantity = (clone $baseQuery)->whereIn('status', [
            GranilyaProduction::STATUS_CUSTOMER_TRANSFER,
            GranilyaProduction::STATUS_DELIVERED
        ])->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

        $rejectedQuantity = (clone $baseQuery)->where('status', GranilyaProduction::STATUS_REJECTED)
        ->join('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id')->sum('granilya_quantities.quantity') ?? 0;

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
            $rejectedQuantity = (clone $baseQuery)->where('granilya_productions.status', GranilyaProduction::STATUS_REJECTED)->sum('granilya_quantities.quantity') ?? 0;

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
            $baseQuery = GranilyaProduction::where('crusher_id', $crusher->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNull('deleted_at');
            
            $productionCount = (clone $baseQuery)->count();
            if($productionCount == 0) continue;

            $baseQtyQuery = (clone $baseQuery)->leftJoin('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id');
            $totalQuantity = (clone $baseQtyQuery)->sum('granilya_quantities.quantity') ?? 0;

            $acceptedQuantity = (clone $baseQtyQuery)->whereIn('status', [
                GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED
            ])->sum('granilya_quantities.quantity') ?? 0;

            $rejectedQuantity = (clone $baseQtyQuery)->where('status', GranilyaProduction::STATUS_REJECTED)->sum('granilya_quantities.quantity') ?? 0;

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

        $rejectedProductions = GranilyaProduction::with(['quantity'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', GranilyaProduction::STATUS_REJECTED)
            ->whereNull('deleted_at')
            ->get();

        $analysis = [];

        foreach ($rejectedProductions as $p) {
            $qty = $p->quantity ? $p->quantity->quantity : 0;
            $crusherName = $p->crusher ? $p->crusher->name : 'Bilinmeyen';

            $sieveRed = ($p->sieve_test_result === 'Red');
            $surfaceRed = ($p->surface_test_result === 'Red');
            $argeRed = ($p->arge_test_result === 'Red');

            $reasonNames = [];
            if ($sieveRed) $reasonNames[] = 'Elek Testi Red';
            if ($surfaceRed) $reasonNames[] = 'Yüzey Testi Red';
            if ($argeRed) $reasonNames[] = 'AR-GE Testi Red';

            $reasonsCount = count($reasonNames);
            if ($reasonsCount == 0) {
                $reasonNames[] = 'Diğer Red';
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
            $baseQuery = GranilyaProduction::where('crusher_id', $crusher->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNull('deleted_at')
                ->leftJoin('granilya_quantities', 'granilya_productions.quantity_id', '=', 'granilya_quantities.id');
                
            $totalQuantity = (clone $baseQuery)->sum('granilya_quantities.quantity') ?? 0;
            if($totalQuantity == 0) continue;

            $rejectedQuantity = (clone $baseQuery)->where('status', GranilyaProduction::STATUS_REJECTED)->sum('granilya_quantities.quantity') ?? 0;
            
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
                DB::raw('SUM(CASE WHEN granilya_productions.status IS NULL OR granilya_productions.sieve_test_result = "Bekliyor" OR granilya_productions.surface_test_result = "Bekliyor" OR granilya_productions.arge_test_result = "Bekliyor" THEN granilya_quantities.quantity ELSE 0 END) as testing_quantity'),
                DB::raw('SUM(CASE WHEN granilya_productions.status IN ("' . GranilyaProduction::STATUS_CUSTOMER_TRANSFER . '", "' . GranilyaProduction::STATUS_DELIVERED . '") THEN granilya_quantities.quantity ELSE 0 END) as delivery_quantity'),
                DB::raw('SUM(CASE WHEN granilya_productions.status = "' . GranilyaProduction::STATUS_REJECTED . '" THEN granilya_quantities.quantity ELSE 0 END) as rejected_quantity'),
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
        // Simple dummy data logic for UI representation.
        // A true implementation would query barcodes and dates dynamically.
        return [
            'current_date' => Carbon::today('Europe/Istanbul')->format('d.m.Y H:i'),
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
    }
    
    private function getAiInsights($periodInfo)
    {
        // Dummy data structured specifically for the UI.
        return [
            'production_efficiency' => [
                'level' => 'average',
                'oee_score' => 85,
                'availability' => 90,
                'performance' => 80,
                'quality_rate' => 95,
                'total_barcodes' => 0,
                'active_barcodes' => 0,
                'rejected_barcodes' => 0,
                'merged_barcodes' => 0,
                'avg_quantity' => 0
            ],
            'production_forecast' => 0,
            'confidence_level' => 0,
            'trend_direction' => 'up',
            'trend_percentage' => 0,
            'quality_risk_level' => 'low',
            'expected_rejection_rate' => 0,
            'quality_recommendation' => 'Mevcut verilerle tahmin yapılamıyor.',
            'anomalies' => []
        ];
    }
}
