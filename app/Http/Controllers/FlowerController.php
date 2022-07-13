<?php

namespace App\Http\Controllers;

use App\Flower;
use Illuminate\Http\Request;

class FlowerController extends Controller
{

    public function create() {
        return view('shop/flower/create');
    }

    public function store(Request $request, Flower $flower) {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $flower->name = $request->name;
        $flower->shop_id = auth()->user()->current_shop->id;

        $flower->save();

        notify()->success('Цветок успешно добавлен!', '');

        return redirect()->back();

    }
}
