<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();


        $thread = create('App\Thread');

        $this->post('/threads', $thread->toArray());


        $r = $this->get($thread->path());

        $r->assertSee($thread->title)->assertSee($thread->body);
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')->assertRedirect('login');
        $this->post('/threads')->assertRedirect('login');
    }
}
