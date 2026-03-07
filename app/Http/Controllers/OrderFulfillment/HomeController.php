<?php

namespace App\Http\Controllers\OrderFulfillment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;

class HomeController extends Controller
{
    public function index()
    {
        $allOpen = Order::where('status', Order::STATUS_OPEN)
            ->with(['createdBy', 'items'])
            ->latest()
            ->take(20)
            ->get();

        $openFritOrders    = Order::where('status', Order::STATUS_OPEN)->where('type', 'frit')->count();
        $openGranilyaOrders = Order::where('status', Order::STATUS_OPEN)->where('type', 'granilya')->count();

        $insufficientCount = $allOpen->filter(function ($o) {
            return !$o->analyzeStock()['is_sufficient'];
        })->count();

        $fritStock     = Order::getFritStock();
        $granilyaStock = Order::getGranilyaStock();

        return view('orders.home', compact(
            'allOpen', 'openFritOrders', 'openGranilyaOrders',
            'insufficientCount', 'fritStock', 'granilyaStock'
        ));
    }
}
