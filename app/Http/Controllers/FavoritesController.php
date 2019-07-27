<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favorite;

class FavoritesController extends Controller
{
    public function store(Reply $reply)
    {
        $reply->favorite();

        return redirect()->back();
    }


    public function destroy(Reply $reply)
    {
       $reply->unFavorite();

       return ['status' => 'ok'];
    }
}
