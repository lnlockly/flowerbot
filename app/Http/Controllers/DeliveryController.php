<?php

namespace App\Http\Controllers;

use App\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function store(Request $request, Delivery $delivery) {
        $delivery->name = $request->name;
        $delivery->price = $request->price;
        $delivery->shop_id = auth()->user()->current_shop->id;

        $delivery->save();

        return redirect()->back();
    }
}
