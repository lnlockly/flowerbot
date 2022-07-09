<?php

namespace App\Http\Controllers;

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
		return view('shop/catalog/create');
	}

	public function store(StoreCatalogRequest $request) {
		$catalog = new Catalog;

		$catalog->active = "1";
		$catalog->section1 = $request->section1;
		$catalog->name = $request->name;
		$catalog->description = $request->description;
		$catalog->url = $request->url;
		$catalog->img = $request->img;
		$catalog->price = $request->price;
		$catalog->shop_id = auth()->user()->current_shop->id;

		$catalog->save();
        notify()->success('Букет успешно добавлен!', '');
		return redirect()->route('statistic.catalogs');
	}

}
