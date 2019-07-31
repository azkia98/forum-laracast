<?php

namespace App\Filters;

use Symfony\Component\HttpFoundation\Request;
use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query with given username.
     *
     * @param string $username
     * @return void
     */
    protected function by(string $username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popular threads.
     *
     * @return void
     */
    protected function popular(): void
    {


        // $this->builder->orders = null;
        $this->builder->getQuery()->orders = [];

        $this->builder->orderBy('replies_count', 'desc');
    }


    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
