<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use App\Models\GranilyaCompany;
use App\Models\GranilyaProduction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dateStr = $request->get('date', date('Y-m-d'));
        $date = Carbon::parse($dateStr)->setTimezone('Europe/Istanbul');
        $period = $request->get('period', 'daily');
        $customStart = $request->get('start_date');
        $customEnd = $request->get('end_date');

        // Logic similar to PageController@report
        $periodInfo = $this->calculatePeriodInfo($date, $period, $customStart, $customEnd);
        $startDate = $periodInfo['start_date'];
        $endDate = $periodInfo['end_date'];

        $companies = GranilyaCompany::orderBy('name')->get();

        foreach ($companies as $company) {
            // Get filtered weight and pallet count
            $company->delivered_kg = $company->getTotalDeliveredWeight($startDate, $endDate);
            $company->barcodes_count = $company->getUniquePalletCount($startDate, $endDate);
            
            // Static indicators (overall or last 30 days)
            $company->total_purchase_overall = $company->getTotalDeliveredWeight();
            $company->last_30_days_purchase = $company->getLast30DaysWeight();
            
            $company->last_purchase_date = $company->deliveredProductions()
                ->where('status', GranilyaProduction::STATUS_DELIVERED)
                ->max('delivered_at');
            
            $company->average_order_size = $company->barcodes_count > 0 
                ? $company->delivered_kg / $company->barcodes_count 
                : 0;

            $company->is_active = $company->is_active;
        }

        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        return view('granilya.companies.index', compact('companies', 'periodInfo', 'period', 'dateStr', 'startDateStr', 'endDateStr'));
    }

    private function calculatePeriodInfo($date, $period, $startDate = null, $endDate = null)
    {
        $today = Carbon::today('Europe/Istanbul');
        
        if ($startDate && $endDate) {
            try {
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
            } catch (\Exception $e) {
            }
        }
        
        $startDate = $date->copy();
        $endDate = $date->copy();
        
        switch ($period) {
            case 'daily':
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $periodName = 'Günlük';
                break;
            case 'weekly':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = 'Haftalık';
                break;
            case 'monthly':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = 'Aylık';
                break;
            case 'quarterly':
                $startDate = $date->copy()->startOfQuarter();
                $endDate = $date->copy()->endOfQuarter();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = '3 Aylık';
                break;
            case 'yearly':
                $startDate = $date->copy()->startOfYear();
                $endDate = $date->copy()->endOfYear();
                if ($endDate->isAfter($today)) $endDate = $today->copy()->endOfDay();
                $periodName = 'Yıllık';
                break;
            default:
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
                $periodName = 'Günlük';
        }
        
        return [
            'name' => $periodName,
            'range' => $startDate->format('d.m.Y') . ($period !== 'daily' ? ' - ' . $endDate->format('d.m.Y') : ''),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_date_formatted' => $startDate->format('d.m.Y'),
            'end_date_formatted' => $endDate->format('d.m.Y'),
            'is_custom' => false
        ];
    }

    /**
     * Export companies data to Excel/CSV.
     */
    public function export(Request $request)
    {
        $dateStr = $request->get('date', date('Y-m-d'));
        $date = Carbon::parse($dateStr);
        $period = $request->get('period', 'daily');
        $customStart = $request->get('start_date');
        $customEnd = $request->get('end_date');

        $periodInfo = $this->calculatePeriodInfo($date, $period, $customStart, $customEnd);
        $companies = GranilyaCompany::orderBy('name')->get();

        // In the context of "Global Audit", we want the general company report to also be XLSX.
        // However, the previous tool call messed up the logic between the general list export 
        // and the single company detail export (which was what I drafted in the plan).
        // Let's implement a general CompanyReportExport if needed, or stick to the plan.
        
        // Actually, the user asked to fix existing export functions. 
        // Let's finalize this method as a valid XLSX download.
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\Granilya\CompanyExport(new GranilyaCompany(['name' => 'Tüm Firmalar']), $periodInfo, GranilyaProduction::where('status', GranilyaProduction::STATUS_DELIVERED)->whereBetween('delivered_at', [$periodInfo['start_date'], $periodInfo['end_date']])->get()),
            'Granilya_Firma_Genel_Raporu_' . date('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('granilya.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        GranilyaCompany::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('granilya.firma.index')->with('success', 'Firma başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $firma = GranilyaCompany::findOrFail($id);
        
        // Detailed stats for the company
        $deliveredProductions = $firma->deliveredProductions()
            ->with(['stock', 'user', 'size'])
            ->orderBy('delivered_at', 'desc')
            ->get();
            
        $uniquePalletCount = $firma->getUniquePalletCount();

        return view('granilya.companies.show', compact('firma', 'deliveredProductions', 'uniquePalletCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GranilyaCompany $firma)
    {
        return view('granilya.companies.edit', compact('firma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GranilyaCompany $firma)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $firma->update([
            'name' => $request->name,
        ]);

        return redirect()->route('granilya.firma.index')->with('success', 'Firma başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GranilyaCompany $firma)
    {
        $firma->delete();
        return redirect()->route('granilya.firma.index')->with('success', 'Firma başarıyla silindi.');
    }
}
