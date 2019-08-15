<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use Carbon\Carbon;

/**
 * App\Reply
 *
 * @property int $id
 * @property int $user_id
 * @property int $thread_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Favorite[] $favorites
 * @property-read mixed $favorites_count
 * @property-read \App\User $owner
 * @property-read \App\Thread $thread
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereUserId($value)
 * @mixin \Eloquent
 * @property-read mixed $is_favorited
 */
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

    protected $appends = ['favoritesCount','isFavorited'];

    
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleting(function ($reply) {
            $reply->thread->decrement('replies_count');


        });

    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }


    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }


    public function wasJustPublished()
    {
       return $this->created_at->gt(Carbon::now()->subMinute()); 
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/','<a href="/profiles/$1">$0</a>',$body);
    }

    public function isBest()
    {
         return $this->thread->best_reply_id == $this->id;
    }


}
