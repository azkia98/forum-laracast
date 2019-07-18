<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class ParticipateInForumTest extends TestCase
{

    use DatabaseMigrations;



    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {

        $thread = factory('App\Thread')->create();
        $reply = factory(Reply::class)->make();

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray())
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = factory(User::class)->create();
        $this->be($user);


        $thread = factory('App\Thread')->create();

        $reply = factory(Reply::class)->make();

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray());


        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
