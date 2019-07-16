<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Thread;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function a_user_can_browse_threads()
    {

        $thread = factory(Thread::class)->create();

        $response = $this->get('/threads');

        $response->assertStatus(200);

        $response->assertSee($thread->title);


    }


    /** @test */
    public function a_user_can_read_a_single_thread(){
        $thread = factory(Thread::class)->create();
        
        $response = $this->get('/threads/'.$thread->id);
        $response->assertSee($thread->title); 
    }
}
