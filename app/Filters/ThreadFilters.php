<?php

namespace App\Filters;

use Symfony\Component\HttpFoundation\Request;
use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

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
}
