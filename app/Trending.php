<?php
namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{

    public $cacheKey = 'trending_threads';
    
    public function get(): array
    {
        return array_map('json_decode',Redis::zrevrange($this->cacheKey,0,4));
    }

    public function push($thread): bool
    {
        Redis::zincrby($this->cacheKey,1,json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));

        return true;
    }


    // public function cacheKey(): string
    // {
    //     return 'trending_threads';
    // }

    public function reset(): void
    {
        
        Redis::del($this->cacheKey);
    }
}