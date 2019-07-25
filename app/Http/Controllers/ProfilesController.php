<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use App\Activity;

class ProfilesController extends Controller
{
    public function show(User $user)
    {


        // return $activities;
        return view('profiles.show',[
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }

    protected function getActivities(User $user) 
    {
   }
}
