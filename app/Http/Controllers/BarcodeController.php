<?php

namespace App\Http\Controllers;

use App\Exports\BarcodeExport;
use App\Http\Requests\Barcode\BarcodeStoreRequest;
use App\Http\Requests\Barcode\BarcodeUpdateRequest;
use App\Http\Requests\BarcodeMergeRequest;
use App\Models\Barcode;
use App\Models\BarcodeHistory;
use App\Models\Company;
use App\Models\Kiln;
use App\Models\Permission;
use App\Models\Quantity;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use DataTables;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $saveAction = $request->get('result');

        // Debug: Gelen parametreleri logla
        \Log::info('Barcode index request parameters:', $request->all());
        
        // Debug: Date filter parameters specifically
        if ($request->ajax()) {
            \Log::info('Date filter parameters:', [
                'lab_start' => $request->input('lab_start'),
                'lab_end' => $request->input('lab_end'),
                'created_start' => $request->input('created_start'),
                'created_end' => $request->input('created_end'),
            ]);
        }

        if ($request->ajax()) {
            $barcodes = Barcode::query()->with(['stock', 'kiln', 'quantity', 'warehouse', 'company', 'createdBy', 'labBy', 'warehouseTransferredBy', 'deliveredBy'])->select('barcodes.*')
                ->when($request->filled('lab_start'), function (Builder $query) use ($request) {
                    try {
                        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('lab_start'))->startOfDay();
                        $query->where('lab_at', '>=', $date);
                        \Log::info('Lab start date filter applied:', ['date' => $date->format('Y-m-d H:i:s')]);
                    } catch (\Exception $e) {
                        \Log::error('Invalid lab_start date format:', ['date' => $request->input('lab_start'), 'error' => $e->getMessage()]);
                    }
                })
                ->when($request->filled('lab_end'), function (Builder $query) use ($request) {
                    try {
                        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('lab_end'))->endOfDay();
                        $query->where('lab_at', '<=', $date);
                        \Log::info('Lab end date filter applied:', ['date' => $date->format('Y-m-d H:i:s')]);
                    } catch (\Exception $e) {
                        \Log::error('Invalid lab_end date format:', ['date' => $request->input('lab_end'), 'error' => $e->getMessage()]);
                    }
                })
                ->when($request->filled('created_start'), function (Builder $query) use ($request) {
                    try {
                        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('created_start'))->startOfDay();
                        $query->where('created_at', '>=', $date);
                        \Log::info('Created start date filter applied:', ['date' => $date->format('Y-m-d H:i:s')]);
                    } catch (\Exception $e) {
                        \Log::error('Invalid created_start date format:', ['date' => $request->input('created_start'), 'error' => $e->getMessage()]);
                    }
                })
                ->when($request->filled('created_end'), function (Builder $query) use ($request) {
                    try {
                        $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('created_end'))->endOfDay();
                        $query->where('created_at', '<=', $date);
                        \Log::info('Created end date filter applied:', ['date' => $date->format('Y-m-d H:i:s')]);
                    } catch (\Exception $e) {
                        \Log::error('Invalid created_end date format:', ['date' => $request->input('created_end'), 'error' => $e->getMessage()]);
                    }
                })
                ->when($request->filled('status'), function (Builder $query) use ($request) {
                    $status = $request->input('status');
                    $statusId = null;
                    
                    // Türkçe durum adını ID'ye çevir
                    switch($status) {
                        case 'Beklemede':
                            $statusId = Barcode::STATUS_WAITING;
                            break;
                        case 'Kontrol Tekrarı':
                            $statusId = Barcode::STATUS_CONTROL_REPEAT;
                            break;
                        case 'Ön Onaylı':
                            $statusId = Barcode::STATUS_PRE_APPROVED;
                            break;
                        case 'Sevk Onaylı':
                            $statusId = Barcode::STATUS_SHIPMENT_APPROVED;
                            break;
                        case 'Reddedildi':
                            $statusId = Barcode::STATUS_REJECTED;
                            break;
                        case 'Düzeltme Faaliyetinde Kullanıldı':
                            $statusId = Barcode::STATUS_CORRECTED;
                            break;
                        case 'Müşteri Transfer':
                            $statusId = Barcode::STATUS_CUSTOMER_TRANSFER;
                            break;
                        case 'Teslim Edildi':
                            $statusId = Barcode::STATUS_DELIVERED;
                            break;
                        case 'Birleştirildi':
                            $statusId = Barcode::STATUS_MERGED;
                            break;
                    }
                    
                    if ($statusId !== null) {
                        $query->where('status', $statusId);
                        \Log::info('Status filter applied:', ['status' => $status, 'status_id' => $statusId]);
                    }
                })
                ->when($request->filled('stock'), function (Builder $query) use ($request) {
                    $stockName = $request->input('stock');
                    $query->whereHas('stock', function ($q) use ($stockName) {
                        $q->where('name', 'like', '%' . $stockName . '%');
                    });
                    \Log::info('Stock filter applied:', ['stock' => $stockName]);
                })
                ->when($request->filled('kiln'), function (Builder $query) use ($request) {
                    $kilnName = $request->input('kiln');
                    $query->whereHas('kiln', function ($q) use ($kilnName) {
                        $q->where('name', 'like', '%' . $kilnName . '%');
                    });
                    \Log::info('Kiln filter applied:', ['kiln' => $kilnName]);
                })
                ->when($request->filled('warehouse'), function (Builder $query) use ($request) {
                    $warehouseName = $request->input('warehouse');
                    $query->whereHas('warehouse', function ($q) use ($warehouseName) {
                        $q->where('name', 'like', '%' . $warehouseName . '%');
                    });
                    \Log::info('Warehouse filter applied:', ['warehouse' => $warehouseName]);
                })
                ->when($request->filled('company'), function (Builder $query) use ($request) {
                    $companyName = $request->input('company');
                    $query->whereHas('company', function ($q) use ($companyName) {
                        $q->where('name', 'like', '%' . $companyName . '%');
                    });
                    \Log::info('Company filter applied:', ['company' => $companyName]);
                })
                ->when($request->filled('createdBy'), function (Builder $query) use ($request) {
                    $userName = $request->input('createdBy');
                    $query->whereHas('createdBy', function ($q) use ($userName) {
                        $q->where('name', 'like', '%' . $userName . '%');
                    });
                    \Log::info('CreatedBy filter applied:', ['user' => $userName]);
                })
                ->when($request->filled('exceptionally_approved'), function (Builder $query) use ($request) {
                    $exceptionallyApproved = $request->input('exceptionally_approved');
                    if ($exceptionallyApproved === '1' || $exceptionallyApproved === 'true') {
                        $query->where('is_exceptionally_approved', true);
                        \Log::info('Exceptionally approved filter applied: true');
                    } elseif ($exceptionallyApproved === '0' || $exceptionallyApproved === 'false') {
                        $query->where('is_exceptionally_approved', false);
                        \Log::info('Exceptionally approved filter applied: false');
                    }
                })
                ->when($request->filled('returned'), function (Builder $query) use ($request) {
                    $returned = $request->input('returned');
                    if ($returned === '1' || $returned === 'true') {
                        $query->where('is_returned', true);
                        \Log::info('Returned filter applied: true');
                    } elseif ($returned === '0' || $returned === 'false') {
                        $query->where('is_returned', false);
                        \Log::info('Returned filter applied: false');
                    }
                });
            return Datatables::of($barcodes)
                ->addColumn('stock', function ($barcode) {
                    return $barcode->stock->code . ' --- ' . $barcode->stock->name;
                })
                ->addColumn('loadNumber', function ($barcode) {
                    $additionalInfo = [];
                    
                    if (!is_null($barcode->rejected_load_number) && !empty($barcode->rejected_load_number)) {
                        $rejectedLoadNumbers = [];
                        
                        // Yeni yöntem: Note alanından birleştirilen şarj numaralarını al
                        if ($barcode->note && strpos($barcode->note, 'Birleştirilen Şarjlar:') !== false) {
                            $noteParts = explode('|', $barcode->note);
                            $group1LoadNumbers = [];
                            $group2LoadNumbers = [];
                            
                            foreach ($noteParts as $part) {
                                if (strpos($part, 'Grup 1 (Kaynak):') !== false) {
                                    $group1IdsPart = trim(str_replace('Grup 1 (Kaynak):', '', $part));
                                    $group1Ids = array_map('trim', explode(',', $group1IdsPart));
                                    // Bu ID'lere sahip barkodların şarj numaralarını al
                                    $group1Barcodes = Barcode::whereIn('id', $group1Ids)->pluck('load_number')->toArray();
                                    $group1LoadNumbers = $group1Barcodes;
                                }
                                if (strpos($part, 'Grup 2 (Hedef):') !== false) {
                                    $group2IdsPart = trim(str_replace('Grup 2 (Hedef):', '', $part));
                                    $group2Ids = array_map('trim', explode(',', $group2IdsPart));
                                    // Bu ID'lere sahip barkodların şarj numaralarını al
                                    $group2Barcodes = Barcode::whereIn('id', $group2Ids)->pluck('load_number')->toArray();
                                    $group2LoadNumbers = $group2Barcodes;
                                }
                            }
                            
                            if (!empty($group1LoadNumbers) && !empty($group2LoadNumbers)) {
                                sort($group1LoadNumbers);
                                sort($group2LoadNumbers);
                                $rejectedLoadNumbers = array_merge($group1LoadNumbers, $group2LoadNumbers);
                                $group1Str = implode(', ', $group1LoadNumbers);
                                $group2Str = implode(', ', $group2LoadNumbers);
                                $additionalInfo[] = 'Kaynak: ' . $group1Str . ' → Hedef: ' . $group2Str;
                            }
                            
                            // Eski format için fallback
                            foreach ($noteParts as $part) {
                                if (strpos($part, 'Birleştirilen Şarjlar:') !== false) {
                                    $loadNumbersPart = trim(str_replace('Birleştirilen Şarjlar:', '', $part));
                                    $rejectedLoadNumbers = array_map('trim', explode(',', $loadNumbersPart));
                                    break;
                                }
                            }
                        }
                        
                        // Eğer note alanından şarj numaraları bulunamadıysa, eski yöntemi kullan
                        if (empty($rejectedLoadNumbers)) {
                            // Eski yöntem: rejected_load_number alanından oku
                            if (strpos($barcode->rejected_load_number, ',') !== false) {
                                $rejectedLoadNumbers = explode(',', $barcode->rejected_load_number);
                            } else {
                                $rejectedLoadNumbers = [$barcode->rejected_load_number];
                            }
                        }
                        
                        // Şarj numaralarını temizle ve sırala
                        $rejectedLoadNumbers = array_map('trim', $rejectedLoadNumbers);
                        sort($rejectedLoadNumbers);
                        
                        if (count($rejectedLoadNumbers) > 1) {
                            $additionalInfo[] = 'Birleştirilen: ' . implode(', ', $rejectedLoadNumbers);
                        } else {
                            $additionalInfo[] = 'Birleştirilen: ' . $rejectedLoadNumbers[0];
                        }
                    }
                    
                    // Eğer bu barkod düzeltme faaliyetinde kullanılan kaynak barkodlardan oluşmuşsa
                    if ($barcode->is_correction && $barcode->correction_source_barcode_id) {
                        $sourceBarcode = Barcode::find($barcode->correction_source_barcode_id);
                        if ($sourceBarcode) {
                            $additionalInfo[] = 'Düzeltme Kaynağı: ' . $sourceBarcode->load_number;
                        }
                    }
                    

                    
                    if (!empty($additionalInfo)) {
                        return $barcode->load_number . ' <small class="text-muted">(' . implode(' | ', $additionalInfo) . ')</small>';
                    }
                    
                    return $barcode->load_number;
                })
                ->addColumn('barcodeId', function ($barcode) {
                    $additionalInfo = [];
                    
                    // Eğer bu barkod birleştirilmiş barkodlardan oluşmuşsa
                    if (!is_null($barcode->rejected_load_number) && !empty($barcode->rejected_load_number)) {
                        $mergedBarcodeIds = [];
                        
                        // Yeni yöntem: Note alanından birleştirilen barkod ID'lerini al
                        if ($barcode->note && strpos($barcode->note, 'Birleştirilen Barkod ID\'leri:') !== false) {
                            $noteParts = explode('|', $barcode->note);
                            $group1BarcodeIds = [];
                            $group2BarcodeIds = [];
                            
                            foreach ($noteParts as $part) {
                                if (strpos($part, 'Grup 1 (Kaynak):') !== false) {
                                    $group1IdsPart = trim(str_replace('Grup 1 (Kaynak):', '', $part));
                                    $group1BarcodeIds = array_map('trim', explode(',', $group1IdsPart));
                                }
                                if (strpos($part, 'Grup 2 (Hedef):') !== false) {
                                    $group2IdsPart = trim(str_replace('Grup 2 (Hedef):', '', $part));
                                    $group2BarcodeIds = array_map('trim', explode(',', $group2IdsPart));
                                }
                            }
                            
                            if (!empty($group1BarcodeIds) && !empty($group2BarcodeIds)) {
                                sort($group1BarcodeIds);
                                sort($group2BarcodeIds);
                                $group1Str = implode(', ', $group1BarcodeIds);
                                $group2Str = implode(', ', $group2BarcodeIds);
                                $additionalInfo[] = 'Birleştirilen: ' . $group1Str . ' → ' . $group2Str;
                            }
                            
                            // Eski format için fallback
                            foreach ($noteParts as $part) {
                                if (strpos($part, 'Birleştirilen Barkod ID\'leri:') !== false) {
                                    $barcodeIdsPart = trim(str_replace('Birleştirilen Barkod ID\'leri:', '', $part));
                                    $mergedBarcodeIds = array_map('trim', explode(',', $barcodeIdsPart));
                                    break;
                                }
                            }
                        }
                        
                        // Eğer note alanından ID'ler bulunamadıysa, eski yöntemi kullan
                        if (empty($mergedBarcodeIds)) {
                            // Şarj numaralarından barkod ID'lerini bul
                            if (strpos($barcode->rejected_load_number, ',') !== false) {
                                $rejectedLoadNumbers = explode(',', $barcode->rejected_load_number);
                                
                                foreach ($rejectedLoadNumbers as $loadNumber) {
                                    $mergedBarcode = Barcode::where('load_number', trim($loadNumber))
                                        ->where('status', Barcode::STATUS_MERGED)
                                        ->first();
                                    if ($mergedBarcode) {
                                        $mergedBarcodeIds[] = $mergedBarcode->id;
                                    }
                                }
                            } else {
                                // Tek şarj numarası varsa
                                $mergedBarcode = Barcode::where('load_number', $barcode->rejected_load_number)
                                    ->where('status', Barcode::STATUS_MERGED)
                                    ->first();
                                if ($mergedBarcode) {
                                    $mergedBarcodeIds[] = $mergedBarcode->id;
                                }
                            }
                        }
                        
                        if (!empty($mergedBarcodeIds)) {
                            $additionalInfo[] = 'Birleştirilen: ' . implode(', ', $mergedBarcodeIds);
                        }
                    }
                    
                    // Eğer bu barkod düzeltme faaliyetinde kullanılan kaynak barkodlardan oluşmuşsa
                    if ($barcode->is_correction && $barcode->correction_source_barcode_id) {
                        $sourceBarcode = Barcode::find($barcode->correction_source_barcode_id);
                        if ($sourceBarcode) {
                            $additionalInfo[] = 'Düzeltme Kaynağı: ' . $sourceBarcode->id . ' (' . $sourceBarcode->load_number . ')';
                        }
                    }
                    

                    
                    if (!empty($additionalInfo)) {
                        return $barcode->id . ' <small class="text-muted">(' . implode(' | ', $additionalInfo) . ')</small>';
                    }
                    
                    return $barcode->id;
                })
                ->addColumn('exceptionallyApproved', function ($barcode) {
                    if ($barcode->is_exceptionally_approved) {
                        return '<span class="badge badge-warning" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529;">
                            <i class="fas fa-exclamation-triangle"></i> İstisnai Onaylı
                        </span>';
                    } else {
                        return '<span class="badge badge-secondary">Normal</span>';
                    }
                })
                ->addColumn('status', function ($barcode) {
                    $statusClass = '';
                    switch($barcode->status) {
                        case Barcode::STATUS_WAITING: 
                            $statusClass = 'status-waiting'; 
                            break;
                        case Barcode::STATUS_CONTROL_REPEAT: 
                            $statusClass = 'status-control-repeat'; 
                            break;
                        case Barcode::STATUS_PRE_APPROVED: 
                            $statusClass = 'status-pre-approved'; 
                            break;
                        case Barcode::STATUS_SHIPMENT_APPROVED: 
                            $statusClass = 'status-shipment-approved'; 
                            break;
                        case Barcode::STATUS_REJECTED: 
                            $statusClass = 'status-rejected'; 
                            break;
                        case Barcode::STATUS_CUSTOMER_TRANSFER: 
                            $statusClass = 'status-customer-transfer'; 
                            break;
                        case Barcode::STATUS_DELIVERED: 
                            $statusClass = 'status-delivered'; 
                            break;
                        case Barcode::STATUS_MERGED: 
                            $statusClass = 'status-merged'; 
                            break;
                        default: 
                            $statusClass = 'status-waiting';
                    }
                    
                    return '<span class="status-badge ' . $statusClass . '">' . Barcode::STATUSES[$barcode->status] . '</span>';
                })
                ->addColumn('quantity', function ($barcode) {
                    return $barcode->quantity->quantity . " KG";
                })
                ->addColumn('kiln', function ($barcode) {
                    return $barcode->kiln->name;
                })
                ->addColumn('warehouse', function ($barcode) {
                    return $barcode->warehouse->name;
                })
                ->addColumn('company', function ($barcode) {
                    return $barcode->company->name;
                })
                ->addColumn('createdBy', function ($barcode) {
                    return $barcode->createdBy->name;
                })
                ->addColumn('lab_at', function ($barcode) {
                    return $barcode->lab_at ? $barcode->lab_at->tz('Europe/Istanbul')->format('d.m.Y H:i') : '-';
                })
                ->addColumn('createdAt', function ($barcode) {
                    return $barcode->created_at->tz('Europe/Istanbul')->format('d.m.Y H:i');
                })
                ->addColumn('labBy', function ($barcode) {
                    return $barcode->labBy ? $barcode->labBy->name : '-';
                })
                ->addColumn('warehouseTransferredBy', function ($barcode) {
                    return $barcode->warehouseTransferredBy ? $barcode->warehouseTransferredBy->name : '-';
                })
                ->addColumn('deliveredBy', function ($barcode) {
                    return $barcode->deliveredBy ? $barcode->deliveredBy->name : '-';
                })
                ->addColumn('warehouseTransferredAt', function ($barcode) {
                    return $barcode->warehouse_transferred_at ? $barcode->warehouse_transferred_at->tz('Europe/Istanbul')->format('d.m.Y H:i') : '-';
                })
                ->addColumn('quantity', function ($barcode) {
                    return $barcode->quantity ? $barcode->quantity->quantity . ' KG' : '-';
                })
                ->addColumn('companyTransferredAt', function ($barcode) {
                    return $barcode->company_transferred_at ? $barcode->company_transferred_at->tz('Europe/Istanbul')->format('d.m.Y H:i') : '-';
                })
                ->addColumn('note', function ($barcode) {
                    return $barcode->note ? (strlen($barcode->note) > 30 ? substr($barcode->note, 0, 30) . '...' : $barcode->note) : '-';
                })
                ->addColumn('labNote', function ($barcode) {
                    return $barcode->lab_note ? (strlen($barcode->lab_note) > 30 ? substr($barcode->lab_note, 0, 30) . '...' : $barcode->lab_note) : '-';
                })
                ->addColumn('isMerged', function ($barcode) {
                    return $barcode->is_merged ? '<span class="badge badge-success">Evet</span>' : '<span class="badge badge-secondary">Hayır</span>';
                })
                ->addColumn('isCorrection', function ($barcode) {
                    if ($barcode->is_correction) {
                        $sourceBarcode = Barcode::find($barcode->correction_source_barcode_id);
                        $sourceInfo = $sourceBarcode ? " (Kaynak: {$sourceBarcode->id})" : '';
                        return '<span class="badge badge-warning">Evet' . $sourceInfo . '</span>';
                    } else {
                        return '<span class="badge badge-secondary">Hayır</span>';
                    }
                })
                ->addColumn('processingTime', function ($barcode) {
                    if ($barcode->lab_at && $barcode->created_at) {
                        $diffInSeconds = $barcode->lab_at->diffInSeconds($barcode->created_at);
                        
                        if ($diffInSeconds < 60) {
                            return $diffInSeconds . ' saniye';
                        } elseif ($diffInSeconds < 3600) {
                            $minutes = floor($diffInSeconds / 60);
                            $seconds = $diffInSeconds % 60;
                            return $minutes . ' dakika ' . $seconds . ' saniye';
                        } elseif ($diffInSeconds < 86400) {
                            $hours = floor($diffInSeconds / 3600);
                            $minutes = floor(($diffInSeconds % 3600) / 60);
                            return $hours . ' saat ' . $minutes . ' dakika';
                        } else {
                            $days = floor($diffInSeconds / 86400);
                            $hours = floor(($diffInSeconds % 86400) / 3600);
                            $minutes = floor(($diffInSeconds % 3600) / 60);
                            return $days . ' gün ' . $hours . ' saat ' . $minutes . ' dakika';
                        }
                    }
                    return '-';
                })
                ->addColumn('action', function($barcode){
                    $historyUrl = route('barcode.history', ['barkod' => $barcode->id]);
                    $editUrl = route('barcode.edit', ['barkod' => $barcode->id]);
                    $deleteUrl = route('barcode.destroy', $barcode->id);
                    return "<div class='btn-group-modern' role='group'>
                        <a class='btn-modern btn-success-modern btn-xs-modern' data-value='$barcode->id' href='$historyUrl' title='Hareket Geçmişi'>
                            <i class='fas fa-history'></i> Hareketler
                        </a>
                        <a class='btn-modern btn-info-modern btn-xs-modern' data-value='$barcode->id' href='$editUrl' title='Barkod Detayı'>
                            <i class='fas fa-eye'></i> Detay
                        </a>
                        <button class='btn-modern btn-danger-modern btn-xs-modern' data-id='$barcode->id' data-action='$deleteUrl' onclick='deleteConfirmation($barcode->id)' title='Barkodu Sil'>
                            <i class='fas fa-trash'></i> Sil
                        </button>
                    </div>";
                })
                ->rawColumns(['action', 'status', 'isMerged', 'isCorrection', 'barcodeId', 'loadNumber'])
                ->make(true);
        }

        $stocks = Stock::all();
        $kilns = Kiln::all();
        $quantities = Quantity::all();
        $companies = Company::all();
        $wareHouses = Warehouse::all();
        $users = User::all();

        $barcodes = Barcode::with([
            'stock',
            'kiln',
            'quantity',
            'company',
            'createdBy'
        ])
            ->orderByDesc('created_at')
            ->when($request->filled('stock_id'), function (Builder $query) use ($request) {
                $query->where('stock_id', $request->input('stock_id'));
            })
            ->when($request->filled('kiln_id'), function (Builder $query) use ($request) {
                $query->where('kiln_id', $request->input('kiln_id'));
            })
            ->when($request->filled('party_number'), function (Builder $query) use ($request) {
                $query->where('party_number', $request->input('party_number'));
            })
            ->when($request->filled('quantity_id'), function (Builder $query) use ($request) {
                $query->where('quantity_id', $request->input('quantity_id'));
            })
            ->when($request->filled('company_id'), function (Builder $query) use ($request) {
                $query->where('company_id', $request->input('company_id'));
            })
            ->when($request->filled('warehouse_id'), function (Builder $query) use ($request) {
                $query->where('warehouse_id', $request->input('warehouse_id'));
            })
            ->when($request->filled('note'), function (Builder $query) use ($request) {
                $query->where('note', 'like', '%' . $request->input('note') . '%');
            })
            ->when($request->filled('start_created_at'), function (Builder $query) use ($request) {
                $query->where('created_at', '>=', $request->input('start_created_at'));
            })
            ->when($request->filled('end_created_at'), function (Builder $query) use ($request) {
                $query->where('created_at', '<=', $request->input('end_created_at'));
            })
            ->when($request->filled('start_lab_at'), function (Builder $query) use ($request) {
                $query->where('lab_at', '>=', $request->input('start_lab_at'));
            })
            ->when($request->filled('end_lab_at'), function (Builder $query) use ($request) {
                $query->where('lab_at', '<=', $request->input('end_lab_at'));
            })
            ->when($request->filled('lab_start'), function (Builder $query) use ($request) {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('lab_start'))->startOfDay();
                $query->where('lab_at', '>=', $date);
            })
            ->when($request->filled('lab_end'), function (Builder $query) use ($request) {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('lab_end'))->endOfDay();
                $query->where('lab_at', '<=', $date);
            })
            ->when($request->filled('created_start'), function (Builder $query) use ($request) {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('created_start'))->startOfDay();
                $query->where('created_at', '>=', $date);
            })
            ->when($request->filled('created_end'), function (Builder $query) use ($request) {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('created_end'))->endOfDay();
                $query->where('created_at', '<=', $date);
            })
            ->when($request->filled('created_by_id'), function (Builder $query) use ($request) {
                $query->where('created_by', $request->input('created_by_id'));
            })
            ->when($request->filled('status'), function (Builder $query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->get();

        if($saveAction === 'excel_download') {
            return Excel::download(new BarcodeExport($barcodes), 'barkodlar-'.Carbon::now()->format('d-m-Y H:i').'.xlsx');
        }

        return view('admin.barcode.index', compact([
            'stocks',
            'kilns',
            'quantities',
            'companies',
            'wareHouses',
            'users',
            'barcodes'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasPermission(Permission::BARCODE_CREATE)) {
            toastr()->error('Barkod oluşturma izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $stocks = Stock::query()->orderBy('name')->get();
        $kilns = Kiln::all();
        $quantities = Quantity::all();
        $warehouses = Warehouse::all();

        // Düzeltme faaliyeti için reddedilen barkodları getir
        $rejectedBarcodes = Barcode::where('status', Barcode::STATUS_REJECTED)
            ->whereDoesntHave('corrections') // Henüz düzeltme faaliyetinde kullanılmamış
            ->with(['stock', 'kiln', 'quantity'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.barcode.create', compact([
            'stocks',
            'kilns',
            'quantities',
            'warehouses',
            'rejectedBarcodes'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Barcode\BarcodeStoreRequest $request
     * @return string
     */
    public function store(BarcodeStoreRequest $request)
    {
        if (!auth()->user()->hasPermission(Permission::BARCODE_CREATE)) {
            toastr()->error('Barkod oluşturma izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $data = $request->validated();
        $kiln = Kiln::where('id', $data['kiln_id'])->firstOrFail();
        $data['created_by'] = auth()->user()->id;

        $barcodeIds = [];
        $totalCorrectionQuantity = 0;

        // Düzeltme faaliyeti kontrolü
        if ($request->filled('correction_barcodes') && is_array($request->input('correction_barcodes'))) {
            $correctionBarcodes = $request->input('correction_barcodes');
            $correctionQuantities = $request->input('correction_quantities', []);
            
            // Düzeltme barkodlarını oluştur
            foreach ($correctionBarcodes as $index => $sourceBarcodeId) {
                if (!empty($sourceBarcodeId) && !empty($correctionQuantities[$index])) {
                    $sourceBarcode = Barcode::findOrFail($sourceBarcodeId);
                    $correctionQuantity = (int) $correctionQuantities[$index];
                    
                    // Düzeltme barkodu oluştur
                    $correctionData = [
                        'stock_id' => $sourceBarcode->stock_id,
                        'kiln_id' => $data['kiln_id'],
                        'party_number' => $data['party_number'],
                        'load_number' => ++$kiln->load_number,
                        'quantity_id' => $sourceBarcode->quantity_id,
                        'warehouse_id' => $data['warehouse_id'],
                        'created_by' => auth()->user()->id,
                        'status' => Barcode::STATUS_WAITING, // Açıkça status belirt
                        'is_correction' => true,
                        'correction_source_barcode_id' => $sourceBarcodeId,
                        'correction_quantity' => $correctionQuantity,
                        'correction_note' => 'Düzeltme faaliyeti: ' . $sourceBarcode->load_number . ' şarjından ' . $correctionQuantity . ' KG',
                        'note' => $data['note'] ?? ''
                    ];

                    $correctionBarcode = Barcode::create($correctionData);
                    $barcodeIds[] = $correctionBarcode->id;
                    $totalCorrectionQuantity += $correctionQuantity;

                    // Barcode history oluştur
                    try {
                        BarcodeHistory::create([
                            'barcode_id' => $correctionBarcode->id,
                            'status' => $correctionBarcode->status ?? Barcode::STATUS_WAITING,
                            'user_id' => auth()->user()->id,
                            'description' => Barcode::EVENT_CREATED,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('BarcodeHistory creation error for correction barcode: ' . $e->getMessage());
                        // Hata durumunda devam et, barkod oluşturuldu
                    }

                    // Kaynak barkodu düzeltme faaliyetinde kullanıldı olarak işaretle
                    $sourceBarcode->update([
                        'status' => Barcode::STATUS_CORRECTED,
                        'note' => ($sourceBarcode->note ? $sourceBarcode->note . ' | ' : '') . 
                                 'Düzeltme faaliyetinde kullanıldı: ' . $correctionBarcode->load_number . ' şarjı'
                    ]);

                    // NOT: Quantity bilgisine dokunulmuyor, sadece status değiştiriliyor
                    // Bu sayede merge işleminde quantity bilgisi korunuyor

                    // Kaynak barkod için history kaydı oluştur
                    try {
                        BarcodeHistory::create([
                            'barcode_id' => $sourceBarcode->id,
                            'status' => Barcode::STATUS_CORRECTED,
                            'user_id' => auth()->user()->id,
                            'description' => 'Düzeltme faaliyetinde kullanıldı',
                            'changes' => [
                                'old_status' => Barcode::STATUS_REJECTED,
                                'new_status' => Barcode::STATUS_CORRECTED,
                                'correction_quantity' => $correctionQuantity,
                                'correction_barcode_id' => $correctionBarcode->id,
                                'note' => 'Quantity bilgisi korundu, sadece status değiştirildi'
                            ]
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('BarcodeHistory creation error for source barcode: ' . $e->getMessage());
                    }
                }
            }
        }

        // Normal üretim barkodlarını oluştur
        $normalQuantity = $data['quantity'] ?? 0;
        for ($i = 0; $i < $normalQuantity; $i++) {
            $data['load_number'] = ++$kiln->load_number;
            $data['is_correction'] = false;
            $data['status'] = Barcode::STATUS_WAITING; // Açıkça status belirt

            $barcode = Barcode::create($data);
            $barcodeIds[] = $barcode->id;

            // Barcode history oluştur
            try {
                BarcodeHistory::create([
                    'barcode_id' => $barcode->id,
                    'status' => $barcode->status ?? Barcode::STATUS_WAITING,
                    'user_id' => auth()->user()->id,
                    'description' => Barcode::EVENT_CREATED,
                ]);
            } catch (\Exception $e) {
                \Log::error('BarcodeHistory creation error for normal barcode: ' . $e->getMessage());
                // Hata durumunda devam et, barkod oluşturuldu
            }
        }

        // Fırın şarj numarasını güncelle
        $kiln->update(['load_number' => $kiln->load_number]);

        // Başarı mesajı
        $message = 'Barkod başarıyla oluşturuldu.';
        if ($totalCorrectionQuantity > 0) {
            $message .= ' Düzeltme faaliyeti: ' . $totalCorrectionQuantity . ' KG reddedilen malzeme kullanıldı.';
        }
        if ($normalQuantity > 0) {
            $message .= ' Yeni üretim: ' . $normalQuantity . ' barkod.';
        }

        toastr()->success($message);

        return isset($data['print'])
            ? redirect()->route('barcode.print', ['barcode_ids' => $barcodeIds])
            : redirect()->route('barcode.index');
    }

    /**
     * Display the specified resource.
     *
     * @param $stockCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($stockCode): JsonResponse
    {
        $data = Barcode::where('stock_code', $stockCode)->first();

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barcode = Barcode::with(['rejectionReasons'])->findOrFail($id);

        $wareHouses = Warehouse::all();

        $companies = Company::all();

        $rejectionReasons = \App\Models\RejectionReason::active()->get();

        return view('admin.barcode.edit', compact([
            'barcode',
            'wareHouses',
            'companies',
            'rejectionReasons'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Barcode\BarcodeUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(BarcodeUpdateRequest $request, $id)
    {
        $data = $request->validated();

        $barcode = Barcode::findOrFail($id);

        // Status değişikliği kontrolü - laboratuvar kurallarına uygun
        if (isset($data['status']) && $barcode->status !== $data['status']) {
            // Durum geçiş kontrolü
            if (!$barcode->canTransitionTo($data['status'])) {
                $currentStatus = Barcode::STATUSES[$barcode->status] ?? 'Bilinmiyor';
                $newStatus = Barcode::STATUSES[$data['status']] ?? 'Bilinmiyor';
                toastr()->error("Geçersiz durum geçişi: {$currentStatus} durumundan {$newStatus} durumuna geçiş yapılamaz.");
                return back()->withInput();
            }

            // Müşteri Transfer veya Teslim Edildi durumlarında firma zorunlu, depo temizle
            if (in_array($data['status'], [Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])) {
                if (empty($data['company_id'])) {
                    toastr()->error('Müşteri Transfer veya Teslim Edildi durumunda firma seçimi zorunludur.');
                    return back()->withInput();
                }
                $data['warehouse_id'] = null; // Depo bilgisini temizle
            } else {
                // Diğer durumlarda depo zorunlu, firma temizle
                if (empty($data['warehouse_id'])) {
                    toastr()->error('Bu durumda depo seçimi zorunludur.');
                    return back()->withInput();
                }
                $data['company_id'] = null; // Firma bilgisini temizle
            }

            // Laboratuvar işlemleri için durumlar
            if (in_array($data['status'], [
                Barcode::STATUS_CONTROL_REPEAT,
                Barcode::STATUS_PRE_APPROVED,
                Barcode::STATUS_REJECTED
            ])) {
                $data['lab_at'] = now();
                $data['lab_by'] = auth()->user()->id;

                // Teslim edildi -> Ön Onaylı geçişinde iade olarak işaretle
                if ($data['status'] == Barcode::STATUS_PRE_APPROVED && $barcode->status == Barcode::STATUS_DELIVERED) {
                    $data['is_returned'] = true;
                    $data['returned_at'] = now();
                    $data['returned_by'] = auth()->user()->id;
                }
            }
            
            // Sevk onayı durumu
            if ($data['status'] == Barcode::STATUS_SHIPMENT_APPROVED) {
                $data['warehouse_transferred_at'] = now();
                $data['warehouse_transferred_by'] = auth()->user()->id;
            }
            
            // Müşteri transfer durumu
            if ($data['status'] == Barcode::STATUS_CUSTOMER_TRANSFER) {
                $data['company_transferred_at'] = now();
                
                // Reddedildi durumundan geçiş yapıldıysa istisnai onaylı olarak işaretle
                if ($barcode->status == Barcode::STATUS_REJECTED) {
                    $data['is_exceptionally_approved'] = true;
                }
            }
            
            // Teslim edildi durumu
            if ($data['status'] == Barcode::STATUS_DELIVERED) {
                $data['delivered_at'] = now();
                $data['delivered_by'] = auth()->user()->id;
                
                // Reddedildi durumundan geçiş yapıldıysa istisnai onaylı olarak işaretle
                if ($barcode->status == Barcode::STATUS_REJECTED) {
                    $data['is_exceptionally_approved'] = true;
                }
            }
        }

        // Transfer status artık kullanılmıyor - sadece ana durum kullanılıyor

        $barcode->update($data);

        // Red sebeplerini işle
        if (isset($data['status']) && $data['status'] == Barcode::STATUS_REJECTED) {
            if ($request->has('rejection_reasons') && is_array($request->rejection_reasons)) {
                $barcode->rejectionReasons()->sync($request->rejection_reasons);
            } else {
                $barcode->rejectionReasons()->detach();
            }
        } else {
            // Red durumu değilse: İstisnai onaylı ya da iade edilmiş ürünlerin red sebeplerini KORU
            // Not: $barcode modeli update edildiği için mevcut bayrakları doğrudan kontrol ediyoruz
            if (!$barcode->is_exceptionally_approved && !$barcode->is_returned) {
                $barcode->rejectionReasons()->detach();
            }
        }

        BarcodeHistory::create([
            'barcode_id' => $barcode->id,
            'status' => $barcode->status,
            'user_id' => auth()->user()->id,
            'description' => Barcode::EVENT_UPDATED,
            'changes' => $barcode->getChanges(),
        ]);

        if (!$barcode) {
            toastr()->error('Barkod düzenlenemedi.');
        }

        toastr()->success('Barkod başarıyla düzenlendi.');

        return redirect()->route('barcode.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $barcode = Barcode::findOrFail($id);

        DB::beginTransaction();
        
        try {
            // Kiln load number'ı güvenli şekilde azalt
            $kiln = $barcode->kiln;
            if ($kiln && $kiln->load_number > 0) {
                $kiln->decrement('load_number', 1);
            }
            
            $barcode->delete();
            
            DB::commit();
            
            return response()->json(['message' => 'Barkod silindi!']);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Barkod silinirken hata oluştu: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function qrRead()
    {
        return view('admin.barcode.qr-read');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function history($id)
    {
        $histories = BarcodeHistory::with([
            'user'
        ])
            ->where('barcode_id', $id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.barcode.history', compact('histories'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function historyIndex(Request $request)
    {
        $stocks = Stock::all();
        $kilns = Kiln::all();
        $quantities = Quantity::all();
        $companies = Company::all();
        $wareHouses = Warehouse::all();
        $users = User::all();
        $descriptions = Barcode::EVENTS;

        // Filtreleme için tüm barkod geçmişlerini al
        $barcodeHistories = BarcodeHistory::with(['barcode'])->get();
        
        // Eğer hiç veri yoksa, boş bir collection döndür
        if ($barcodeHistories->isEmpty()) {
            if ($request->ajax()) {
                return DataTables::of(collect([]))
                    ->addIndexColumn()
                    ->addColumn('stock', function ($row) { return '-'; })
                    ->addColumn('party_number', function ($row) { return '-'; })
                    ->addColumn('load_number', function ($row) { return '-'; })
                    ->addColumn('description', function ($row) { return '-'; })
                    ->addColumn('user', function ($row) { return '-'; })
                    ->addColumn('status', function ($row) { return '-'; })
                    ->addColumn('changes', function ($row) { return '-'; })
                    ->addColumn('created_at', function ($row) { return '-'; })
                    ->rawColumns(['status', 'changes'])
                    ->make(true);
            }
            
            return view('admin.barcode.history-index', compact(
                'stocks',
                'kilns',
                'quantities',
                'companies',
                'wareHouses',
                'users',
                'descriptions'
            ))->with([
                'histories' => collect([]),
                'barcodeHistories' => collect([])
            ]);
        }
        
        $histories = BarcodeHistory::with([
            'user',
            'barcode',
            'barcode.stock',
            'barcode.quantity',
            'barcode.company',
            'barcode.warehouse'
        ])
            ->orderByDesc('id')
            ->when($request->filled('stock_name'), function (Builder $query) use ($request) {
                $query->whereHas('barcode.stock', function (Builder $query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('stock_name') . '%');
                });
            })

            ->when($request->filled('party_number'), function (Builder $query) use ($request) {
                $query->whereHas('barcode', function (Builder $query) use ($request) {
                    $query->where('party_number', 'like', '%' . $request->input('party_number') . '%');
                });
            })
            ->when($request->filled('status'), function (Builder $query) use ($request) {
                $status = $request->input('status');
                $statusId = null;
                
                // Türkçe durum adını ID'ye çevir
                switch($status) {
                    case 'Beklemede':
                        $statusId = Barcode::STATUS_WAITING;
                        break;
                    case 'Kontrol Tekrarı':
                        $statusId = Barcode::STATUS_CONTROL_REPEAT;
                        break;
                    case 'Ön Onaylı':
                        $statusId = Barcode::STATUS_PRE_APPROVED;
                        break;
                    case 'Sevk Onaylı':
                        $statusId = Barcode::STATUS_SHIPMENT_APPROVED;
                        break;
                    case 'Reddedildi':
                        $statusId = Barcode::STATUS_REJECTED;
                        break;
                    case 'Müşteri Transfer':
                        $statusId = Barcode::STATUS_CUSTOMER_TRANSFER;
                        break;
                    case 'Teslim Edildi':
                        $statusId = Barcode::STATUS_DELIVERED;
                        break;
                    case 'Birleştirildi':
                        $statusId = Barcode::STATUS_MERGED;
                        break;
                }
                
                if ($statusId !== null) {
                    $query->whereHas('barcode', function (Builder $query) use ($statusId) {
                        $query->where('status', $statusId);
                    });
                }
            })
            ->when($request->filled('quantity'), function (Builder $query) use ($request) {
                $quantity = str_replace(' KG', '', $request->input('quantity'));
                $query->whereHas('barcode.quantity', function (Builder $query) use ($quantity) {
                    $query->where('quantity', $quantity);
                });
            })
            ->when($request->filled('company_name'), function (Builder $query) use ($request) {
                $query->whereHas('barcode.company', function (Builder $query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('company_name') . '%');
                });
            })
            ->when($request->filled('warehouse_name'), function (Builder $query) use ($request) {
                $query->whereHas('barcode.warehouse', function (Builder $query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('warehouse_name') . '%');
                });
            })
            ->when($request->filled('created_by'), function (Builder $query) use ($request) {
                $query->whereHas('user', function (Builder $query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('created_by') . '%');
                });
            })
            ->when($request->filled('lab_by'), function (Builder $query) use ($request) {
                $query->whereHas('barcode.labBy', function (Builder $query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('lab_by') . '%');
                });
            })
            ->when($request->filled('start_date'), function (Builder $query) use ($request) {
                try {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('start_date'))->startOfDay();
                    $query->where('created_at', '>=', $date);
                } catch (\Exception $e) {
                    \Log::error('Invalid start_date format:', ['date' => $request->input('start_date'), 'error' => $e->getMessage()]);
                }
            })
            ->when($request->filled('end_date'), function (Builder $query) use ($request) {
                try {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('end_date'))->endOfDay();
                    $query->where('created_at', '<=', $date);
                } catch (\Exception $e) {
                    \Log::error('Invalid end_date format:', ['date' => $request->input('end_date'), 'error' => $e->getMessage()]);
                }
            })
            ->when($request->filled('lab_start_date'), function (Builder $query) use ($request) {
                try {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('lab_start_date'))->startOfDay();
                    $query->whereHas('barcode', function (Builder $query) use ($date) {
                        $query->where('lab_at', '>=', $date);
                    });
                } catch (\Exception $e) {
                    \Log::error('Invalid lab_start_date format:', ['date' => $request->input('lab_start_date'), 'error' => $e->getMessage()]);
                }
            })
            ->when($request->filled('lab_end_date'), function (Builder $query) use ($request) {
                try {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('lab_end_date'))->endOfDay();
                    $query->whereHas('barcode', function (Builder $query) use ($date) {
                        $query->where('lab_at', '<=', $date);
                    });
                } catch (\Exception $e) {
                    \Log::error('Invalid lab_end_date format:', ['date' => $request->input('lab_end_date'), 'error' => $e->getMessage()]);
                }
            });

        if ($request->ajax()) {
            try {
                return DataTables::of($histories)
                    ->addIndexColumn()
                    ->addColumn('stock', function ($row) {
                        try {
                            if (!$row->barcode || !$row->barcode->stock) return '-';
                            return htmlspecialchars($row->barcode->stock->name);
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->addColumn('party_number', function ($row) {
                        try {
                            if (!$row->barcode || !$row->barcode->party_number) return '-';
                            return htmlspecialchars($row->barcode->party_number);
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->addColumn('load_number', function ($row) {
                        try {
                            if (!$row->barcode || !$row->barcode->load_number) return '-';
                            return htmlspecialchars($row->barcode->load_number);
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->addColumn('description', function ($row) {
                        try {
                            if (!$row->description) return '-';
                            return htmlspecialchars($row->description);
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->addColumn('user', function ($row) {
                        try {
                            if (!$row->user || !$row->user->name) return '-';
                            return htmlspecialchars($row->user->name);
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->addColumn('status', function ($row) {
                        try {
                            if (!$row->barcode) return '-';
                            
                            $status = $row->barcode->status;
                            if (!isset($status)) return '-';
                            
                            $statusName = Barcode::STATUSES[$status] ?? 'Bilinmiyor';
                            
                            $statusClass = '';
                            switch($status) {
                                case Barcode::STATUS_WAITING:
                                    $statusClass = 'status-waiting';
                                    break;
                                case Barcode::STATUS_CONTROL_REPEAT:
                                    $statusClass = 'status-control-repeat';
                                    break;
                                case Barcode::STATUS_PRE_APPROVED:
                                    $statusClass = 'status-pre-approved';
                                    break;
                                case Barcode::STATUS_SHIPMENT_APPROVED:
                                    $statusClass = 'status-shipment-approved';
                                    break;
                                case Barcode::STATUS_REJECTED:
                                    $statusClass = 'status-rejected';
                                    break;
                                case Barcode::STATUS_CUSTOMER_TRANSFER:
                                    $statusClass = 'status-customer-transfer';
                                    break;
                                case Barcode::STATUS_DELIVERED:
                                    $statusClass = 'status-delivered';
                                    break;
                                case Barcode::STATUS_MERGED:
                                    $statusClass = 'status-merged';
                                    break;
                                default:
                                    $statusClass = 'status-unknown';
                                    break;
                            }
                            
                            return '<span class="status-badge ' . $statusClass . '">' . htmlspecialchars($statusName) . '</span>';
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->addColumn('changes', function ($row) {
                        try {
                            // changes alanı null, boş array veya geçersiz olabilir
                            if (!$row->changes || empty($row->changes) || !is_array($row->changes)) {
                                return '-';
                            }
                            
                            $html = '<div class="changes-container">';
                            foreach ($row->changes as $field => $change) {
                                if (empty($field)) continue; // Boş field'ları atla
                                
                                $html .= '<div class="change-item">';
                                $html .= '<span class="change-field">' . htmlspecialchars($field) . '</span>';
                                
                                if (is_array($change) && isset($change['from']) && isset($change['to'])) {
                                    $html .= '<span class="change-value">' . htmlspecialchars($change['from'] ?: 'Boş') . '</span>';
                                    $html .= '<span class="change-arrow">→</span>';
                                    $html .= '<span class="change-value">' . htmlspecialchars($change['to'] ?: 'Boş') . '</span>';
                                } else {
                                    $changeValue = is_array($change) ? json_encode($change) : (string)$change;
                                    $html .= '<span class="change-value">' . htmlspecialchars($changeValue) . '</span>';
                                }
                                $html .= '</div>';
                            }
                            $html .= '</div>';
                            
                            return $html;
                        } catch (\Exception $e) {
                            \Log::warning('Error processing changes column:', [
                                'row_id' => $row->id,
                                'error' => $e->getMessage()
                            ]);
                            return '-';
                        }
                    })
                    ->addColumn('created_at', function ($row) {
                        try {
                            if (!$row->created_at) return '-';
                            return $row->created_at->format('d.m.Y H:i:s');
                        } catch (\Exception $e) {
                            return '-';
                        }
                    })
                    ->rawColumns(['status', 'changes'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error('DataTables Error in historyIndex:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => 'Veri yüklenirken hata oluştu: ' . $e->getMessage()
                ], 500);
            }
        }

        return view('admin.barcode.history-index', compact(
            'histories',
            'barcodeHistories',
            'stocks',
            'kilns',
            'quantities',
            'companies',
            'wareHouses',
            'users',
            'descriptions'
        ));
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function print(Request $request)
    {
        $ids = $request->input('barcode_ids');

        if (!is_array($ids)) {
            toastr()->error('Barkod numaraları dizi olmalıdır.');

            $ids = [];
        }

        $barcodes = Barcode::with([
            'createdBy',
            'stock',
            'kiln',
            'quantity',
            'company',
            'warehouse',
            'labBy',
            'warehouseTransferredBy',
            'deliveredBy',
        ])
            ->whereIn('id', $ids)
            ->get();

        return view('admin.barcode.print', compact('barcodes'));
    }

    public function merge()
    {
        // Müşteri transfer, teslim edildi ve birleştirildi hariç tüm barkodlar (birleştirilebilir)
        $availableBarcodes = Barcode::with([
            'stock',
            'quantity'
        ])
            ->whereNotIn('status', [
                Barcode::STATUS_CUSTOMER_TRANSFER,
                Barcode::STATUS_DELIVERED,
                Barcode::STATUS_MERGED
            ])
            ->orderByDesc('created_at')
            ->get();

        $warehouses = Warehouse::get();
        $kilns = Kiln::get();

        return view('admin.barcode.merge', compact([
            'availableBarcodes',
            'warehouses',
            'kilns',
        ]));
    }

    public function mergeStore(BarcodeMergeRequest $request)
    {
        $data = $request->validated();

        $kiln = Kiln::where('id', Kiln::KILN_DF)->firstOrFail();

        // Grup 1 ve Grup 2'yi ayır
        $group1BarcodeIds = $data['barcode_ids'] ?? [];
        $group2BarcodeIds = $data['barcode_ids_2'] ?? [];
        $allBarcodeIds = array_merge($group1BarcodeIds, $group2BarcodeIds);
        
        // Aynı barkod seçilip seçilmediğini kontrol et
        if (count($allBarcodeIds) !== count(array_unique($allBarcodeIds))) {
            toastr()->error('Aynı barkod birden fazla seçilemez!');
            return back()->withInput();
        }

        // Seçilen barkodların durumlarını kontrol et
        $selectedBarcodes = Barcode::whereIn('id', $allBarcodeIds)->get();
        $mergedBarcodes = $selectedBarcodes->where('status', Barcode::STATUS_MERGED);
        
        if ($mergedBarcodes->count() > 0) {
            $mergedBarcodeIds = $mergedBarcodes->pluck('id')->implode(', ');
            toastr()->error('Seçilen barkodlardan bazıları zaten birleştirilmiş durumda! Birleştirilmiş barkod ID\'leri: ' . $mergedBarcodeIds);
            return back()->withInput();
        }

        // Transaction başlat
        DB::beginTransaction();
        
        try {
            // Tüm seçilen barkodları "Birleştirildi" durumuna geçir
            Barcode::whereIn('id', $allBarcodeIds)
                ->update([
                    'is_merged' => true,
                    'status' => Barcode::STATUS_MERGED
                ]);

            // Her barkod için geçmiş kaydı oluştur
            foreach ($allBarcodeIds as $barcodeId) {
                $barcode = Barcode::find($barcodeId);
                BarcodeHistory::create([
                    'barcode_id' => $barcode->id,
                    'status' => $barcode->status,
                    'user_id' => auth()->user()->id,
                    'description' => Barcode::EVENT_MERGED,
                    'changes' => $barcode->getChanges(),
                ]);
            }

            // Güvenli miktar hesaplama
            $totalQuantity = DB::table('barcodes')
                ->join('quantities', 'quantities.id', '=', 'barcodes.quantity_id')
                ->whereIn('barcodes.id', $allBarcodeIds)
                ->whereNull('barcodes.deleted_at')
                ->sum('quantities.quantity');

            // İlk barkodun bilgilerini al (stok, fırın vb. için referans)
            $firstBarcode = Barcode::find($allBarcodeIds[0]);

            // Birleştirilen tüm barkodların bilgilerini topla
            $mergedBarcodes = Barcode::whereIn('id', $allBarcodeIds)
                ->select('load_number', 'stock_id')
                ->orderBy('load_number')
                ->get();

            $mergedLoadNumbers = $mergedBarcodes->pluck('load_number')->implode(',');
            $mergedBarcodeIds = implode(',', $allBarcodeIds);
            
            // Grup 1 ve Grup 2'nin şarj numaralarını ayrı ayrı al
            $group1Barcodes = Barcode::whereIn('id', $group1BarcodeIds)->pluck('load_number')->sort()->implode(',');
            $group2Barcodes = Barcode::whereIn('id', $group2BarcodeIds)->pluck('load_number')->sort()->implode(',');
            $group1BarcodeIdsStr = implode(',', $group1BarcodeIds);
            $group2BarcodeIdsStr = implode(',', $group2BarcodeIds);
            
            $mergedNote = $data['note'] ? $data['note'] . ' | ' : '';
            $mergedNote .= 'Birleştirilen Şarjlar: ' . $mergedLoadNumbers . ' | Birleştirilen Barkod ID\'leri: ' . $mergedBarcodeIds;
            $mergedNote .= ' | Grup 1 (Kaynak): ' . $group1BarcodeIdsStr . ' | Grup 2 (Hedef): ' . $group2BarcodeIdsStr;

            // 1000 KG'lık paketler oluştur
            $packageCount = (int) ceil($totalQuantity / 1000);

            for($i = 0; $i < $packageCount; $i++) {
                $packageQuantity = min(1000, $totalQuantity - ($i * 1000));
                
                $quantity = Quantity::firstOrCreate([
                    'quantity' => $packageQuantity
                ]);

                $bar = Barcode::create([
                    'stock_id' => $firstBarcode->stock_id,
                    'kiln_id' => $kiln->id,
                    'warehouse_id' => $data['warehouse_id'],
                    'party_number' => $data['party_number'],
                    'load_number' => ++$kiln->load_number,
                    'rejected_load_number' => $mergedLoadNumbers,
                    'quantity_id' => $quantity->id,
                    'status' => Barcode::STATUS_WAITING,
                    'created_by' => auth()->user()->id,
                    'note' => $mergedNote,
                    'old_barcode_id' => $firstBarcode->id
                ]);

                BarcodeHistory::create([
                    'barcode_id' => $bar->id,
                    'status' => $bar->status,
                    'user_id' => auth()->user()->id,
                    'description' => Barcode::EVENT_MERGED
                ]);
            }

            $kiln->update([
                'load_number' => $kiln->load_number
            ]);

            DB::commit();
            
            toastr()->success('Barkodlar başarıyla birleştirildi ve yeni test edilebilir ürünler oluşturuldu.');
            
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Barkod birleştirme işlemi başarısız: ' . $e->getMessage());
        }

        return redirect()->route('barcode.index');
    }

    public function printPageLayout()
    {
        return view('admin.barcode.print-page');
    }

    public function printPage(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->input('q');
            $data = DB::table('barcodes')
                ->select('barcodes.id', 'barcodes.stock_id', 'stocks.name as stock_name', 'createdBy.name as created_by_name', 'barcodes.load_number', 'barcodes.rejected_load_number')
                ->leftJoin('stocks', 'stocks.id', '=', 'barcodes.stock_id')
                ->leftJoin('users as createdBy', 'createdBy.id', '=', 'barcodes.created_by')
                ->where('stocks.name', 'LIKE', $search)
                ->orWhere('barcodes.load_number', 'LIKE', $search)
                ->orderByDesc('barcodes.created_at')
                ->orderBy('stock_name')
                ->orderBy('barcodes.load_number')
                ->get();
        }

        return response()->json($data);
    }
}
