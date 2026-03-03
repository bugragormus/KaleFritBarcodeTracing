<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use App\Models\GranilyaCrusher;
use Illuminate\Http\Request;

class CrusherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crushers = GranilyaCrusher::orderBy('name')->get();
        return view('granilya.crushers.index', compact('crushers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('granilya.crushers.create');
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

        GranilyaCrusher::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->route('granilya.kirici.index')->with('success', 'Kırıcı makina başarıyla eklendi.');
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
    public function edit(GranilyaCrusher $kirici)
    {
        return view('granilya.crushers.edit', compact('kirici'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GranilyaCrusher $kirici)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $kirici->update([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => $request->boolean('is_active', false)
        ]);

        return redirect()->route('granilya.kirici.index')->with('success', 'Kırıcı makina başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GranilyaCrusher $kirici)
    {
        $kirici->delete();
        return redirect()->route('granilya.kirici.index')->with('success', 'Kırıcı makina başarıyla silindi.');
    }
}
