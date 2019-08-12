<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;

/**
 * App\Favorite
 *
 * @property int $id
 * @property int $user_id
 * @property int $favorited_id
 * @property string $favorited_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite whereFavoritedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite whereFavoritedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Favorite whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $favorited
 */
class Favorite extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    use RecordsActivity;


    public function favorited(){
       return $this->morphTo(); 
    }


    
}
