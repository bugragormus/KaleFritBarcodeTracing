<?php

namespace App\Http\Controllers\Granilya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function production()
    {
        return view('granilya.placeholder', ['title' => 'Üretim Girişi']);
    }

    public function stock()
    {
        return view('granilya.placeholder', ['title' => 'Stok Durumu']);
    }

    public function laboratory()
    {
        return view('granilya.placeholder', ['title' => 'Laboratuvar']);
    }

    public function report()
    {
        return view('granilya.placeholder', ['title' => 'Üretim Raporu']);
    }

    public function sales()
    {
        return view('granilya.placeholder', ['title' => 'Satış Ekranı']);
    }

    public function barcode()
    {
        return view('granilya.placeholder', ['title' => 'Barkod Sorgu']);
    }
}
