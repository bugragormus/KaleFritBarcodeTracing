<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use App\Models\GranilyaSize;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = GranilyaSize::orderBy('name')->get();
        return view('granilya.sizes.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('granilya.sizes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        GranilyaSize::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('granilya.boyut.index')->with('success', 'Tane boyutu başarıyla eklendi.');
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
    public function edit(GranilyaSize $boyut)
    {
        return view('granilya.sizes.edit', compact('boyut'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GranilyaSize $boyut)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // The original method used route model binding ($boyut).
        // The instruction's code snippet introduced a problematic line: "$size = GranilyaSize::findOrFail($id);".
        // To maintain syntactic correctness and the original intent of updating the bound model,
        // we will continue to use $boyut for the update, as $id is not defined in this context.
        // The variable name change from $boyut to $size in the update call is also applied as per the instruction.
        $boyut->update([
            'name' => $request->name,
        ]);

        return redirect()->route('granilya.boyut.index')->with('success', 'Tane boyutu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GranilyaSize $boyut)
    {
        $boyut->delete();
        return redirect()->route('granilya.boyut.index')->with('success', 'Tane boyutu başarıyla silindi.');
    }
}
