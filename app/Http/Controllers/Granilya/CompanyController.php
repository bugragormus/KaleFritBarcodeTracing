<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use App\Models\GranilyaCompany;
use App\Models\GranilyaProduction;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = GranilyaCompany::orderBy('name')->get();

        foreach ($companies as $company) {
            $company->delivered_kg = $company->getTotalDeliveredWeight();
            
            $company->total_purchase = $company->delivered_kg;
            $company->last_30_days_purchase = $company->getLast30DaysWeight();
            $company->barcodes_count = $company->getUniquePalletCount();
            
            $company->last_purchase_date = $company->deliveredProductions()
                ->where('status', GranilyaProduction::STATUS_DELIVERED)
                ->max('delivered_at');
            
            $company->average_order_size = $company->barcodes_count > 0 
                ? $company->total_purchase / $company->barcodes_count 
                : 0;

            $company->is_active = $company->is_active; // Already from DB
        }

        return view('granilya.companies.index', compact('companies'));
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
