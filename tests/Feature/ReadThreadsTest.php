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


    public function setUp() :void
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
        
        $response = $this->get($this->thread->path());

        $response->assertSee($reply->body);

        

        
    }
}
