<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Thread;
use App\Reply;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    private $thread;


    public function setUp(): void
    {
        parent::setUp();


        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_user_can_browse_threads()
    {


        $response = $this->get('/threads');

        $response->assertStatus(200);

        $response->assertSee($this->thread->title);
    }


    /** @test */
    public function a_user_can_read_a_single_thread()
    {

        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }


    /**
     * @test
     */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {

        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id,'thread_id' => $this->thread->id]);

    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');


        $this->withoutExceptionHandling();

        $this->get("/threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }


    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'mahdi']));

        $this->withoutExceptionHandling();
        $threadByMahdi = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByMahdi = create('App\Thread');

        $this->get('/threads?by=mahdi')
            ->assertSee($threadByMahdi->title)
            ->assertDontSee($threadNotByMahdi->title);
    }



    /** @test */
    public function a_user_can_filter_threads_by_populatiry()
    {

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply',['thread_id' => $threadWithTwoReplies->id],2);


        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply',['thread_id' => $threadWithThreeReplies->id],3);


        $threadWithNoReplies = $this->thread;


        $response  = $this->getJson('/threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }


    /** @test */
    public function a_user_can_filter_threads_by_those_that_unasnwered()
    {
       $thread = create('App\Thread');
       create('App\Reply',['thread_id' => $thread->id]); 
       
        $response  = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(1,$response);

    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply',['thread_id' => $thread->id],2);

        $response = $this->getJson($thread->path() .'/replies')->json();


        
        $this->assertEquals(2,$response['total']);

    }
}
