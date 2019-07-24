<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Favoritable;
use App\Traits\RecordsActivity;

class Reply extends Model
{

    use Favoritable,RecordsActivity;


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
