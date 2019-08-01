<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Thread;
use Illuminate\Database\Eloquent\Collection;
use App\User;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    private $thread;

    public function setUp(): void
    {
        parent::setUp();


        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $this->assertEquals(url("/threads/{$this->thread->channel->slug}/{$this->thread->id}"), $this->thread->path());
    }

    /** @test */
    public function a_thread_has_replies()
    {

        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }


    /** @test */
    public function a_thread_has_a_creator()
    {

        $this->thread = factory(Thread::class)->create();

        $this->assertInstanceOf(User::class, $this->thread->creator);
    }


    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);


        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belong_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');


        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        
        $thread = create('App\Thread');


        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0,$thread->subscriptions);

    }
}
