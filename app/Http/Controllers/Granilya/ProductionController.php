<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GranilyaProduction;
use App\Models\GranilyaQuantity;
use App\Models\Stock;

class ProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Temel sorguyu oluştur
        $query = GranilyaProduction::with(['stock', 'size', 'crusher', 'quantity', 'company', 'user'])
            ->orderBy('id', 'desc');

        // Filtreleme mantığı
        if ($request->filled('stock_id')) {
            $query->whereIn('stock_id', $request->stock_id);
        }
        
        if ($request->filled('size_id')) {
            $query->whereIn('size_id', $request->size_id);
        }
        
        if ($request->filled('crusher_id')) {
            $query->whereIn('crusher_id', $request->crusher_id);
        }
        
        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        if ($request->filled('load_number')) {
            $query->where('load_number', 'like', '%' . $request->load_number . '%');
        }

        if ($request->filled('customer_type')) {
            $query->whereIn('customer_type', $request->customer_type);
        }
        
        if ($request->filled('created_date_start')) {
            $query->whereDate('created_at', '>=', \Carbon\Carbon::createFromFormat('d.m.Y', $request->created_date_start)->format('Y-m-d'));
        }
        
        if ($request->filled('created_date_end')) {
            $query->whereDate('created_at', '<=', \Carbon\Carbon::createFromFormat('d.m.Y', $request->created_date_end)->format('Y-m-d'));
        }

        if ($request->filled('user_id')) {
            $query->whereIn('user_id', $request->user_id);
        }

        // Frit Barkod Listesinde olduğu gibi tüm paletleri listele
        $productions = $query->get();

        // Filtre dropdownları için sistemdeki verileri çek
        $stocks = Stock::whereHas('granilyaProductions')->get();
        $sizes = \App\Models\GranilyaSize::all();
        $crushers = \App\Models\GranilyaCrusher::all();
        $statuses = GranilyaProduction::getStatusList();
        $users = \App\Models\User::all();

        return view('granilya.stock.index', compact('productions', 'stocks', 'sizes', 'crushers', 'statuses', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'load_number' => 'required|string',
            'size_id' => 'required|exists:granilya_sizes,id',
            'crusher_id' => 'required|exists:granilya_crushers,id',
            'customer_type' => 'required|in:İç Müşteri,Dış Müşteri',
            'pallet_number' => 'required|string',
        ]);

        // Elek altı durumunda olan şarjlar tekrar kullanılamaz
        $isAlreadySieveResidue = GranilyaProduction::where('stock_id', $request->stock_id)
            ->where('load_number', $request->load_number)
            ->where('is_sieve_residue', true)
            ->exists();

        if ($isAlreadySieveResidue) {
            return back()->with('error', 'Bu şarj numarası elek altı (stok sonu) olarak işaretlendiği için yeni üretim yapılamaz!')->withInput();
        }

        // Miktar (Kullanılan Miktar) tespiti
        $usedQuantity = 0;
        if ($request->filled('custom_quantity')) {
            // Tane boyutu TOZ olup elle girilen miktar
            $usedQuantity = (float) $request->custom_quantity;
        } elseif ($request->filled('quantity_id')) {
            // Dropdown'dan seçilen miktar
            $quantityModel = GranilyaQuantity::find($request->quantity_id);
            if ($quantityModel) {
                $usedQuantity = (float) $quantityModel->quantity;
            }
        }

        if ($usedQuantity <= 0) {
            return back()->with('error', 'Geçerli bir üretim miktarı girmediniz!')->withInput();
        }

        // --- STOK KONTROLÜ (TÜM ÜRETİMLER İÇİN ZORUNLU) ---
        // Frit'ten Granilya'ya bu şarjdan gelen toplam miktar 
        $totalImported = \App\Models\Barcode::where('stock_id', $request->stock_id)
            ->where('load_number', $request->load_number)
            ->where('status', \App\Models\Barcode::STATUS_TRANSFERRED_TO_GRANILYA)
            ->leftJoin('quantities', 'barcodes.quantity_id', '=', 'quantities.id')
            ->sum('quantities.quantity');

        // Bugüne kadar bu şarjdan KULLANILMIŞ toplam miktar 
        // ve ELEK ALTINA gönderilmiş toplam miktar (biz eklemeden önce)
        $previouslyUsed = GranilyaProduction::where('stock_id', $request->stock_id)
            ->where('load_number', $request->load_number)
            ->sum('used_quantity');

        $previouslySieved = GranilyaProduction::where('stock_id', $request->stock_id)
            ->where('load_number', $request->load_number)
            ->sum('sieve_residue_quantity');

        // Geriye kalan "Stoktaki Miktar" üzerinden güncel kullanımı düş
        $remainingStock = $totalImported - $previouslyUsed - $previouslySieved;
        
        // EĞER kalan stoktan daha fazla kullandıysak hata döndür (sistem eksiye düşmemeli)
        if ($usedQuantity > $remainingStock) {
            return back()->with('error', 'Stokta yeterli miktar bulunmuyor (Kalan Miktar: ' . $remainingStock . ' KG, Girmek İstediğiniz: ' . $usedQuantity . ' KG).')->withInput();
        }

        // Elek Altı (Sieve Residue) işaretlendiyse ek işlem
        $isSieveResidue = $request->has('is_sieve_residue') && $request->is_sieve_residue == 1;
        $sieveResidueQuantity = 0;

        // EĞER elek altı işaretlendiyse, geriye kalan tüm stoğu elek altına atıyoruz.
        if ($isSieveResidue) {
            $sieveResidueQuantity = $remainingStock - $usedQuantity;
        }

        // Üretimi (Paleti) kaydet
        $pallet = GranilyaProduction::create([
            'stock_id' => $request->stock_id,
            'load_number' => $request->load_number,
            'size_id' => $request->size_id,
            'crusher_id' => $request->crusher_id,
            'quantity_id' => $request->quantity_id,
            'custom_quantity' => $request->custom_quantity,
            'used_quantity' => $usedQuantity,
            'customer_type' => $request->customer_type,
            'pallet_number' => $request->pallet_number,
            'status' => GranilyaProduction::STATUS_WAITING, // Varsayılan: Beklemede
            'is_sieve_residue' => $isSieveResidue,
            'sieve_residue_quantity' => $sieveResidueQuantity,
            'user_id' => auth()->id(),
            'general_note' => $request->general_note,
        ]);

        if ($isSieveResidue && $sieveResidueQuantity > 0) {
            // Frit tarafında Düzeltme Faaliyeti için Elek Altı barkodu oluştur.
            try {
                // 1. Orijinal Stock'un Elek Altı versiyonunu bul veya oluştur
                $originalStock = \App\Models\Stock::find($request->stock_id);
                if ($originalStock) {
                    $elekAltiStockName = $originalStock->name . ' - Elek Altı';
                    $elekAltiStockCode = $originalStock->code . '-EA';
                    
                    $elekAltiStock = \App\Models\Stock::firstOrCreate(
                        ['name' => $elekAltiStockName],
                        ['code' => $elekAltiStockCode]
                    );

                    // 2. Frit Quantity'sini bul veya oluştur
                    $fritQuantity = \App\Models\Quantity::firstOrCreate(
                        ['quantity' => $sieveResidueQuantity]
                    );

                    // 3. Varsayılan Kiln, Warehouse ve Load Number belirle
                    $defaultKiln = \App\Models\Kiln::first();
                    $defaultWarehouse = \App\Models\Warehouse::first();
                    $maxLoadNumber = \App\Models\Barcode::max('load_number') ?? 0;
                    $nextLoadNumber = $maxLoadNumber + 1;

                    // 4. Frit Barkodunu Oluştur (Düzeltme Faaliyetinde kullanılmak üzere REJECTED durumda)
                    $fritBarcode = \App\Models\Barcode::create([
                        'stock_id' => $elekAltiStock->id,
                        'kiln_id' => $defaultKiln ? $defaultKiln->id : 1,
                        'quantity_id' => $fritQuantity->id,
                        'party_number' => 0, // Elek altı için özel parti mantığı eklenebilir
                        'load_number' => $nextLoadNumber,
                        'rejected_load_number' => null,
                        'status' => \App\Models\Barcode::STATUS_REJECTED,
                        'warehouse_id' => $defaultWarehouse ? $defaultWarehouse->id : 1,
                        'created_by' => auth()->id(),
                        'note' => 'Granilya üretiminden elek altı (Stok sonu) olarak ayrıldı. Palet: ' . $request->pallet_number,
                        'is_sieve_residue' => true,
                        'has_sieve_residue' => false,
                    ]);

                    // Fırın şarj numarasını güncelle
                    if ($defaultKiln) {
                         $defaultKiln->update(['load_number' => $nextLoadNumber]);
                    }

                    \App\Models\BarcodeHistory::create([
                        'barcode_id' => $fritBarcode->id,
                        'status' => $fritBarcode->status,
                        'user_id' => auth()->id(),
                        'description' => 'Granilya üretiminden kalan elek altı ürünü oluşturuldu.',
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Elek Altı (Sieve Residue) Frit barkodu oluşturulurken hata: ' . $e->getMessage());
            }
        }

        \App\Models\GranilyaProductionHistory::create([
            'production_id' => $pallet->id,
            'status' => $pallet->status,
            'user_id' => auth()->id(),
            'description' => 'Palet oluşturuldu: Beklemede',
        ]);

        return redirect()->route('granilya.stock.index')->with('success', 'Palet (Üretim) başarıyla oluşturuldu.');
    }

    public function show($pallet_number)
    {
        $pallet = GranilyaProduction::where('pallet_number', $pallet_number)->first();
        
        if (!$pallet) {
            return redirect()->route('granilya.barcode')->with('error', 'Girilen numaraya ait palet bulunamadı: ' . $pallet_number);
        }

        $stocks = Stock::all();
        $sizes = \App\Models\GranilyaSize::all();
        $crushers = \App\Models\GranilyaCrusher::all();
        $companies = \App\Models\GranilyaCompany::all();
        $quantities = GranilyaQuantity::all();
        
        return view('granilya.stock.show', compact('pallet', 'stocks', 'sizes', 'crushers', 'companies', 'quantities'));
    }

// Redundant as editing is now integrated into show view

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_type' => 'required|in:İç Müşteri,Dış Müşteri',
            // Other fields (stock_id, size_id, crusher_id, load_number, quantities) are omitted from validation 
            // because they are disabled in the view and shouldn't be mutable.
        ]);

        $pallet = GranilyaProduction::findOrFail($id);
        
        // Track changes
        $oldData = $pallet->getRawOriginal();
        $input = $request->all();

        // Customer type change handling
        $isCustomerTypeChanged = false;
        if ($request->filled('customer_type') && $request->customer_type !== $pallet->customer_type) {
            $isCustomerTypeChanged = true;
            $oldCustomerType = $pallet->customer_type;
            $newCustomerType = $request->customer_type;

            $input['status'] = GranilyaProduction::STATUS_WAITING;
            $input['sieve_test_result'] = null;
            $input['surface_test_result'] = null;
            $input['arge_test_result'] = null;
            $input['sieve_reject_reason'] = null;
            $input['surface_reject_reason'] = null;

            $note = "\n[SİSTEM BİLGİSİ - " . now()->format('d.m.Y H:i') . "]: Müşteri tipi '{$oldCustomerType}' -> '{$newCustomerType}' olarak değiştirildiği için testler sıfırlanmış ve palet Bekliyor durumuna alınmıştır.\n";
            $note .= "Önceki Test Sonuçları: Elek: " . ($pallet->sieve_test_result ?? 'Bilinmiyor') . 
                     ", Yüzey: " . ($pallet->surface_test_result ?? 'Bilinmiyor') . 
                     ", Arge: " . ($pallet->arge_test_result ?? 'Bilinmiyor');

            $input['system_note'] = ($request->system_note ?? $pallet->system_note) . $note;
        }

        // Status transition validation
        if (!$isCustomerTypeChanged && $request->filled('status') && (int)$request->status !== (int)$pallet->status) {
            $currentStatus = (int)$pallet->status;
            $newStatus = (int)$request->status;

            // Rule: Terminal States cannot be changed
            if ($currentStatus == GranilyaProduction::STATUS_SHIPMENT_APPROVED) {
                 return back()->with('error', 'Sevk onaylı paletlerin durumu değiştirilemez.')->withInput();
            }

            // --- VALID TRANSITIONS ---
            
            // 1. Waiting -> Pre-Approved (AUTO-APPROVE Sieve & Surface)
            if ($currentStatus == GranilyaProduction::STATUS_WAITING && $newStatus == GranilyaProduction::STATUS_PRE_APPROVED) {
                $input['sieve_test_result'] = 'Onay';
                $input['surface_test_result'] = 'Onay';
            }
            // 2. Waiting -> Rejected (Requires Reason from Request)
            elseif ($currentStatus == GranilyaProduction::STATUS_WAITING && $newStatus == GranilyaProduction::STATUS_REJECTED) {
                $this->handleManualRejection($request, $pallet, $input);
            }
            // 3. Pre-Approved -> Shipment Approved (AUTO-APPROVE Arge)
            elseif ($currentStatus == GranilyaProduction::STATUS_PRE_APPROVED && $newStatus == GranilyaProduction::STATUS_SHIPMENT_APPROVED) {
                $input['arge_test_result'] = 'Onay';
            }
            // 4. Pre-Approved -> Rejected (Requires Reason from Request)
            elseif ($currentStatus == GranilyaProduction::STATUS_PRE_APPROVED && $newStatus == GranilyaProduction::STATUS_REJECTED) {
                $this->handleManualRejection($request, $pallet, $input);
            }
            // 5. Rejected -> Shipment Approved (Exceptional Approval)
            elseif ($currentStatus == GranilyaProduction::STATUS_REJECTED && $newStatus == GranilyaProduction::STATUS_SHIPMENT_APPROVED) {
                if (!$pallet->isExceptionalAllowed()) {
                    return back()->with('error', 'Bu palet için istisnai onay verilemez. Sadece Arge testi sebebiyle reddedilen (elek ve yüzey onaylı) paletler istisnai onay alabilir.')->withInput();
                }
                $input['is_exceptionally_approved'] = true;
                $input['status'] = GranilyaProduction::STATUS_SHIPMENT_APPROVED;
            }
            // 6. Direct to Shipment Approved from Waiting (AUTO-APPROVE ALL)
            elseif ($currentStatus == GranilyaProduction::STATUS_WAITING && $newStatus == GranilyaProduction::STATUS_SHIPMENT_APPROVED) {
                $input['sieve_test_result'] = 'Onay';
                $input['surface_test_result'] = 'Onay';
                $input['arge_test_result'] = 'Onay';
            }
            // Illegal Transitions
            else {
                return back()->with('error', 'Bu durumdan hedeflenen duruma doğrudan geçiş yapılamaz.')->withInput();
            }

            // Ensure status is integer in input
            $input['status'] = $newStatus;
        }

        $pallet->update($input);
        $newData = $pallet->getChanges();
        
        if (!empty($newData)) {
            $formattedChanges = [];
            $statusList = GranilyaProduction::getStatusList();

            foreach ($newData as $field => $newValue) {
                if ($field == 'updated_at') continue;
                
                $fromValue = $oldData[$field] ?? null;
                $toValue = $newValue;

                // Format status labels for history
                if ($field == 'status') {
                    $fromValue = $statusList[$fromValue] ?? $fromValue;
                    $toValue = $statusList[$toValue] ?? $toValue;
                }

                $formattedChanges[$field] = [
                    'from' => $fromValue,
                    'to' => $toValue
                ];
            }
            
            // Ana durum güncelleme geçmişi
            $description = 'Palet bilgileri güncellendi.';
            if (isset($newData['status'])) {
                $statusList = GranilyaProduction::getStatusList();
                $newStatusLabel = $statusList[$newData['status']] ?? 'Bilinmiyor';
                $description = 'Durum Değişikliği: ' . $newStatusLabel;
                if (!empty($newData['is_exceptionally_approved'])) {
                    $description = 'İstisnai Onay ile Sevk Onaylı.';
                }
            }

            \App\Models\GranilyaProductionHistory::create([
                'production_id' => $pallet->id,
                'status' => $pallet->status,
                'user_id' => auth()->id(),
                'description' => $description,
                'changes' => $formattedChanges
            ]);

            // Test sonuçları için ayrı laboratuvar geçmişi kayıtları (Görünüm için gerekli)
            if (isset($newData['sieve_test_result'])) {
                \App\Models\GranilyaProductionHistory::create([
                    'production_id' => $pallet->id,
                    'status' => $pallet->status,
                    'user_id' => auth()->id(),
                    'description' => 'Elek Testi: ' . $newData['sieve_test_result'],
                ]);
            }
            if (isset($newData['surface_test_result'])) {
                \App\Models\GranilyaProductionHistory::create([
                    'production_id' => $pallet->id,
                    'status' => $pallet->status,
                    'user_id' => auth()->id(),
                    'description' => 'Yüzey Testi: ' . $newData['surface_test_result'],
                ]);
            }
            if (isset($newData['arge_test_result'])) {
                \App\Models\GranilyaProductionHistory::create([
                    'production_id' => $pallet->id,
                    'status' => $pallet->status,
                    'user_id' => auth()->id(),
                    'description' => 'Arge Testi: ' . $newData['arge_test_result'],
                ]);
            }
        }

        return redirect()->route('granilya.production.show', $pallet->pallet_number)->with('success', 'Palet başarıyla güncellendi.');
    }

    /**
     * Manuel durum değişikliğinde red detaylarını işler.
     */
    private function handleManualRejection(Request $request, $pallet, &$input)
    {
        $currentStatus = (int)$pallet->status;

        // Ön Onaylı'dan Reddedildi'ye geçişte Arge testi otomatik olarak Red sayılır, soru sorulmaz.
        if ($currentStatus == GranilyaProduction::STATUS_PRE_APPROVED) {
            $input['arge_test_result'] = 'Red';
            // Arge için Ön Onaylı'yken zaten elek/yüzey onaylıdır
            $input['sieve_test_result'] = 'Onay';
            $input['surface_test_result'] = 'Onay';
            return;
        }

        // Beklemede'den Reddedildi'ye geçişte sebep ve test zorunludur.
        $request->validate([
            'rejected_test' => 'required|in:Elek,Yüzey,Arge',
            'reject_reason' => 'required|string|max:500',
        ]);

        $test = $request->rejected_test;
        $reason = $request->reject_reason;

        // Geçerli nedenleri kontrol et
        if ($test == 'Elek') {
            if (!in_array($reason, GranilyaProduction::getSieveRejectionReasons())) {
                throw \Illuminate\Validation\ValidationException::withMessages(['reject_reason' => 'Geçersiz elek red sebebi.']);
            }
            $input['sieve_test_result'] = 'Red';
            $input['sieve_reject_reason'] = $reason;
        } elseif ($test == 'Yüzey') {
            if (!in_array($reason, GranilyaProduction::getSurfaceRejectionReasons())) {
                throw \Illuminate\Validation\ValidationException::withMessages(['reject_reason' => 'Geçersiz yüzey red sebebi.']);
            }
            $input['surface_test_result'] = 'Red';
            $input['surface_reject_reason'] = $reason;
        } elseif ($test == 'Arge') {
            $input['arge_test_result'] = 'Red';
            $input['general_note'] = ($input['general_note'] ?? $pallet->general_note) . "\n[Arge Red Nedeni]: " . $reason;
        }
    }

    public function history($id)
    {
        $pallet = GranilyaProduction::findOrFail($id);
        $histories = $pallet->histories()->with('user')->orderBy('created_at', 'desc')->get();
        
        return view('granilya.stock.history', compact('pallet', 'histories'));
    }

    public function destroy($id)
    {
        try {
            $pallet = GranilyaProduction::findOrFail($id);
            
            \App\Models\GranilyaProductionHistory::create([
                'production_id' => $pallet->id,
                'status' => $pallet->status,
                'user_id' => auth()->id(),
                'description' => 'Palet silindi (Soft Delete).',
            ]);
            
            $pallet->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Palet başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Palet silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
