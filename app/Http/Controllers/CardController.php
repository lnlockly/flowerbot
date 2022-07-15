<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, Card $card){
        $card->name = $request->name;
        $card->shop_id = auth()->user()->current_shop->id;

        $card->save();
        notify()->success('Карта добавлена', '');
        return redirect()->back();
    }
}
