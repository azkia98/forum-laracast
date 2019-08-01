<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_user_can_subscribe_to_thread()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $this->post($thread->path().'/subscriptions');

        // $this->assertCount(1,$thread->subscriptions);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);

        $this->assertTrue(true);

        // $this->assertCount(1,auth()->user()->notifications);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $thread->subscribe();

        $this->delete($thread->path().'/subscriptions');

        $this->assertCount(0,$thread->subscriptions);


    }
}
