<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{

    public function store(Request $request)
    {


        $request->validate([
            'avatar' => ['required','image']
        ]);

        auth()->user()->update([
            'avatar_path' => $file =  $request->file('avatar')->store('avatars','public')
        ]);

        


        return back();
        
    }
}
