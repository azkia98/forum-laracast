<?php
namespace App\Traits;

use Illuminate\Support\Facades\Redis;

/*
* This Trait Record the user's visits
*/
trait RecordsVisits
{
    
    public function recordVisit()
    {
       Redis::incr($this->visitsCacheKey()) ;

       return $this;
    }

    public function visits()
    {
       return Redis::get($this->visitsCacheKey()) ?? 0 ;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    protected function visitsCacheKey()
    {
        return "thread.{$this->id}.visits";
    }
}