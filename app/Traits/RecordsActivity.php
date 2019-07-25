<?php

namespace App\Traits;

use App\Activity;
use ReflectionClass;
use phpDocumentor\Reflection\Types\This;

/*
* This trait created for adding this ability for saving activity.
*/

trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {


        foreach(static::getActivitiesToRecord() as $event){
            static::$event(function ($model) use($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function($model){
            $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        if (!auth()->check()) return;


        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }

    public function activity(){
        return $this->morphMany('App\Activity','subject');
    }
}
