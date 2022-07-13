<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes();

Route::post('/{token}/webhook', 'BotController@index');


Route::get('/getUpdates', 'BotController@longpull');


Route::middleware('if_shop')->group(function () {
	Route::get('/create', 'ShopController@index')->name('shop.create');

    Route::get('/switch', function() {
        $user = auth()->user();
        $shop = $user->shops()->where('id', '!=', $user->current_shop->id)->first();
        if ($shop == null) {
            return  redirect()->back();
        }
        $user->update(['current_shop' => $shop->id]);
        return redirect()->back();
    })->name('shop.switch');

	Route::get('/catalog/create', 'CatalogController@create')->name('catalog.create');

    Route::get('/flower/create', 'FlowerController@create')->name('flower.create');

    Route::get('/statistic/flowers', function() {
        return view('shop.statistic.flowers');
    })->name('statistic.flowers');

	Route::get('/statistic/catalogs', function () {
		return view('shop.statistic.catalogs');
	})->name('statistic.catalogs');

	Route::get('/statistic/orders', function () {
		return view('shop.statistic.orders');
	})->name('statistic.orders');

	Route::post('/shop/save', 'ShopController@store')->name('shop.save');

	Route::post('/catalogs/save', 'CatalogController@store')->name('catalog.store');

    Route::post('/flowers/save', 'FlowerController@store')->name('flower.store');

	Route::post('/catalogs/import', 'CatalogController@import')->name('catalog.import');

	Route::post('/bot/{token}/webhook', 'ShopController@bot')->name('shop.bot');

	Route::get('/getAdmin', function() {
		$user = User::find(auth()->user()->id);
		$user->is_admin = true;
		$user->save();
		return redirect(route('admin.users'));
	});
});

Route::group(['prefix' => 'admin','as' => 'admin.', 'middleware' => 'is_admin'], function(){
    Route::get('/clients', function () {
        return view('shop.statistic.clients');
    })->name('statistic.users');

    Route::get('/cards', function() {
        return view('shop.statistic.cards');
    })->name('statistic.cards');

    Route::get('/card/create', function() {
        return view('shop.card.create');
    })->name('card.create');

    Route::post('/cards/save', 'CardController@store')->name('card.store');

    Route::get('/deliveries', function() {
        return view('shop.statistic.deliveries');
    })->name('statistic.deliveries');

    Route::get('/delivery/create', function() {
        return view('shop.delivery.create');
    })->name('delivery.create');

    Route::post('/deliveries/save', 'DeliveryController@store')->name('delivery.store');

	Route::get('/users', function() {
        return view('admin.users');
    })->name('users');

    Route::get('/user/create', function() {
        return view('admin.user.create');
    })->name('user.create');

    Route::post('/user/save', 'AdminController@store')->name('user.store');

	Route::get('/mailing', function () {
		return view('admin.mailing');
	})->name('mailing.create');

	Route::post('/mailing/save', 'BotController@mailing')->name('mailing.save');
});


Route::get('/', 'Auth\LoginController@showLoginForm')->name('showLoginForm');

Route::post('/login', 'Auth\LoginController@login')->name('login');


