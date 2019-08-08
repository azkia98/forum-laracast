<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        return User::where('name','LIKE',"{$request->name}%")
        ->orderBy ('name')
        ->take(5)
        ->get()
        ->pluck('name');
    }
}
