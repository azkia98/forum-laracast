<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Notification;
use Tests\TestCase;
use Carbon\Carbon;

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

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_use_is_subscribed_to_it()
    {

        $thread = create('App\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();


        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {

        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => 999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }


    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertTrue($thread->hasUpdatesFor(auth()->user()));

        auth()->user()->read($thread);

        // Simulate that the user visited the thread

        $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
    }

    /** @test */
    public function a_thread_records_each_visit()
    {

        

        $thread = make('App\Thread',['id' => 1]);

        $thread->resetVisits();

        $this->assertSame(0,$thread->visits());

        $thread->recordVisit();

        $this->assertEquals(1,$thread->visits());


        $thread->recordVisit();

        $this->assertEquals(2,$thread->visits());


    }
}
