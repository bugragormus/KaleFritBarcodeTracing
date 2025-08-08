<?php

namespace App\Http\Controllers;


use App\Models\Barcode;
use App\Models\Company;
use App\Models\Kiln;
use App\Models\Quantity;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userCount = User::count();
        $companyCount = Company::count();
        $warehouseCount = Warehouse::count();
        $barcodeCount = Barcode::count();
        $stockCount = Stock::count();
        $kilnCount = Kiln::count();

        return view('admin.home', compact([
            'userCount',
            'companyCount',
            'warehouseCount',
            'barcodeCount',
            'stockCount',
            'kilnCount',
        ]));
    }
}
