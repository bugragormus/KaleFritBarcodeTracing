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

        if ($request->filled('company_id')) {
            $query->whereIn('company_id', $request->company_id);
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
        $companies = \App\Models\GranilyaCompany::all();
        $statuses = GranilyaProduction::getStatusList();
        $users = \App\Models\User::all();

        return view('granilya.stock.index', compact('productions', 'stocks', 'sizes', 'crushers', 'companies', 'statuses', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'load_number' => 'required|string',
            'size_id' => 'required|exists:granilya_sizes,id',
            'crusher_id' => 'required|exists:granilya_crushers,id',
            'company_id' => 'required|exists:granilya_companies,id',
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

        // Elek Altı (Sieve Residue) işaretlendiyse ek işlem
        $isSieveResidue = $request->has('is_sieve_residue') && $request->is_sieve_residue == 1;
        $sieveResidueQuantity = 0;

        // EĞER elek altı işaretlendiyse, kalan stok miktarını hesapla ve sieve_residue_quantity kolonuna koy
        if ($isSieveResidue) {
            // Frit'ten Granilya'ya bu şarjdan gelen toplam miktar (hesaplamıştık)
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
            
            // Eğer kalan stoktan daha fazla kullandıysak hata dönebiliriz.
            if ($usedQuantity > $remainingStock) {
                return back()->with('error', 'Stokta yeterli miktar bulunmuyor (Kalan: ' . $remainingStock . ' KG).')->withInput();
            }

            // Geriye ne kaldıysa elek altına atıyoruz.
            $sieveResidueQuantity = $remainingStock - $usedQuantity;
        }

        // Üretimi (Paleti) kaydet
        GranilyaProduction::create([
            'stock_id' => $request->stock_id,
            'load_number' => $request->load_number,
            'size_id' => $request->size_id,
            'crusher_id' => $request->crusher_id,
            'quantity_id' => $request->quantity_id,
            'custom_quantity' => $request->custom_quantity,
            'used_quantity' => $usedQuantity,
            'company_id' => $request->company_id,
            'pallet_number' => $request->pallet_number,
            'status' => GranilyaProduction::STATUS_WAITING, // Varsayılan: Beklemede
            'is_sieve_residue' => $isSieveResidue,
            'sieve_residue_quantity' => $sieveResidueQuantity,
            'user_id' => auth()->id(),
            'general_note' => $request->general_note,
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
            'stock_id' => 'required|exists:stocks,id',
            'load_number' => 'required|string',
            'size_id' => 'required|exists:granilya_sizes,id',
            'crusher_id' => 'required|exists:granilya_crushers,id',
            'company_id' => 'required|exists:granilya_companies,id',
            'pallet_number' => 'required|string',
        ]);

        $pallet = GranilyaProduction::findOrFail($id);
        
        // Track changes
        $oldData = $pallet->getRawOriginal();
        $pallet->update($request->all());
        $newData = $pallet->getChanges();
        
        if (!empty($newData)) {
            $formattedChanges = [];
            foreach ($newData as $field => $newValue) {
                if ($field == 'updated_at') continue;
                $formattedChanges[$field] = [
                    'from' => $oldData[$field] ?? null,
                    'to' => $newValue
                ];
            }
            
            \App\Models\GranilyaProductionHistory::create([
                'production_id' => $pallet->id,
                'status' => $pallet->status,
                'user_id' => auth()->id(),
                'description' => 'Palet bilgileri güncellendi.',
                'changes' => $formattedChanges
            ]);
        }

        return redirect()->route('granilya.production.show', $pallet->pallet_number)->with('success', 'Palet başarıyla güncellendi.');
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
