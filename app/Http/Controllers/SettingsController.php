<?php

namespace App\Http\Controllers;

use App\Models\Kiln;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function show()
    {
        $kilns = Kiln::all();
        return view('admin.settings.edit', compact('kilns'));
    }

    public function resetKilnLoadNumber()
    {
        try {
            DB::update('UPDATE kilns set load_number = 0');

            return response()->json(['message' => 'İşlem tamamlanmıştır']);
        }catch (\Exception $exception) {
            return response()->json(['message' => 'Hata meydana geldi!'], 404);
        }
    }

    public function resetSingleKilnLoadNumber(Request $request)
    {
        try {
            $kilnId = $request->input('kiln_id');
            $kiln = Kiln::findOrFail($kilnId);
            
            $kiln->update(['load_number' => 0]);

            return response()->json([
                'message' => $kiln->name . ' fırınının şarj numarası sıfırlandı',
                'kiln_name' => $kiln->name
            ]);
        }catch (\Exception $exception) {
            return response()->json(['message' => 'Hata meydana geldi!'], 404);
        }
    }
}
