<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GranilyaProduction;
use App\Models\GranilyaProductionHistory;
use Carbon\Carbon;

class LaboratoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Laboratuvar ana ekranı (Dashboard)
     */
    public function dashboard()
    {
        $startDate = request('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate   = request('end_date', now()->format('Y-m-d'));

        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime   = Carbon::parse($endDate)->endOfDay();

        $productionsInPeriod = GranilyaProduction::where(function($q) use ($startDateTime, $endDateTime) {
            $q->whereBetween('updated_at', [$startDateTime, $endDateTime])
              ->orWhere(function($subq) use ($startDateTime, $endDateTime) {
                  $subq->where('status', GranilyaProduction::STATUS_WAITING)
                       ->whereBetween('created_at', [$startDateTime, $endDateTime]);
              });
        })->get();

        $waiting      = $productionsInPeriod->where('status', GranilyaProduction::STATUS_WAITING)->count();
        $preApproved  = $productionsInPeriod->where('status', GranilyaProduction::STATUS_PRE_APPROVED)->count();
        
        $cleanApproved = $productionsInPeriod->filter(function($p) {
            return in_array($p->status, [GranilyaProduction::STATUS_SHIPMENT_APPROVED, 6, 9, 10]) && !$p->is_exceptionally_approved;
        })->count();

        $exceptional   = $productionsInPeriod->where('is_exceptionally_approved', true)->count();
        $corrected     = $productionsInPeriod->where('status', GranilyaProduction::STATUS_CORRECTED)->count();
        $rejected      = $productionsInPeriod->where('status', GranilyaProduction::STATUS_REJECTED)->count();
        
        $finalizedTotal = $cleanApproved + $exceptional + $corrected + $rejected;
        $pendingTotal   = $waiting + $preApproved;
        
        $acceptanceRate = $finalizedTotal > 0
                                ? round((($cleanApproved + $exceptional) / $finalizedTotal) * 100, 1)
                                : 0;

        $stats = [
            'clean_approved' => $cleanApproved,
            'exceptional_approved' => $exceptional,
            'corrected'      => $corrected,
            'rejected'       => $rejected,
            'pending_total'  => $pendingTotal,
            'finalized_total' => $finalizedTotal,
            'acceptance_rate' => $acceptanceRate,
            // Keep legacy keys for temporary compatibility if needed
            'waiting'        => $waiting,
            'pre_approved'   => $preApproved,
            'shipment_approved' => $cleanApproved + $exceptional,
        ];

        $recentActivities = GranilyaProduction::with(['stock', 'quantity', 'user'])
            ->whereIn('status', [
                GranilyaProduction::STATUS_PRE_APPROVED,
                GranilyaProduction::STATUS_SHIPMENT_APPROVED,
                GranilyaProduction::STATUS_REJECTED,
                6, 9, 10
            ])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        $pendingPallets = GranilyaProduction::with(['stock', 'quantity', 'user'])
            ->where(function ($q) {
                $q->whereIn('status', [GranilyaProduction::STATUS_WAITING, GranilyaProduction::STATUS_PRE_APPROVED])
                  ->orWhere(function ($q2) {
                      $q2->where('status', GranilyaProduction::STATUS_REJECTED)
                         ->where(function ($q3) {
                             $q3->where('sieve_test_result', 'Bekliyor')
                                ->orWhere('surface_test_result', 'Bekliyor')
                                ->orWhere('arge_test_result', 'Bekliyor');
                         });
                  });
            })
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        return view('granilya.laboratory.dashboard', compact(
            'stats', 'recentActivities', 'pendingPallets', 'startDate', 'endDate'
        ));
    }


    /**
     * Kapsamlı palet listesi (DataTables)
     */
    public function barcodeList(Request $request)
    {
        if ($request->ajax()) {
            $query = GranilyaProduction::with(['stock', 'quantity', 'user'])
                ->select('granilya_productions.*');

            return \DataTables::of($query)
                ->addColumn('status_badge', function($p) { return $p->status_badge; })
                ->addColumn('test_sieve', function($p) { return $this->testBadge($p->sieve_test_result); })
                ->addColumn('test_surface', function($p) { return $this->testBadge($p->surface_test_result); })
                ->addColumn('test_arge', function($p) { return $this->testBadge($p->arge_test_result); })
                ->rawColumns(['status_badge', 'test_sieve', 'test_surface', 'test_arge'])
                ->make(true);
        }

        return view('granilya.laboratory.barcode-list');
    }

    private function testBadge($value)
    {
        $v = $value ?? 'Bekliyor';
        $cls = $v === 'Onay' ? 'success' : ($v === 'Red' ? 'danger' : 'secondary');
        return '<span class="badge badge-' . $cls . '">' . $v . '</span>';
    }

    /**
     * Toplu işlem ekranı
     */
    public function bulkView()
    {
        $pendingPallets = GranilyaProduction::with(['stock', 'quantity', 'user'])
            ->where(function ($q) {
                $q->whereIn('status', [GranilyaProduction::STATUS_WAITING, GranilyaProduction::STATUS_PRE_APPROVED])
                  ->orWhere(function ($q2) {
                      $q2->where('status', GranilyaProduction::STATUS_REJECTED)
                         ->where(function ($q3) {
                             $q3->where('sieve_test_result', 'Bekliyor')
                                ->orWhere('surface_test_result', 'Bekliyor')
                                ->orWhere('arge_test_result', 'Bekliyor');
                         });
                  });
            })
            ->get();

        return view('granilya.laboratory.bulk-process', compact('pendingPallets'));
    }

    /**
     * Palet detayı (Modal) — test sonuçları + geçmiş
     */
    public function getPalletDetail($id)
    {
        $pallet = GranilyaProduction::with(['stock', 'size', 'crusher', 'quantity', 'user'])->findOrFail($id);

        // Test histories (from history table, filtered by test-related descriptions)
        $histories = GranilyaProductionHistory::with('user')
            ->where('production_id', $id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($h) {
                return [
                    'id'          => $h->id,
                    'user_name'   => $h->user->name ?? 'Bilinmiyor',
                    'description' => $h->description,
                    'status'      => $h->status,
                    'changes'     => $h->changes,
                    'created_at'  => $h->created_at ? $h->created_at->format('d.m.Y H:i') : '-',
                ];
            });

        return response()->json([
            'success'      => true,
            'pallet'       => $pallet,
            'status_label' => $pallet->status_label,
            'status_badge' => $pallet->status_badge,
            'histories'    => $histories,
        ]);
    }

    /**
     * Tek bir paletin test sonucunu günceller ve history kaydeder.
     */
    public function updateTest(Request $request, $id)
    {
        try {
            $request->validate([
                'test_type'    => 'required|in:sieve,surface,arge',
                'result'       => 'required|in:Onay,Red',
                'reject_reason' => 'nullable|string|max:100',
            ]);

            $production = GranilyaProduction::with(['stock', 'quantity'])->findOrFail($id);
            $testType   = $request->test_type;
            $result     = $request->result;
            $reason     = $request->reject_reason;
            $user       = auth()->user();

            $testLabels = ['sieve' => 'Elek', 'surface' => 'Yüzey', 'arge' => 'Arge'];
            $testLabel  = $testLabels[$testType] ?? $testType;

            if ($testType === 'sieve') {
                $production->sieve_test_result  = $result;
                $production->sieve_reject_reason = ($result === 'Red') ? $reason : null;
            } elseif ($testType === 'surface') {
                $production->surface_test_result  = $result;
                $production->surface_reject_reason = ($result === 'Red') ? $reason : null;
            } elseif ($testType === 'arge') {
                $production->arge_test_result = $result;
            }

            $oldStatus = $production->status;
            $production->evaluateTestStatus();
            $newStatus = $production->status;
            $production->save();

            // Activity Log
            $statusList = GranilyaProduction::getStatusList();
            $description = $testLabel . ' testi: ' . $result;
            if ($result === 'Red' && $reason) {
                $description .= ' (' . $reason . ')';
            }

            GranilyaProductionHistory::create([
                'production_id' => $production->id,
                'status'        => $newStatus,
                'user_id'       => $user->id,
                'description'   => $description,
                'changes'       => [
                    'test_type'     => $testType,
                    'test_label'    => $testLabel,
                    'result'        => $result,
                    'reject_reason' => $reason,
                    'old_status'    => $oldStatus,
                    'new_status'    => $newStatus,
                    'old_status_label' => $statusList[$oldStatus] ?? $oldStatus,
                    'new_status_label' => $statusList[$newStatus] ?? $newStatus,
                ],
            ]);

            // Grup kontrolü (1000 KG tamamlandı mı?)
            GranilyaProduction::checkAndCompleteGroup($production->base_pallet_number, $user->id);

            return response()->json([
                'success'  => true,
                'message'  => $testLabel . ' testi ' . $result . ' olarak kaydedildi.',
                'pallet'   => $production->fresh(['stock', 'quantity']),
                'histories' => GranilyaProductionHistory::with('user')
                    ->where('production_id', $id)
                    ->orderByDesc('created_at')
                    ->get()
                    ->map(function($h) {
                        return [
                            'id'          => $h->id,
                            'user_name'   => $h->user->name ?? 'Bilinmiyor',
                            'description' => $h->description,
                            'status'      => $h->status,
                            'changes'     => $h->changes,
                            'created_at'  => $h->created_at ? $h->created_at->format('d.m.Y H:i') : '-',
                        ];
                    })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Toplu işlem kaydı — test sonuçlarını günceller ve log tutar.
     */
    public function processBulk(Request $request)
    {
        try {
            $request->validate([
                'pallets'      => 'required|array|min:1',
                'pallets.*'    => 'exists:granilya_productions,id',
                'test_type'    => 'required|in:sieve,surface,arge',
                'result'       => 'required|in:Onay,Red',
                'reject_reason' => 'nullable|string|max:100',
            ]);

            $testType  = $request->test_type;
            $result    = $request->result;
            $reason    = $request->reject_reason;
            $user      = auth()->user();
            $statusList = GranilyaProduction::getStatusList();
            $testLabels = ['sieve' => 'Elek', 'surface' => 'Yüzey', 'arge' => 'Arge'];
            $testLabel  = $testLabels[$testType] ?? $testType;

            $productions = GranilyaProduction::whereIn('id', $request->pallets)->get();
            $count = 0;

            foreach ($productions as $p) {
                $oldStatus = $p->status;

                if ($testType === 'sieve') {
                    $p->sieve_test_result  = $result;
                    $p->sieve_reject_reason = ($result === 'Red') ? $reason : null;
                } elseif ($testType === 'surface') {
                    $p->surface_test_result  = $result;
                    $p->surface_reject_reason = ($result === 'Red') ? $reason : null;
                } elseif ($testType === 'arge') {
                    $p->arge_test_result = $result;
                }

                $p->evaluateTestStatus();
                $p->save();
                $newStatus = $p->status;

                // Log activity
                $description = '[Toplu] ' . $testLabel . ' testi: ' . $result;
                if ($result === 'Red' && $reason) $description .= ' (' . $reason . ')';

                GranilyaProductionHistory::create([
                    'production_id' => $p->id,
                    'status'        => $newStatus,
                    'user_id'       => $user->id,
                    'description'   => $description,
                    'changes'       => [
                        'test_type'        => $testType,
                        'test_label'       => $testLabel,
                        'result'           => $result,
                        'reject_reason'    => $reason,
                        'old_status'       => $oldStatus,
                        'new_status'       => $newStatus,
                        'old_status_label' => $statusList[$oldStatus] ?? $oldStatus,
                        'new_status_label' => $statusList[$newStatus] ?? $newStatus,
                        'bulk'             => true,
                    ],
                ]);

                $count++;
            }

            // Olası her grup için kontrolü çalıştır (unique base pallet numaraları)
            $basePallets = $productions->map(function($p) { return $p->base_pallet_number; })->unique();
            foreach ($basePallets as $base) {
                GranilyaProduction::checkAndCompleteGroup($base, $user->id);
            }

            return response()->json([
                'success' => true,
                'message' => $count . ' adet palet için ' . $testLabel . ' testi ' . $result . ' olarak kaydedildi.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Reddedilmiş bir paleti "İstisnai Onay" ile Sevk Onaylı durumuna geçirir.
     */
    public function exceptionalApprove(Request $request, $id)
    {
        try {
            $production = GranilyaProduction::findOrFail($id);
            $user = auth()->user();

            if (!$production->isExceptionalAllowed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu palet için istisnai onay verilemez. Tüm laboratuvar testlerinin (Elek, Yüzey, Arge) tamamlanmış ve paletin reddedilmiş olması gereklidir.'
                ], 422);
            }

            $oldStatus = $production->status;
            $production->status = GranilyaProduction::STATUS_SHIPMENT_APPROVED;
            $production->is_exceptionally_approved = true;
            $production->save();
            $newStatus = $production->status;

            // Activity Log
            $statusList = GranilyaProduction::getStatusList();
            GranilyaProductionHistory::create([
                'production_id' => $production->id,
                'status'        => $newStatus,
                'user_id'       => $user->id,
                'description'   => 'İstisnai Onay ile Sevk Onaylı.',
                'changes'       => [
                    'old_status'       => $oldStatus,
                    'new_status'       => $newStatus,
                    'old_status_label' => $statusList[$oldStatus] ?? $oldStatus,
                    'new_status_label' => $statusList[$newStatus] ?? $newStatus,
                ],
            ]);

            // Grup kontrolü (İstisnai onay sonrası 1000 KG kuralı sağlanmış olabilir)
            GranilyaProduction::checkAndCompleteGroup($production->base_pallet_number, $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Palet başarıyla istisnai olarak onaylandı.',
                'pallet'  => $production->fresh(['stock', 'quantity'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Laboratuvar raporu
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        $report = GranilyaProduction::with(['stock', 'user'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->whereIn('status', [
                GranilyaProduction::STATUS_PRE_APPROVED,
                GranilyaProduction::STATUS_SHIPMENT_APPROVED,
                GranilyaProduction::STATUS_REJECTED,
                6, 9, 10
            ])
            ->selectRaw('
                stock_id,
                status,
                user_id,
                COUNT(*) as count,
                DATE(updated_at) as lab_date
            ')
            ->groupBy('stock_id', 'status', 'user_id', 'lab_date')
            ->orderBy('lab_date', 'desc')
            ->get();

        $productions = GranilyaProduction::where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('updated_at', [$startDate, $endDate])
                  ->orWhere(function($subq) use ($startDate, $endDate) {
                      $subq->where('status', GranilyaProduction::STATUS_WAITING)
                           ->whereBetween('created_at', [$startDate, $endDate]);
                  });
            })->get();

        $summary = [
            'total_items' => $productions->count(),
            'clean_approved' => $productions->filter(function($p) {
                return in_array($p->status, [GranilyaProduction::STATUS_SHIPMENT_APPROVED, 6, 9, 10]) && !$p->is_exceptionally_approved;
            })->count(),
            'exceptional_approved' => $productions->where('is_exceptionally_approved', true)->count(),
            'corrected' => $productions->where('status', GranilyaProduction::STATUS_CORRECTED)->count(),
            'rejected' => $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count(),
            'waiting' => $productions->where('status', GranilyaProduction::STATUS_WAITING)->count(),
            'pre_approved' => $productions->where('status', GranilyaProduction::STATUS_PRE_APPROVED)->count(),
        ];
        
        $summary['finalized'] = $summary['clean_approved'] + $summary['exceptional_approved'] + $summary['corrected'] + $summary['rejected'];
        $summary['acceptance_rate'] = $summary['finalized'] > 0 
            ? round((($summary['clean_approved'] + $summary['exceptional_approved']) / $summary['finalized']) * 100, 1) 
            : 0;

        return view('granilya.laboratory.report', compact('report', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Laboratuvar raporu Excel Export
     */
    public function exportReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->startOfDay());
        $endDate = $request->get('end_date', now()->endOfDay());

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        $data = GranilyaProduction::with(['stock', 'user', 'quantity'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->whereIn('status', [
                GranilyaProduction::STATUS_PRE_APPROVED,
                GranilyaProduction::STATUS_SHIPMENT_APPROVED,
                GranilyaProduction::STATUS_REJECTED,
                6, 9, 10
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        $filename = "granilya_laboratuvar_raporu_" . date('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Tarih', 'Palet No', 'Ürün', 'Miktar (KG)', 'Elek Testi', 'Yüzey Testi', 'Arge Testi', 'Durum', 'İşlem Yapan']);

            foreach ($data as $p) {
                fputcsv($file, [
                    $p->updated_at->format('d.m.Y H:i'),
                    $p->pallet_number,
                    $p->stock->name,
                    $p->used_quantity,
                    $p->sieve_test_result,
                    $p->surface_test_result,
                    $p->arge_test_result,
                    $p->status_label,
                    $p->user->name ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Stok Kalite Analizi
     */
    public function stockQualityAnalysis(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfDay());

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        $stockQualityData = \App\Models\Stock::whereHas('granilyaProductions', function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        })->with(['granilyaProductions' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        }])->get()->map(function($stock) {
            $productions = $stock->granilyaProductions;
            $total = $productions->count();
            
            $clean = $productions->filter(function($p) {
                return in_array($p->status, [GranilyaProduction::STATUS_SHIPMENT_APPROVED, 6, 9, 10]) && !$p->is_exceptionally_approved;
            })->count();
            
            $exceptional = $productions->where('is_exceptionally_approved', true)->count();
            $corrected = $productions->where('status', GranilyaProduction::STATUS_CORRECTED)->count();
            $rejected = $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count();
            $pending = $productions->whereIn('status', [GranilyaProduction::STATUS_WAITING, GranilyaProduction::STATUS_PRE_APPROVED])->count();
            
            $finalized = $clean + $exceptional + $corrected + $rejected;
            
            $reasons = [];
            foreach ($productions->where('status', GranilyaProduction::STATUS_REJECTED) as $p) {
                if ($p->sieve_test_result == 'Red') $reasons['Elek: ' . $p->sieve_reject_reason] = ($reasons['Elek: ' . $p->sieve_reject_reason] ?? 0) + 1;
                if ($p->surface_test_result == 'Red') $reasons['Yüzey: ' . $p->surface_reject_reason] = ($reasons['Yüzey: ' . $p->surface_reject_reason] ?? 0) + 1;
                if ($p->arge_test_result == 'Red') $reasons['Arge'] = ($reasons['Arge'] ?? 0) + 1;
            }

            return [
                'stock' => $stock,
                'total' => $total,
                'clean' => $clean,
                'exceptional' => $exceptional,
                'corrected' => $corrected,
                'rejected' => $rejected,
                'pending' => $pending,
                'acceptance_rate' => $finalized > 0 ? round((($clean + $exceptional) / $finalized) * 100, 1) : 0,
                'rejection_reasons' => $reasons,
                'top_reason' => collect($reasons)->sortByDesc(function($v) { return $v; })->keys()->first()
            ];
        })->sortBy('acceptance_rate');

        return view('granilya.laboratory.stock-quality-analysis', compact('stockQualityData', 'startDate', 'endDate'));
    }

    public function stockQualityAnalysisExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfDay());

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        // Similar logic to above but return CSV stream
        $stockQualityData = \App\Models\Stock::whereHas('granilyaProductions', function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        })->with(['granilyaProductions' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        }])->get();

        $filename = "granilya_stok_kalite_analizi_" . date('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($stockQualityData) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Ürün Adı', 'Toplam İşlem', 'Kabul', 'Red', 'Kabul Oranı (%)', 'En Sık Red Sebebi']);

            foreach ($stockQualityData as $stock) {
                $productions = $stock->granilyaProductions;
                $total = $productions->count();
                $accepted = $productions->whereIn('status', [GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED, 6])->count();
                $rejected = $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count();
                
                $reasons = [];
                foreach ($productions->where('status', GranilyaProduction::STATUS_REJECTED) as $p) {
                    if ($p->sieve_test_result == 'Red') $reasons['Elek: ' . $p->sieve_reject_reason] = ($reasons['Elek: ' . $p->sieve_reject_reason] ?? 0) + 1;
                    if ($p->surface_test_result == 'Red') $reasons['Yüzey: ' . $p->surface_reject_reason] = ($reasons['Yüzey: ' . $p->surface_reject_reason] ?? 0) + 1;
                    if ($p->arge_test_result == 'Red') $reasons['Arge'] = ($reasons['Arge'] ?? 0) + 1;
                }
                $topReason = collect($reasons)->sortByDesc(function($v) { return $v; })->keys()->first() ?? '-';

                fputcsv($file, [
                    $stock->name,
                    $total,
                    $accepted,
                    $rejected,
                    $total > 0 ? round(($accepted / $total) * 100, 1) : 0,
                    $topReason
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Kırıcı Performans Analizi
     */
    public function crusherPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfDay());

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        $crusherData = \App\Models\GranilyaCrusher::whereHas('granilyaProductions', function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        })->with(['granilyaProductions' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        }])->get()->map(function($crusher) {
            $productions = $crusher->granilyaProductions;
            $total = $productions->count();
            
            $clean = $productions->filter(function($p) {
                return in_array($p->status, [GranilyaProduction::STATUS_SHIPMENT_APPROVED, 6, 9, 10]) && !$p->is_exceptionally_approved;
            })->count();
            
            $exceptional = $productions->where('is_exceptionally_approved', true)->count();
            $corrected = $productions->where('status', GranilyaProduction::STATUS_CORRECTED)->count();
            $rejected = $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count();
            $pending = $productions->whereIn('status', [GranilyaProduction::STATUS_WAITING, GranilyaProduction::STATUS_PRE_APPROVED])->count();
            
            $finalized = $clean + $exceptional + $corrected + $rejected;

            return [
                'crusher' => $crusher,
                'total' => $total,
                'clean' => $clean,
                'exceptional' => $exceptional,
                'corrected' => $corrected,
                'rejected' => $rejected,
                'pending' => $pending,
                'acceptance_rate' => $finalized > 0 ? round((($clean + $exceptional) / $finalized) * 100, 1) : 0,
            ];
        })->sortByDesc('acceptance_rate');

        return view('granilya.laboratory.crusher-performance', compact('crusherData', 'startDate', 'endDate'));
    }

    public function crusherPerformanceExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfDay());

        if ($startDate) $startDate = Carbon::parse($startDate)->startOfDay();
        if ($endDate) $endDate = Carbon::parse($endDate)->endOfDay();

        $crusherData = \App\Models\GranilyaCrusher::whereHas('granilyaProductions', function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        })->with(['granilyaProductions' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('updated_at', [$startDate, $endDate]);
        }])->get();

        $filename = "granilya_kirici_analizi_" . date('Ymd_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($crusherData) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Kırıcı Adı', 'Toplam İşlem', 'Kabul', 'Red', 'Kabul Oranı (%)']);

            foreach ($crusherData as $crusher) {
                $productions = $crusher->granilyaProductions;
                $total = $productions->count();
                $accepted = $productions->whereIn('status', [GranilyaProduction::STATUS_SHIPMENT_APPROVED, GranilyaProduction::STATUS_CUSTOMER_TRANSFER, GranilyaProduction::STATUS_DELIVERED, 6])->count();
                $rejected = $productions->where('status', GranilyaProduction::STATUS_REJECTED)->count();

                fputcsv($file, [
                    $crusher->name,
                    $total,
                    $accepted,
                    $rejected,
                    $total > 0 ? round(($accepted / $total) * 100, 1) : 0
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
