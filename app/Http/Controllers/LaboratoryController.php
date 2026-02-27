<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Stock;
use App\Models\Kiln;
use App\Models\Quantity;
use App\Models\User;
use App\Services\StockCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaboratoryReportExport;

class LaboratoryController extends Controller
{
    protected $stockCalculationService;

    public function __construct(StockCalculationService $stockCalculationService)
    {
        $this->middleware('auth');
        $this->middleware('permission:lab_processes');
        $this->stockCalculationService = $stockCalculationService;
    }

    /**
     * Laboratuvar ana ekranı
     */
    public function dashboard()
    {
        // AJAX istekleri için sadece başarı yanıtı döndür
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Yetki kontrolü başarılı'
            ]);
        }

        // Tarih filtreleri (KPI'lar için) - Türkiye saati ile
        $startDate = request('start_date', now()->tz('Europe/Istanbul')->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', now()->tz('Europe/Istanbul')->format('Y-m-d'));
        
        $startDateTime = \Carbon\Carbon::parse($startDate)->tz('Europe/Istanbul')->startOfDay();
        $endDateTime = \Carbon\Carbon::parse($endDate)->tz('Europe/Istanbul')->endOfDay();

        // Laboratuvar istatistikleri (tarih filtrelenmiş)
        $stats = [
            'waiting' => Barcode::where('status', Barcode::STATUS_WAITING)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->count(),
            'accepted' => Barcode::where('status', Barcode::STATUS_PRE_APPROVED)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->count(),
            'rejected' => Barcode::where('status', Barcode::STATUS_REJECTED)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->count(),
            'total_processed' => Barcode::whereNotNull('lab_at')
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'processed_today' => Barcode::whereNotNull('lab_at')
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'control_repeat' => Barcode::where('status', Barcode::STATUS_CONTROL_REPEAT)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->count(),
            'shipment_approved' => Barcode::where('status', Barcode::STATUS_SHIPMENT_APPROVED)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->count(),
        ];

        // Kabul oranı hesaplama
        $totalProcessed = $stats['total_processed'];
        $acceptedCount = $stats['shipment_approved'];
        $stats['acceptance_rate'] = $totalProcessed > 0 ? round(($acceptedCount / $totalProcessed) * 100, 1) : 0;

        // Son işlemler
        $recentActivities = Barcode::with(['stock', 'kiln', 'quantity', 'labBy'])
            ->whereNotNull('lab_at')
            ->orderByDesc('lab_at')
            ->limit(10)
            ->get();

        // Laboratuvar işlemleri için bekleyen barkodlar (Beklemede, Ön Onaylı, Kontrol Tekrarı)
        $pendingBarcodes = Barcode::with(['stock', 'kiln', 'quantity', 'createdBy'])
            ->whereIn('status', [
                Barcode::STATUS_WAITING,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_CONTROL_REPEAT
            ])
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        return view('admin.laboratory.dashboard', compact('stats', 'recentActivities', 'pendingBarcodes'));
    }

    /**
     * Barkod listesi (DataTables)
     */
    public function barcodeList(Request $request)
    {
        if ($request->ajax()) {
            $barcodes = Barcode::with(['stock', 'kiln', 'quantity', 'createdBy', 'labBy', 'rejectionReasons'])
                ->select('barcodes.*');

            return Datatables::of($barcodes)
                ->addColumn('stock_info', function ($barcode) {
                    return $barcode->stock->name;
                })
                ->addColumn('load_info', function ($barcode) {
                    return 'Fırın: ' . $barcode->kiln->name . ' | Şarj: ' . $barcode->load_number;
                })
                ->addColumn('quantity_info', function ($barcode) {
                    return $barcode->quantity->quantity . ' KG';
                })
                ->addColumn('status_badge', function ($barcode) {
                    $statusClass = [
                        Barcode::STATUS_WAITING => 'badge-warning',
                        Barcode::STATUS_CONTROL_REPEAT => 'badge-info',
                        Barcode::STATUS_PRE_APPROVED => 'badge-success',
                        Barcode::STATUS_SHIPMENT_APPROVED => 'badge-primary',
                        Barcode::STATUS_REJECTED => 'badge-danger',
                        Barcode::STATUS_CUSTOMER_TRANSFER => 'badge-info',
                        Barcode::STATUS_DELIVERED => 'badge-success',
                    ];
                    
                    return '<span class="badge ' . ($statusClass[$barcode->status] ?? 'badge-secondary') . '">' 
                           . Barcode::getStatusName($barcode->status) . '</span>';
                })
                ->addColumn('created_info', function ($barcode) {
                    return $barcode->createdBy->name . '<br><small>' . $barcode->created_at->tz('Europe/Istanbul')->format('d.m.Y H:i') . '</small>';
                })
                ->addColumn('lab_info', function ($barcode) {
                    if ($barcode->labBy && $barcode->lab_at) {
                        return $barcode->labBy->name . '<br><small>' . $barcode->lab_at->tz('Europe/Istanbul')->format('d.m.Y H:i') . '</small>';
                    }
                    return '-';
                })
                ->addColumn('rejection_reasons', function ($barcode) {
                    if ($barcode->status == Barcode::STATUS_REJECTED && $barcode->rejectionReasons->count() > 0) {
                        $reasons = $barcode->rejectionReasons->pluck('name')->toArray();
                        
                        if (count($reasons) <= 2) {
                            // 2 veya daha az sebep varsa hepsini göster
                            return '<span class="badge badge-danger">' . implode(', ', $reasons) . '</span>';
                        } else {
                            // 3 veya daha fazla sebep varsa ilk 2'sini göster + sayı
                            $firstTwo = array_slice($reasons, 0, 2);
                            $remaining = count($reasons) - 2;
                            $fullList = implode(', ', $reasons);
                            
                            return '<span class="badge badge-danger" data-toggle="tooltip" data-placement="top" title="' . $fullList . '">' 
                                   . implode(', ', $firstTwo) . ' +' . $remaining . '</span>';
                        }
                    }
                    return '-';
                })
                ->addColumn('actions', function ($barcode) {
                    $actions = '<div class="btn-group" role="group">';
                    
                    // Beklemede (1) durumundaki barkodlar için
                    if ($barcode->status == Barcode::STATUS_WAITING) {
                        $actions .= '<button class="btn btn-success btn-sm" onclick="processBarcode(' . $barcode->id . ', \'pre_approved\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu ön onaylı olarak işaretle - Kontrol geçti, sevk onayı için hazır">
                                        <i class="fas fa-check"></i>
                                    </button>';
                        $actions .= '<button class="btn btn-info btn-sm" onclick="processBarcode(' . $barcode->id . ', \'control_repeat\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu kontrol tekrarı olarak işaretle - Tekrar kontrol gerekli">
                                        <i class="fas fa-redo"></i>
                                    </button>';
                        $actions .= '<button class="btn btn-danger btn-sm" onclick="processBarcode(' . $barcode->id . ', \'reject\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu reddet - Kontrol geçemedi, işlem durduruldu">
                                        <i class="fas fa-times"></i>
                                    </button>';
                    }
                    // Ön onaylı (3) durumundaki barkodlar için
                    else if ($barcode->status == Barcode::STATUS_PRE_APPROVED) {
                        $actions .= '<button class="btn btn-info btn-sm" onclick="processBarcode(' . $barcode->id . ', \'control_repeat\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu kontrol tekrarı olarak işaretle - Tekrar kontrol gerekli">
                                        <i class="fas fa-redo"></i>
                                    </button>';
                        $actions .= '<button class="btn btn-primary btn-sm" onclick="processBarcode(' . $barcode->id . ', \'shipment_approved\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu sevk onaylı olarak işaretle - Direkt sevk için onaylandı">
                                        <i class="fas fa-shipping-fast"></i>
                                    </button>';
                        $actions .= '<button class="btn btn-danger btn-sm" onclick="processBarcode(' . $barcode->id . ', \'reject\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu reddet - Kontrol geçemedi, işlem durduruldu">
                                        <i class="fas fa-times"></i>
                                    </button>';
                    }
                    // Kontrol tekrarı (2) durumundaki barkodlar için
                    else if ($barcode->status == Barcode::STATUS_CONTROL_REPEAT) {
                        $actions .= '<button class="btn btn-success btn-sm" onclick="processBarcode(' . $barcode->id . ', \'pre_approved\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu ön onaylı olarak işaretle - Kontrol geçti, sevk onayı için hazır">
                                        <i class="fas fa-check"></i>
                                    </button>';
                        $actions .= '<button class="btn btn-danger btn-sm" onclick="processBarcode(' . $barcode->id . ', \'reject\')" 
                                        data-toggle="tooltip" data-placement="top" title="Barkodu reddet - Kontrol geçemedi, işlem durduruldu">
                                        <i class="fas fa-times"></i>
                                    </button>';
                    }
                    // Sevk onaylı (4) veya reddedildi (5) durumundaki barkodlar için sadece görüntüleme
                    else if ($barcode->status == Barcode::STATUS_SHIPMENT_APPROVED || $barcode->status == Barcode::STATUS_REJECTED) {
                        // Hiçbir işlem butonu gösterilmez, sadece görüntüleme
                    }
                    
                    $actions .= '<button class="btn btn-info btn-sm" onclick="viewBarcode(' . $barcode->id . ')" 
                                    data-toggle="tooltip" data-placement="top" title="Barkod detaylarını görüntüle">
                                    <i class="fas fa-eye"></i>
                                </button>';
                    $actions .= '</div>';
                    
                    return $actions;
                })
                ->rawColumns(['status_badge', 'created_info', 'lab_info', 'rejection_reasons', 'actions'])
                ->make(true);
        }

        return view('admin.laboratory.barcode-list');
    }

    /**
     * Barkod işleme (AJAX)
     */
    public function processBarcode(Request $request)
    {
        $request->validate([
            'barcode_id' => 'required|exists:barcodes,id',
            'action' => 'required|in:pre_approved,control_repeat,shipment_approved,reject',
            'note' => 'nullable|string|max:500',
            'rejection_reasons' => 'required_if:action,reject|array',
            'rejection_reasons.*' => 'exists:rejection_reasons,id'
        ]);

        $barcode = Barcode::findOrFail($request->barcode_id);
        
        // İş akışı kontrolü
        $canProcess = true;
        $errorMessage = '';
        
        // Beklemede (1) durumundaki barkodlar için
        if ($barcode->status === Barcode::STATUS_WAITING) {
            if ($request->action === 'shipment_approved') {
                $canProcess = false;
                $errorMessage = 'Beklemede durumundaki barkodlar direkt sevk onaylı yapılamaz!';
            }
        }
        // Ön onaylı (3) durumundaki barkodlar için
        else if ($barcode->status === Barcode::STATUS_PRE_APPROVED) {
            if ($request->action === 'pre_approved') {
                $canProcess = false;
                $errorMessage = 'Ön onaylı durumundaki barkodlar tekrar ön onaylı yapılamaz!';
            }
        }
        // Kontrol tekrarı (2) durumundaki barkodlar için
        else if ($barcode->status === Barcode::STATUS_CONTROL_REPEAT) {
            if ($request->action === 'shipment_approved' || $request->action === 'control_repeat') {
                $canProcess = false;
                $errorMessage = 'Kontrol tekrarı durumundaki barkodlar direkt sevk onaylı veya tekrar kontrol tekrarı yapılamaz!';
            }
        }
        // Sevk onaylı (4) veya reddedildi (5) durumundaki barkodlar için
        else if ($barcode->status === Barcode::STATUS_SHIPMENT_APPROVED || $barcode->status === Barcode::STATUS_REJECTED) {
            $canProcess = false;
            $errorMessage = 'Sevk onaylı veya reddedildi durumundaki barkodlar işlenemez!';
        }
        
        if (!$canProcess) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        }

        // Durumu güncelle - yeni durum yapısına göre
        switch($request->action) {
            case 'pre_approved':
                $barcode->status = Barcode::STATUS_PRE_APPROVED;
                break;
            case 'control_repeat':
                $barcode->status = Barcode::STATUS_CONTROL_REPEAT;
                break;
            case 'shipment_approved':
                $barcode->status = Barcode::STATUS_SHIPMENT_APPROVED;
                break;
            case 'reject':
                $barcode->status = Barcode::STATUS_REJECTED;
                break;
        }
                        $barcode->lab_at = now();
                $barcode->lab_by = Auth::id();
                $barcode->lab_note = $request->note;
                $barcode->save();

                // Red sebeplerini ekle (eğer red işlemi ise)
                if ($request->action === 'reject' && $request->has('rejection_reasons')) {
                    $barcode->rejectionReasons()->sync($request->rejection_reasons);
                } else {
                    // Diğer işlemlerde red sebeplerini temizle
                    $barcode->rejectionReasons()->detach();
                }

        // Geçmiş kaydı oluştur
        \App\Models\BarcodeHistory::create([
            'barcode_id' => $barcode->id,
            'status' => $barcode->status,
            'user_id' => Auth::id(),
            'description' => Barcode::EVENT_UPDATED,
            'changes' => $barcode->getChanges(),
        ]);

        // Tarihleri Türkiye saati olarak formatla
        $barcodeData = $barcode->load(['stock', 'kiln', 'quantity'])->toArray();
        if ($barcodeData['created_at']) {
            $barcodeData['created_at'] = \Carbon\Carbon::parse($barcodeData['created_at'])
                ->setTimezone('Europe/Istanbul')
                ->format('d.m.Y H:i:s');
        }
        if ($barcodeData['lab_at']) {
            $barcodeData['lab_at'] = \Carbon\Carbon::parse($barcodeData['lab_at'])
                ->setTimezone('Europe/Istanbul')
                ->format('d.m.Y H:i:s');
        }

        $actionText = '';
        switch($request->action) {
            case 'pre_approved':
                $actionText = 'ön onaylı olarak işaretlendi';
                break;
            case 'control_repeat':
                $actionText = 'kontrol tekrarı olarak işaretlendi';
                break;
            case 'shipment_approved':
                $actionText = 'sevk onaylı olarak işaretlendi';
                break;
            case 'reject':
                $actionText = 'red edildi';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Barkod başarıyla ' . $actionText,
            'barcode' => $barcodeData
        ]);
    }

    /**
     * Barkod detayı (Modal)
     */
    public function getBarcodeDetail($id)
    {
        $barcode = Barcode::with([
            'stock', 'kiln', 'quantity', 'createdBy', 'labBy', 'warehouse', 'company', 'rejectionReasons'
        ])->findOrFail($id);

        // JSON response için ilişkileri düzenle
        $barcodeData = $barcode->toArray();
        $barcodeData['created_by'] = $barcode->createdBy;
        $barcodeData['lab_by'] = $barcode->labBy;
        $barcodeData['rejection_reasons'] = $barcode->rejectionReasons;
        
        // Tarihleri Türkiye saati olarak formatla
        if ($barcodeData['created_at']) {
            $barcodeData['created_at'] = \Carbon\Carbon::parse($barcodeData['created_at'])
                ->setTimezone('Europe/Istanbul')
                ->format('d.m.Y H:i:s');
        }
        
        if ($barcodeData['lab_at']) {
            $barcodeData['lab_at'] = \Carbon\Carbon::parse($barcodeData['lab_at'])
                ->setTimezone('Europe/Istanbul')
                ->format('d.m.Y H:i:s');
        }

        return response()->json([
            'success' => true,
            'barcode' => $barcodeData,
            'status_text' => Barcode::getStatusName($barcode->status)
        ]);
    }

    /**
     * Toplu işlem ekranı
     */
    public function bulkProcess()
    {
        $pendingBarcodes = Barcode::with(['stock', 'kiln', 'quantity', 'createdBy'])
            ->whereIn('status', [
                Barcode::STATUS_WAITING,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_CONTROL_REPEAT
            ])
            ->orderBy('created_at')
            ->get();

        return view('admin.laboratory.bulk-process', compact('pendingBarcodes'));
    }

    /**
     * Toplu işlem (AJAX)
     */
    public function processBulk(Request $request)
    {
        $request->validate([
            'barcode_ids' => 'required|array',
            'barcode_ids.*' => 'exists:barcodes,id',
            'action' => 'required|in:pre_approved,control_repeat,shipment_approved,reject',
            'note' => 'nullable|string|max:500',
            'rejection_reasons' => 'required_if:action,reject|array',
            'rejection_reasons.*' => 'exists:rejection_reasons,id'
        ]);

        $processed = 0;
        $errors = [];

        foreach ($request->barcode_ids as $barcodeId) {
            try {
                $barcode = Barcode::findOrFail($barcodeId);
                
                // İş akışı kontrolü
                $canProcess = true;
                $errorMessage = '';
                
                // Beklemede (1) durumundaki barkodlar için
                if ($barcode->status === Barcode::STATUS_WAITING) {
                    if ($request->action === 'shipment_approved') {
                        $canProcess = false;
                        $errorMessage = 'Beklemede durumundaki barkodlar direkt sevk onaylı yapılamaz';
                    }
                }
                // Ön onaylı (3) durumundaki barkodlar için
                else if ($barcode->status === Barcode::STATUS_PRE_APPROVED) {
                    if ($request->action === 'pre_approved') {
                        $canProcess = false;
                        $errorMessage = 'Ön onaylı durumundaki barkodlar tekrar ön onaylı yapılamaz';
                    }
                }
                // Kontrol tekrarı (2) durumundaki barkodlar için
                else if ($barcode->status === Barcode::STATUS_CONTROL_REPEAT) {
                    if ($request->action === 'shipment_approved' || $request->action === 'control_repeat') {
                        $canProcess = false;
                        $errorMessage = 'Kontrol tekrarı durumundaki barkodlar direkt sevk onaylı veya tekrar kontrol tekrarı yapılamaz';
                    }
                }
                // Sevk onaylı (4) veya reddedildi (5) durumundaki barkodlar için
                else if ($barcode->status === Barcode::STATUS_SHIPMENT_APPROVED || $barcode->status === Barcode::STATUS_REJECTED) {
                    $canProcess = false;
                    $errorMessage = 'Sevk onaylı veya reddedildi durumundaki barkodlar işlenemez';
                }
                
                if (!$canProcess) {
                    $errors[] = "Barkod #{$barcodeId}: {$errorMessage}";
                    continue;
                }

                switch($request->action) {
                    case 'pre_approved':
                        $barcode->status = Barcode::STATUS_PRE_APPROVED;
                        break;
                    case 'control_repeat':
                        $barcode->status = Barcode::STATUS_CONTROL_REPEAT;
                        break;
                    case 'shipment_approved':
                        $barcode->status = Barcode::STATUS_SHIPMENT_APPROVED;
                        break;
                    case 'reject':
                        $barcode->status = Barcode::STATUS_REJECTED;
                        break;
                }
                $barcode->lab_at = now();
                $barcode->lab_by = Auth::id();
                $barcode->lab_note = $request->note;
                $barcode->save();

                // Red sebeplerini ekle/temizle (toplu işlem)
                if ($request->action === 'reject' && $request->has('rejection_reasons')) {
                    $barcode->rejectionReasons()->sync($request->rejection_reasons);
                } else {
                    $barcode->rejectionReasons()->detach();
                }

                \App\Models\BarcodeHistory::create([
                    'barcode_id' => $barcode->id,
                    'status' => $barcode->status,
                    'user_id' => Auth::id(),
                    'description' => Barcode::EVENT_UPDATED,
                    'changes' => $barcode->getChanges(),
                ]);

                $processed++;
            } catch (\Exception $e) {
                $errors[] = "Barkod #{$barcodeId} işlenirken hata: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'processed' => $processed,
            'errors' => $errors,
            'message' => "{$processed} barkod başarıyla işlendi" . (count($errors) > 0 ? " ({count($errors)} hata)" : "")
        ]);
    }

    /**
     * Laboratuvar raporu
     */
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        }

        $report = Barcode::with(['stock', 'labBy'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->selectRaw('
                stock_id,
                status,
                lab_by,
                COUNT(*) as count,
                DATE(lab_at) as lab_date
            ')
            ->groupBy('stock_id', 'status', 'lab_by', 'lab_date')
            ->orderBy('lab_date', 'desc')
            ->get();

        // Red sebepleri analizi
        $rejectionReasonsAnalysis = Barcode::with(['rejectionReasons', 'stock', 'quantity'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->where('status', Barcode::STATUS_REJECTED)
            ->get()
            ->groupBy('stock_id')
            ->map(function ($barcodes, $stockId) {
                $stock = $barcodes->first()->stock;
                $totalRejected = $barcodes->count();
                $totalRejectedKg = $barcodes->sum('quantity.quantity');
                
                $reasonsBreakdown = [];
                foreach ($barcodes as $barcode) {
                    foreach ($barcode->rejectionReasons as $reason) {
                        if (!isset($reasonsBreakdown[$reason->name])) {
                            $reasonsBreakdown[$reason->name] = ['count' => 0, 'kg' => 0];
                        }
                        $reasonsBreakdown[$reason->name]['count']++;
                        $reasonsBreakdown[$reason->name]['kg'] += $barcode->quantity->quantity ?? 0;
                    }
                }
                
                return [
                    'stock' => $stock,
                    'total_rejected' => $totalRejected,
                    'total_rejected_kg' => $totalRejectedKg,
                    'reasons_breakdown' => $reasonsBreakdown
                ];
            });

        // Genel red sebepleri istatistikleri
        $generalRejectionStats = Barcode::with(['rejectionReasons', 'quantity'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->where('status', Barcode::STATUS_REJECTED)
            ->get()
            ->flatMap(function ($barcode) {
                return $barcode->rejectionReasons->map(function ($reason) use ($barcode) {
                    return [
                        'reason_name' => $reason->name,
                        'kg' => $barcode->quantity->quantity ?? 0
                    ];
                });
            })
            ->groupBy('reason_name')
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total_kg' => $items->sum('kg')
                ];
            })
            ->sortByDesc('count');

        $summary = [
            'total_processed' => Barcode::whereBetween('lab_at', [$startDate, $endDate])->count(),
            'pre_approved' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_PRE_APPROVED)->count(),
            'rejected' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)->count(),
            'waiting' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_WAITING)->count(),
            'control_repeat' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_CONTROL_REPEAT)->count(),
            'shipment_approved' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_SHIPMENT_APPROVED)->count(),
        ];

        return view('admin.laboratory.report', compact(
            'report', 
            'summary', 
            'startDate', 
            'endDate',
            'rejectionReasonsAnalysis',
            'generalRejectionStats'
        ));
    }

    /**
     * Laboratuvar raporunu Excel olarak indir
     */
    public function exportReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        }

        $report = Barcode::with(['stock', 'labBy'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->selectRaw('
                stock_id,
                status,
                lab_by,
                COUNT(*) as count,
                DATE(lab_at) as lab_date
            ')
            ->groupBy('stock_id', 'status', 'lab_by', 'lab_date')
            ->orderBy('lab_date', 'desc')
            ->get();

        $summary = [
            'total_processed' => Barcode::whereBetween('lab_at', [$startDate, $endDate])->count(),
            'accepted' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_PRE_APPROVED)->count(),
            'rejected' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_REJECTED)->count(),
            'waiting' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_WAITING)->count(),
            'control_repeat' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_CONTROL_REPEAT)->count(),
            'shipment_approved' => Barcode::whereBetween('lab_at', [$startDate, $endDate])
                ->where('status', Barcode::STATUS_SHIPMENT_APPROVED)->count(),
        ];

        // Red sebepleri analizi
        $rejectionReasonsAnalysis = Barcode::with(['rejectionReasons', 'stock', 'quantity'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->where('status', Barcode::STATUS_REJECTED)
            ->get()
            ->groupBy('stock_id')
            ->map(function ($barcodes) {
                $stock = $barcodes->first()->stock;
                $totalRejected = $barcodes->count();
                $totalRejectedKg = $barcodes->sum('quantity.quantity');
                $reasonsBreakdown = [];
                foreach ($barcodes as $barcode) {
                    foreach ($barcode->rejectionReasons as $reason) {
                        if (!isset($reasonsBreakdown[$reason->name])) {
                            $reasonsBreakdown[$reason->name] = ['count' => 0, 'kg' => 0];
                        }
                        $reasonsBreakdown[$reason->name]['count']++;
                        $reasonsBreakdown[$reason->name]['kg'] += $barcode->quantity->quantity ?? 0;
                    }
                }
                return [
                    'stock' => $stock,
                    'total_rejected' => $totalRejected,
                    'total_rejected_kg' => $totalRejectedKg,
                    'reasons_breakdown' => $reasonsBreakdown
                ];
            });

        // Genel red sebepleri istatistikleri
        $generalRejectionStats = Barcode::with(['rejectionReasons', 'quantity'])
            ->whereBetween('lab_at', [$startDate, $endDate])
            ->where('status', Barcode::STATUS_REJECTED)
            ->get()
            ->flatMap(function ($barcode) {
                return $barcode->rejectionReasons->map(function ($reason) use ($barcode) {
                    return [
                        'reason_name' => $reason->name,
                        'kg' => $barcode->quantity->quantity ?? 0
                    ];
                });
            })
            ->groupBy('reason_name')
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total_kg' => $items->sum('kg')
                ];
            })
            ->sortByDesc('count');

        $fileName = 'laboratuvar-raporu_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.xlsx';

        return Excel::download(new LaboratoryReportExport(
            $summary,
            $report,
            $generalRejectionStats,
            $rejectionReasonsAnalysis,
            $startDate,
            $endDate
        ), $fileName);
    }

    /**
     * Barkod durumlarını getir (AJAX)
     */
    public function getBarcodeStatuses(Request $request)
    {
        $request->validate([
            'barcode_ids' => 'required|array',
            'barcode_ids.*' => 'exists:barcodes,id'
        ]);

        $statuses = [];
        foreach ($request->barcode_ids as $barcodeId) {
            $barcode = Barcode::find($barcodeId);
            if ($barcode) {
                $statuses[$barcodeId] = $barcode->status;
            }
        }

        return response()->json([
            'success' => true,
            'statuses' => $statuses
        ]);
    }

    /**
     * Stok kalite analizi
     */
    public function stockQualityAnalysis(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        }

        // Tüm stoklar için kalite analizi
        $stockQualityData = \App\Models\Stock::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('lab_at', [$startDate, $endDate]);
        }, 'barcodes.rejectionReasons', 'barcodes.quantity'])
        ->get()
        ->map(function ($stock) use ($startDate, $endDate) {
            $totalBarcodes = $stock->barcodes->count();
            $acceptedBarcodes = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->count();
            $rejectedBarcodes = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            $controlRepeatBarcodes = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            $totalKg = $stock->barcodes->sum('quantity.quantity');
            $acceptedKg = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->sum('quantity.quantity');
            $rejectedKg = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            
            // Red sebepleri analizi
            $rejectionReasons = [];
            foreach ($stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as $barcode) {
                foreach ($barcode->rejectionReasons as $reason) {
                    if (!isset($rejectionReasons[$reason->name])) {
                        $rejectionReasons[$reason->name] = ['count' => 0, 'kg' => 0];
                    }
                    $rejectionReasons[$reason->name]['count']++;
                    $rejectionReasons[$reason->name]['kg'] += $barcode->quantity->quantity ?? 0;
                }
            }
            
            // Kalite oranları
            $acceptanceRate = $totalBarcodes > 0 ? ($acceptedBarcodes / $totalBarcodes) * 100 : 0;
            $rejectionRate = $totalBarcodes > 0 ? ($rejectedBarcodes / $totalBarcodes) * 100 : 0;
            
            return [
                'stock' => $stock,
                'total_barcodes' => $totalBarcodes,
                'accepted_barcodes' => $acceptedBarcodes,
                'rejected_barcodes' => $rejectedBarcodes,
                'control_repeat_barcodes' => $controlRepeatBarcodes,
                'total_kg' => $totalKg,
                'accepted_kg' => $acceptedKg,
                'rejected_kg' => $rejectedKg,
                'acceptance_rate' => $acceptanceRate,
                'rejection_rate' => $rejectionRate,
                'rejection_reasons' => $rejectionReasons,
                'top_rejection_reason' => collect($rejectionReasons)->sortByDesc('count')->keys()->first(),
                'top_rejection_count' => collect($rejectionReasons)->max('count') ?? 0
            ];
        })
        ->filter(function ($data) {
            return $data['total_barcodes'] > 0; // Sadece işlem görmüş stokları göster
        })
        ->sortByDesc('rejection_rate');

        // Genel kalite istatistikleri
        $overallStats = [
            'total_stocks' => $stockQualityData->count(),
            'total_barcodes' => $stockQualityData->sum('total_barcodes'),
            'total_accepted' => $stockQualityData->sum('accepted_barcodes'),
            'total_rejected' => $stockQualityData->sum('rejected_barcodes'),
            'total_kg' => $stockQualityData->sum('total_kg'),
            'total_accepted_kg' => $stockQualityData->sum('accepted_kg'),
            'total_rejected_kg' => $stockQualityData->sum('rejected_kg'),
            'overall_acceptance_rate' => $stockQualityData->sum('total_barcodes') > 0 ? 
                ($stockQualityData->sum('accepted_barcodes') / $stockQualityData->sum('total_barcodes')) * 100 : 0,
            'overall_rejection_rate' => $stockQualityData->sum('total_barcodes') > 0 ? 
                ($stockQualityData->sum('rejected_barcodes') / $stockQualityData->sum('total_barcodes')) * 100 : 0
        ];

        return view('admin.laboratory.stock-quality-analysis', compact(
            'stockQualityData', 
            'overallStats', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Stok kalite analizi Excel export
     */
    public function stockQualityAnalysisExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        }

        // Tüm stoklar için kalite analizi (Excel export için)
        $stockQualityData = \App\Models\Stock::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('lab_at', [$startDate, $endDate]);
        }, 'barcodes.rejectionReasons', 'barcodes.quantity'])
        ->get()
        ->map(function ($stock) use ($startDate, $endDate) {
            $totalBarcodes = $stock->barcodes->count();
            $acceptedBarcodes = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->count();
            $rejectedBarcodes = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            $controlRepeatBarcodes = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            $totalKg = $stock->barcodes->sum('quantity.quantity');
            $acceptedKg = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->sum('quantity.quantity');
            $rejectedKg = $stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            
            // Red sebepleri analizi
            $rejectionReasons = [];
            foreach ($stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as $barcode) {
                foreach ($barcode->rejectionReasons as $reason) {
                    if (!isset($rejectionReasons[$reason->name])) {
                        $rejectionReasons[$reason->name] = ['count' => 0, 'kg' => 0];
                    }
                    $rejectionReasons[$reason->name]['count']++;
                    $rejectionReasons[$reason->name]['kg'] += $barcode->quantity->quantity ?? 0;
                }
            }
            
            // Kalite oranları
            $acceptanceRate = $totalBarcodes > 0 ? ($acceptedBarcodes / $totalBarcodes) * 100 : 0;
            $rejectionRate = $totalBarcodes > 0 ? ($rejectedBarcodes / $totalBarcodes) * 100 : 0;
            
            return [
                'stock' => $stock,
                'total_barcodes' => $totalBarcodes,
                'accepted_barcodes' => $acceptedBarcodes,
                'rejected_barcodes' => $rejectedBarcodes,
                'control_repeat_barcodes' => $controlRepeatBarcodes,
                'total_kg' => $totalKg,
                'accepted_kg' => $acceptedKg,
                'rejected_kg' => $rejectedKg,
                'acceptance_rate' => $acceptanceRate,
                'rejection_rate' => $rejectionRate,
                'rejection_reasons' => $rejectionReasons,
                'top_rejection_reason' => collect($rejectionReasons)->sortByDesc('count')->keys()->first(),
                'top_rejection_count' => collect($rejectionReasons)->max('count') ?? 0
            ];
        })
        ->filter(function ($data) {
            return $data['total_barcodes'] > 0; // Sadece işlem görmüş stokları göster
        })
        ->sortByDesc('rejection_rate');

        // Genel kalite istatistikleri
        $overallStats = [
            'total_stocks' => $stockQualityData->count(),
            'total_barcodes' => $stockQualityData->sum('total_barcodes'),
            'total_accepted' => $stockQualityData->sum('accepted_barcodes'),
            'total_rejected' => $stockQualityData->sum('rejected_barcodes'),
            'total_kg' => $stockQualityData->sum('total_kg'),
            'total_accepted_kg' => $stockQualityData->sum('accepted_kg'),
            'total_rejected_kg' => $stockQualityData->sum('rejected_kg'),
            'overall_acceptance_rate' => $stockQualityData->sum('total_barcodes') > 0 ? 
                ($stockQualityData->sum('accepted_barcodes') / $stockQualityData->sum('total_barcodes')) * 100 : 0,
            'overall_rejection_rate' => $stockQualityData->sum('total_barcodes') > 0 ? 
                ($stockQualityData->sum('rejected_barcodes') / $stockQualityData->sum('total_barcodes')) * 100 : 0
        ];

        // Dosya adına tarih bilgisi ekle
        $fileName = 'stok-kalite-analizi';
        if ($startDate && $endDate) {
            if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                $fileName .= '-' . $startDate->format('d-m-Y');
            } else {
                $fileName .= '-' . $startDate->format('d-m-Y') . '-to-' . $endDate->format('d-m-Y');
            }
        } else {
            $fileName .= '-' . now()->format('d-m-Y');
        }
        $fileName .= '.xlsx';

        return Excel::download(new \App\Exports\StockQualityAnalysisExport(
            $stockQualityData, 
            $overallStats, 
            $startDate, 
            $endDate
        ), $fileName);
    }

    /**
     * Fırın performans analizi
     */
    public function kilnPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        }

        // Tüm fırınlar için performans analizi
        $kilnPerformanceData = \App\Models\Kiln::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('lab_at', [$startDate, $endDate]);
        }, 'barcodes.rejectionReasons', 'barcodes.quantity', 'barcodes.stock'])
        ->get()
        ->map(function ($kiln) use ($startDate, $endDate) {
            $totalBarcodes = $kiln->barcodes->count();
            $acceptedBarcodes = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->count();
            $rejectedBarcodes = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            $controlRepeatBarcodes = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            $totalKg = $kiln->barcodes->sum('quantity.quantity');
            $acceptedKg = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->sum('quantity.quantity');
            $rejectedKg = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            
            // Red sebepleri analizi
            $rejectionReasons = [];
            foreach ($kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as $barcode) {
                foreach ($barcode->rejectionReasons as $reason) {
                    if (!isset($rejectionReasons[$reason->name])) {
                        $rejectionReasons[$reason->name] = ['count' => 0, 'kg' => 0];
                    }
                    $rejectionReasons[$reason->name]['count']++;
                    $rejectionReasons[$reason->name]['kg'] += $barcode->quantity->quantity ?? 0;
                }
            }
            
            // Performans oranları
            $acceptanceRate = $totalBarcodes > 0 ? ($acceptedBarcodes / $totalBarcodes) * 100 : 0;
            $rejectionRate = $totalBarcodes > 0 ? ($rejectedBarcodes / $totalBarcodes) * 100 : 0;
            $efficiencyRate = $totalBarcodes > 0 ? (($acceptedBarcodes + $controlRepeatBarcodes) / $totalBarcodes) * 100 : 0;
            
            // Günlük ortalama üretim
            $dailyAverage = $totalBarcodes > 0 ? $totalBarcodes / max(1, $startDate->diffInDays($endDate)) : 0;
            
            return [
                'kiln' => $kiln,
                'total_barcodes' => $totalBarcodes,
                'accepted_barcodes' => $acceptedBarcodes,
                'rejected_barcodes' => $rejectedBarcodes,
                'control_repeat_barcodes' => $controlRepeatBarcodes,
                'total_kg' => $totalKg,
                'accepted_kg' => $acceptedKg,
                'rejected_kg' => $rejectedKg,
                'acceptance_rate' => $acceptanceRate,
                'rejection_rate' => $rejectionRate,
                'efficiency_rate' => $efficiencyRate,
                'daily_average' => $dailyAverage,
                'rejection_reasons' => $rejectionReasons,
                'top_rejection_reason' => collect($rejectionReasons)->sortByDesc('count')->keys()->first(),
                'top_rejection_count' => collect($rejectionReasons)->max('count') ?? 0
            ];
        })
        ->filter(function ($data) {
            return $data['total_barcodes'] > 0; // Sadece işlem görmüş fırınları göster
        })
        ->sortByDesc('efficiency_rate');

        // Genel fırın performans istatistikleri
        $overallStats = [
            'total_kilns' => $kilnPerformanceData->count(),
            'total_barcodes' => $kilnPerformanceData->sum('total_barcodes'),
            'total_accepted' => $kilnPerformanceData->sum('accepted_barcodes'),
            'total_rejected' => $kilnPerformanceData->sum('rejected_barcodes'),
            'total_kg' => $kilnPerformanceData->sum('total_kg'),
            'total_accepted_kg' => $kilnPerformanceData->sum('accepted_kg'),
            'total_rejected_kg' => $kilnPerformanceData->sum('rejected_kg'),
            'overall_acceptance_rate' => $kilnPerformanceData->sum('total_barcodes') > 0 ? 
                ($kilnPerformanceData->sum('accepted_barcodes') / $kilnPerformanceData->sum('total_barcodes')) * 100 : 0,
            'overall_rejection_rate' => $kilnPerformanceData->sum('total_barcodes') > 0 ? 
                ($kilnPerformanceData->sum('rejected_barcodes') / $kilnPerformanceData->sum('total_barcodes')) * 100 : 0,
            'overall_efficiency_rate' => $kilnPerformanceData->sum('total_barcodes') > 0 ? 
                (($kilnPerformanceData->sum('accepted_barcodes') + $kilnPerformanceData->sum('control_repeat_barcodes')) / $kilnPerformanceData->sum('total_barcodes')) * 100 : 0
        ];

        return view('admin.laboratory.kiln-performance', compact(
            'kilnPerformanceData', 
            'overallStats', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Fırın performans analizi Excel export
     */
    public function kilnPerformanceExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        }
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        }

        // Tüm fırınlar için performans analizi (Excel export için)
        $kilnPerformanceData = \App\Models\Kiln::with(['barcodes' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('lab_at', [$startDate, $endDate]);
        }, 'barcodes.rejectionReasons', 'barcodes.quantity', 'barcodes.stock'])
        ->get()
        ->map(function ($kiln) use ($startDate, $endDate) {
            $totalBarcodes = $kiln->barcodes->count();
            $acceptedBarcodes = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->count();
            $rejectedBarcodes = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            $controlRepeatBarcodes = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            $totalKg = $kiln->barcodes->sum('quantity.quantity');
            $acceptedKg = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->sum('quantity.quantity');
            $rejectedKg = $kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            
            // Red sebepleri analizi
            $rejectionReasons = [];
            foreach ($kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as $barcode) {
                foreach ($barcode->rejectionReasons as $reason) {
                    if (!isset($rejectionReasons[$reason->name])) {
                        $rejectionReasons[$reason->name] = ['count' => 0, 'kg' => 0];
                    }
                    $rejectionReasons[$reason->name]['count']++;
                    $rejectionReasons[$reason->name]['kg'] += $barcode->quantity->quantity ?? 0;
                }
            }
            
            // Performans oranları
            $acceptanceRate = $totalBarcodes > 0 ? ($acceptedBarcodes / $totalBarcodes) * 100 : 0;
            $rejectionRate = $totalBarcodes > 0 ? ($rejectedBarcodes / $totalBarcodes) * 100 : 0;
            $efficiencyRate = $totalBarcodes > 0 ? (($acceptedBarcodes + $controlRepeatBarcodes) / $totalBarcodes) * 100 : 0;
            
            // Günlük ortalama üretim
            $dailyAverage = $totalBarcodes > 0 ? $totalBarcodes / max(1, $startDate->diffInDays($endDate)) : 0;
            
            return [
                'kiln' => $kiln,
                'total_barcodes' => $totalBarcodes,
                'accepted_barcodes' => $acceptedBarcodes,
                'rejected_barcodes' => $rejectedBarcodes,
                'control_repeat_barcodes' => $controlRepeatBarcodes,
                'total_kg' => $totalKg,
                'accepted_kg' => $acceptedKg,
                'rejected_kg' => $rejectedKg,
                'acceptance_rate' => $acceptanceRate,
                'rejection_rate' => $rejectionRate,
                'efficiency_rate' => $efficiencyRate,
                'daily_average' => $dailyAverage,
                'rejection_reasons' => $rejectionReasons,
                'top_rejection_reason' => collect($rejectionReasons)->sortByDesc('count')->keys()->first(),
                'top_rejection_count' => collect($rejectionReasons)->max('count') ?? 0
            ];
        })
        ->filter(function ($data) {
            return $data['total_barcodes'] > 0; // Sadece işlem görmüş fırınları göster
        })
        ->sortByDesc('efficiency_rate');

        // Genel fırın performans istatistikleri
        $overallStats = [
            'total_kilns' => $kilnPerformanceData->count(),
            'total_barcodes' => $kilnPerformanceData->sum('total_barcodes'),
            'total_accepted' => $kilnPerformanceData->sum('accepted_barcodes'),
            'total_rejected' => $kilnPerformanceData->sum('rejected_barcodes'),
            'total_kg' => $kilnPerformanceData->sum('total_kg'),
            'total_accepted_kg' => $kilnPerformanceData->sum('accepted_kg'),
            'total_rejected_kg' => $kilnPerformanceData->sum('total_rejected_kg'),
            'overall_acceptance_rate' => $kilnPerformanceData->sum('total_barcodes') > 0 ? 
                ($kilnPerformanceData->sum('accepted_barcodes') / $kilnPerformanceData->sum('total_barcodes')) * 100 : 0,
            'overall_rejection_rate' => $kilnPerformanceData->sum('total_barcodes') > 0 ? 
                ($kilnPerformanceData->sum('rejected_barcodes') / $kilnPerformanceData->sum('total_barcodes')) * 100 : 0,
            'overall_efficiency_rate' => $kilnPerformanceData->sum('total_barcodes') > 0 ? 
                (($kilnPerformanceData->sum('accepted_barcodes') + $kilnPerformanceData->sum('control_repeat_barcodes')) / $kilnPerformanceData->sum('total_barcodes')) * 100 : 0
        ];

        // Dosya adına tarih bilgisi ekle
        $fileName = 'firin-performans-analizi';
        if ($startDate && $endDate) {
            if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
                $fileName .= '-' . $startDate->format('d-m-Y');
            } else {
                $fileName .= '-' . $startDate->format('d-m-Y') . '-to-' . $endDate->format('d-m-Y');
            }
        } else {
            $fileName .= '-' . now()->format('d-m-Y');
        }
        $fileName .= '.xlsx';

        return Excel::download(new \App\Exports\KilnPerformanceAnalysisExport(
            $kilnPerformanceData, 
            $overallStats, 
            $startDate, 
            $endDate
        ), $fileName);
    }
} 