<?php

namespace App\Traits;

use App\Favorite;

/**
 * Favoritable Trait for those models want to have favorites ability
 * @method bool unFavorite()
 */
trait Favoritable
{

    static public function bootFavoritable(){
        static::deleting(function($model){
            $model->favorites()->get()->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attribute = ['user_id' => auth()->id()];
        if ($this->favorites()->where($attribute)->exists())
            return;

        $this->favorites()->create($attribute);
    }


    public function unFavorite()
    {
        $attribute = ['user_id' => auth()->id()];


        return $this->favorites()->where($attribute)->get()->each->delete();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function isFavorited(): bool
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites->count();
    }
}
