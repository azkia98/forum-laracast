<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use App\Traits\RecordsActivity;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $with = ['creator'];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });

    }


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


    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    // public function getReplyCountAttribute()
    // {
    //     return $this->replies()->count();
    // }
}
