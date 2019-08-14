<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUserTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $mahdi = create('App\User', ['name' => 'Mahdi']);

        $this->signIn($mahdi);

        $ali = create('App\User', ['name' => 'Ali']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => '@Ali look at this. and @reza lookout this.'
        ]);

        $this->json(
            'post',
            "/threads/{$thread->channel->slug}/{$thread->slug}/replies",
            $reply->toArray()
        );

        $this->assertCount(1,$ali->notifications);
    }

    /** @test */
    public function it_can_fetch_mall_mentioned_users_starting_with_the_given_characters()
    {

        create('App\User',['name' => 'ali']);
        create('App\User',['name' => 'ali2']);
        create('App\User',['name' => 'mahdi']);

        $res = $this->json('GET','/api/users',['name' => 'ali']);

        $this->assertCount(2,$res->json());
    }
}
