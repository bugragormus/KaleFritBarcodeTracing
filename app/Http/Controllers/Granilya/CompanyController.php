<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use App\Models\GranilyaCompany;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = GranilyaCompany::orderBy('name')->get();

        // Since Granilya barcode tracking is not yet implemented, 
        // we feed the detailed view with 0/placeholder data for now 
        // to match the Frit Company UI design exactly as requested.
        foreach ($companies as $company) {
            $company->delivery_rate = 0;
            $company->total_purchase = 0;
            $company->last_30_days_purchase = 0;
            $company->barcodes_count = 0;
            $company->last_purchase_date = null;
            $company->average_order_size = 0;
            
            $company->customer_transfer_kg = 0;
            $company->delivered_kg = 0;
            
            $company->is_active = false;
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
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        GranilyaCompany::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('granilya.firma.index')->with('success', 'Firma başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $firma->update([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => $request->boolean('is_active', false)
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
