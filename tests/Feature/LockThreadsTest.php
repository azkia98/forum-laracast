<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-thread.store', $thread), ['locked' => true])
            ->assertStatus(403);

        $this->assertFalse(!!$thread->fresh()->locked);

    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        $this->signIn(create('App\User',['admin' => true]));

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-thread.store', $thread), ['locked' => true]);

        $this->assertTrue(!! $thread->fresh()->locked);

    }


    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(Response::HTTP_LOCKED);
    }
}
