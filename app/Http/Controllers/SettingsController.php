<?php

namespace App\Http\Controllers;

use App\Models\Kiln;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->hasPermission(Permission::USER_MANAGE)) {
                abort(403, 'Bu sayfaya erişim yetkiniz bulunmamaktadır.');
            }
            return $next($request);
        });
    }

    public function show()
    {
        $kilns = Kiln::all();
        return view('admin.settings.edit', compact('kilns'));
    }

    public function resetKilnLoadNumber()
    {
        // Yetki kontrolü constructor'da yapılıyor
        try {
            // Tüm fırınların şarj numaralarını sıfırla
            DB::update('UPDATE kilns set load_number = 0');

            return response()->json(['message' => 'Tüm fırınların şarj numaraları sıfırlandı. Artık yeni barkod oluştururken şarj numarası 1\'den başlayabilir.']);
        }catch (\Exception $exception) {
            return response()->json(['message' => 'Hata meydana geldi!'], 404);
        }
    }

    public function resetSingleKilnLoadNumber(Request $request)
    {
        // Yetki kontrolü constructor'da yapılıyor
        try {
            $kilnId = $request->input('kiln_id');
            $kiln = Kiln::findOrFail($kilnId);
            
            // Fırının şarj numarasını sıfırla
            $kiln->update(['load_number' => 0]);

            return response()->json([
                'message' => $kiln->name . ' fırınının şarj numarası sıfırlandı. Artık yeni barkod oluştururken şarj numarası 1\'den başlayabilir.',
                'kiln_name' => $kiln->name
            ]);
        }catch (\Exception $exception) {
            return response()->json(['message' => 'Hata meydana geldi!'], 404);
        }
    }
    
    /**
     * Fırınların mevcut şarj numarası durumunu günceller
     */
    public function updateKilnLoadNumbers()
    {
        // Yetki kontrolü constructor'da yapılıyor
        try {
            // Her fırın için en yüksek şarj numarasını bul ve güncelle
            $kilns = Kiln::all();
            
            foreach ($kilns as $kiln) {
                $maxLoadNumber = DB::table('barcodes')
                    ->where('kiln_id', $kiln->id)
                    ->max('load_number');
                
                $kiln->update(['load_number' => $maxLoadNumber ?: 0]);
            }
            
            return response()->json(['message' => 'Fırın şarj numaraları güncellendi']);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Hata meydana geldi!'], 404);
        }
    }
}
