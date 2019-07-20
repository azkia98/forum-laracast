<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();


        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());


        $r = $this->get($response->headers->get('Location'));

        $r->assertSee($thread->title)->assertSee($thread->body);
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')->assertRedirect('login');
        $this->post('/threads')->assertRedirect('login');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {

        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    
    /** @test */
    public function a_thread_requires_a_body()
    {

        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }



    /** @test */
    public function a_thread_requires_a_valid_channel()
    {

        factory('App\Channel',2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');


        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');


    }


    public function publishThread($overrides = [])
    {

        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('threads', $thread->toArray());
    }
}