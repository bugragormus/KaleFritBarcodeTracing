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

        $waiting           = GranilyaProduction::where('status', GranilyaProduction::STATUS_WAITING)
                                ->whereBetween('created_at', [$startDateTime, $endDateTime])->count();
        $preApproved       = GranilyaProduction::where('status', GranilyaProduction::STATUS_PRE_APPROVED)
                                ->whereBetween('created_at', [$startDateTime, $endDateTime])->count();
        $shipmentApproved  = GranilyaProduction::where('status', GranilyaProduction::STATUS_SHIPMENT_APPROVED)
                                ->whereBetween('created_at', [$startDateTime, $endDateTime])->count();
        $rejected          = GranilyaProduction::where('status', GranilyaProduction::STATUS_REJECTED)
                                ->whereBetween('created_at', [$startDateTime, $endDateTime])->count();
        $totalProcessed    = $preApproved + $shipmentApproved + $rejected;
        $acceptanceRate    = $totalProcessed > 0
                                ? round(($shipmentApproved / $totalProcessed) * 100, 1)
                                : 0;

        $stats = [
            'waiting'           => $waiting,
            'pre_approved'      => $preApproved,
            'shipment_approved' => $shipmentApproved,
            'rejected'          => $rejected,
            'total_processed'   => $totalProcessed,
            'acceptance_rate'   => $acceptanceRate,
        ];

        $recentActivities = GranilyaProduction::with(['stock', 'quantity', 'user'])
            ->whereIn('status', [
                GranilyaProduction::STATUS_PRE_APPROVED,
                GranilyaProduction::STATUS_SHIPMENT_APPROVED,
                GranilyaProduction::STATUS_REJECTED
            ])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        $pendingPallets = GranilyaProduction::with(['stock', 'quantity', 'user'])
            ->whereIn('status', [GranilyaProduction::STATUS_WAITING, GranilyaProduction::STATUS_PRE_APPROVED])
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
            ->whereIn('status', [GranilyaProduction::STATUS_WAITING, GranilyaProduction::STATUS_PRE_APPROVED])
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
                    'message' => 'Bu palet için istisnai onay verilemez. Sadece Arge testi sebebiyle reddedilen (elek ve yüzey onaylı) paletler istisnai onay alabilir.'
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
}
