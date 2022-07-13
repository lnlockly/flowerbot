<?php

namespace App\Http\Controllers;

use App\Flower;
use App\Http\Requests\StoreCatalogRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Catalog;
use App\Imports\CatalogsImport;

class CatalogController extends Controller
{

	public function index() {
	}

	public function create() {
        $flowers = Flower::where('shop_id', auth()->user()->current_shop->id)->get();
        if ($flowers == null) {
            notify()->error('Сначала добавьте цветы', '');
            return  redirect()->back();
        }
		return view('shop/catalog/create', ['flowers' => $flowers]);
	}

	public function store(Request $request) {
		$catalog = new Catalog;

		$catalog->section1 = $request->section1;
		$catalog->name = $request->name;
		$catalog->img = $request->img;
        $catalog->flowers = $request->flowers;
		$catalog->price = $request->price;
		$catalog->shop_id = auth()->user()->current_shop->id;

		$catalog->save();
        notify()->success('Букет успешно добавлен!', '');
		return redirect()->route('statistic.catalogs');
	}

}
