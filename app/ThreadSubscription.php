<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

/**
 * App\ThreadSubscription
 *
 * @property int $id
 * @property int $user_id
 * @property int $thread_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Thread $thread
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThreadSubscription whereUserId($value)
 * @mixin \Eloquent
 */
class ThreadSubscription extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function notify($reply)
    {
        $this->user->notify(new ThreadWasUpdated($this->thread,$reply));
    }
}
