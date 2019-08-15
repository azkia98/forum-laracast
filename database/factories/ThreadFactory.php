<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Thread;
use App\User;
use App\Channel;

$factory->define(Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'title' => $title,
        'body' => $faker->paragraph,
        'channel_id' => function(){
            return factory(Channel::class)->create();
        },
        'user_id' => function () { return factory(User::class)->create()->id; },
        'visits' => 0,
        'slug' => str_slug($title),
        'locked' => false
    ];
});
