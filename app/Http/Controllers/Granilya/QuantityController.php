<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use App\Models\GranilyaQuantity;
use Illuminate\Http\Request;

class QuantityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quantities = GranilyaQuantity::orderBy('quantity')->get();
        return view('granilya.quantities.index', compact('quantities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('granilya.quantities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);

        GranilyaQuantity::create([
            'quantity' => $request->quantity,
            'is_active' => true,
        ]);

        return redirect()->route('granilya.miktar.index')->with('success', 'Miktar başarıyla eklendi.');
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
    public function edit(GranilyaQuantity $miktar)
    {
        return view('granilya.quantities.edit', compact('miktar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GranilyaQuantity $miktar)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);
        
        $miktar->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('granilya.miktar.index')->with('success', 'Miktar başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GranilyaQuantity $miktar)
    {
        $miktar->delete();
        return redirect()->route('granilya.miktar.index')->with('success', 'Miktar başarıyla silindi.');
    }
}
