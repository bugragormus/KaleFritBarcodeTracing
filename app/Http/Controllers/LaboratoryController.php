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

        // Tarih filtreleri (KPI'lar için)
        $startDate = request('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));
        
        $startDateTime = \Carbon\Carbon::parse($startDate)->startOfDay();
        $endDateTime = \Carbon\Carbon::parse($endDate)->endOfDay();

        // Laboratuvar istatistikleri (tarih filtrelenmiş)
        $stats = [
            'waiting' => Barcode::where('status', Barcode::STATUS_WAITING)->count(), // Bekleyen her zaman güncel
            'accepted' => Barcode::where('status', Barcode::STATUS_PRE_APPROVED)
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'rejected' => Barcode::where('status', Barcode::STATUS_REJECTED)
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'total_processed' => Barcode::whereNotNull('lab_at')
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'processed_today' => Barcode::whereNotNull('lab_at')
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'control_repeat' => Barcode::where('status', Barcode::STATUS_CONTROL_REPEAT)
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
                ->count(),
            'shipment_approved' => Barcode::where('status', Barcode::STATUS_SHIPMENT_APPROVED)
                ->whereBetween('lab_at', [$startDateTime, $endDateTime])
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
            $barcodes = Barcode::with(['stock', 'kiln', 'quantity', 'createdBy', 'labBy'])
                ->select('barcodes.*');

            return Datatables::of($barcodes)
                ->addColumn('stock_info', function ($barcode) {
                    return $barcode->stock->code . ' - ' . $barcode->stock->name;
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
                           . Barcode::STATUSES[$barcode->status] . '</span>';
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
                ->rawColumns(['status_badge', 'created_info', 'lab_info', 'actions'])
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
            'note' => 'nullable|string|max:500'
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
            'stock', 'kiln', 'quantity', 'createdBy', 'labBy', 'warehouse', 'company'
        ])->findOrFail($id);

        // JSON response için ilişkileri düzenle
        $barcodeData = $barcode->toArray();
        $barcodeData['created_by'] = $barcode->createdBy;
        $barcodeData['lab_by'] = $barcode->labBy;
        
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
            'status_text' => Barcode::STATUSES[$barcode->status] ?? 'Bilinmiyor'
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
            'note' => 'nullable|string|max:500'
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

        return view('admin.laboratory.report', compact('report', 'summary', 'startDate', 'endDate'));
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
} 