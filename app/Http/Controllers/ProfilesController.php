<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Collection;

class ProfilesController extends Controller
{
    public function show(User $user)
    {


        // return $activities;
        return view('profiles.show',[
            'profileUser' => $user,
            'activities' => $this->getActivities($user)
        ]);
    }

    protected function getActivities(User $user) :Collection
    {
        return $user->activities()->latest()->with('subject')->get()->groupBy(function($item,$key){
            return $item->created_at->format('Y-m-d');
        });
    }
}
