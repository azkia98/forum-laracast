<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $thread = create('App\Thread',['user_id' => auth()->id()]);

        $replies = create('App\Reply',['thread_id' => $thread->id],2);

        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->postJson(route('best-replies.store',$replies[1]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }


    /** @test */
    public function only_the_thread_creator_may_mark_a_reply_as_best()
    {

        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread',['user_id' => auth()->id()]);

        $replies = create('App\Reply',['thread_id' => $thread->id],2);

        $this->signIn(create('App\User'));

        $this->postJson(route('best-replies.store',$replies[1]))->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
