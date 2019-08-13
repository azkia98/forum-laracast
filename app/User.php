<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\RecordsActivity;
use Carbon\Carbon;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activities
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Thread[] $threads
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $avatar_path
 * @property int $confirmed
 * @property-read mixed $avatar
 * @property-read \App\Reply $lastReply
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatarPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereConfirmed($value)
 * @property string|null $confirmation_token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereConfirmationToken($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'confirmed' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }


    public function threads(){
        return $this->hasMany(Thread::class)->latest();
    }


    public function activities(){
        return $this->hasMany(Activity::class);
    }


    public function visitedThreadCacheKey($thread)
    {
        
       return  sprintf('users.%s.visits.%s',$this->id,$thread->id);

    }

    public function read($thread)
    {
        cache()->forever(
            auth()->user()->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }


    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarAttribute()
    {
       return asset($this->avatar_path ?: 'avatars/default.png');
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }
}
