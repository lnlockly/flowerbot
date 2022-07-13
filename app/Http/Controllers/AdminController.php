<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function store(Request $request, User $user) {
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->current_shop = auth()->user()->current_shop->id;

        $user->save();

        notify()->success('Пользователь добавлен','');
        return redirect()->back();

    }
}
