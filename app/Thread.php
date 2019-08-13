<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;
use App\Events\ThreadReceivedNewReply;

/**
 * App\Thread
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int $channel_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read \App\Channel $channel
 * @property-read \App\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reply[] $replies
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUserId($value)
 * @mixin \Eloquent
 * @property int $replies_count
 * @property int|null $visits
 * @property-read mixed $is_subscribed_to
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ThreadSubscription[] $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereRepliesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereVisits($value)
 */
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isSubscribedTo'];


    protected static function boot()
    {
        parent::boot();


        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }


    public function path()
    {
        return url("/threads/{$this->channel->slug}/{$this->slug}");
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
        $reply = $this->replies()->create($reply);

        // $this->notifySubscribers($reply);

        event(new ThreadReceivedNewReply($reply));


        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }


    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }


    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function hasUpdatesFor()
    {
        $key = '';
        if (auth()->check())
            $key =  auth()->user()->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }


        $this->attributes['slug'] = $slug;
    }


    public function incrementSlug($slug)
    {
       $max = static::whereTitle($this->title)->latest('id')->value('slug');


       if(is_numeric($max[-1]))
       {
           return preg_replace_callback('/(\d+)$/',function($matches) {
               return end($matches) + 1;
           },$max);
       }

       return "{$slug}-2";

    }
}
