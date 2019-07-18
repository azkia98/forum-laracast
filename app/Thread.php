<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function path()
    {
        return url("/threads/{$this->channel->slug}/{$this->id}");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
