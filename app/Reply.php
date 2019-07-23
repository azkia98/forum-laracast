<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Favoritable;

class Reply extends Model
{

    use Favoritable;


    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = ['owner', 'favorites'];



    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }




}
