<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{

    use DatabaseMigrations;



    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {

        $this->withExceptionHandling();
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray())
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make();

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray());


        $this->assertDatabaseHas('replies',['body' => $reply->body]);


        $this->assertEquals(1,$thread->fresh()->replies_count);
    }


    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = factory('App\Thread')->create();

        $reply = make('App\Reply', ['body' => null]);
        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply',['user_id' => auth()->id()]);
        
        $this->delete("/replies/{$reply->id}")->assertStatus(302);


        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
        $this->assertEquals(0,$reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create('App\Reply',['user_id' => auth()->id()]);

        $text = 'You been changed, fool.';

        $this->patch("/replies/{$reply->id}",['body' => $text]);

        $this->assertDatabaseHas('replies', ['body' => $text,'id' => $reply->id]);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->make([
            'body' => 'Yahoo Customer Support'
        ]);

        $this->expectException(\Exception::class);

        $this->post("/threads/{$thread->channel->slug}/{$thread->id}/replies", $reply->toArray());
    }
}
