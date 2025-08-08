<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quantity\QuantityStoreRequest;
use App\Http\Requests\Quantity\QuantityUpdateRequest;
use App\Models\Permission;
use App\Models\Quantity;

class QuantityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $quantities = Quantity::get();

        return view('admin.quantity.index', compact('quantities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        return view('admin.quantity.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Quantity\QuantityStoreRequest $request
     * @return string
     */
    public function store(QuantityStoreRequest $request)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        try {
            $data = $request->validated();
            
            $quantity = Quantity::create($data);
            
            if ($quantity) {
                toastr()->success('Adet girişi başarılı.');
            } else {
                toastr()->error('Adet girişi yapılamadı.');
            }
            
        } catch (\Exception $e) {
            toastr()->error('Bir hata oluştu: ' . $e->getMessage());
        }

        return redirect()->route('quantity.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $quantity = Quantity::findOrFail($id);

        return view('admin.quantity.edit', compact('quantity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Quantity\QuantityUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(QuantityUpdateRequest $request, $id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        try {
            $quantity = Quantity::findOrFail($id);
            
            // Manuel olarak güncelle
            $quantity->quantity = $request->input('quantity');
            $saved = $quantity->save();
            
            if ($saved) {
                // Cache temizleme
                \Cache::flush();
                
                toastr()->success('Adet girişi başarıyla düzenlendi.');
            } else {
                toastr()->error('Adet girişi düzenlenemedi.');
            }
            
        } catch (\Exception $e) {
            toastr()->error('Bir hata oluştu: ' . $e->getMessage());
        }

        return redirect()->route('quantity.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermission(Permission::MANAGEMENT)) {
            toastr()->error('Yönetim izniniz bulunmamaktadır.');
            return back()->withInput();
        }

        $quantity = Quantity::findOrFail($id);

        $quantity->delete();

        if (!$quantity) {
            toastr()->error('Adet girişi silinemedi.');
        }

        return response()->json(['message' => 'Adet girişi silindi!']);
    }
}
