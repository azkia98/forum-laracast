<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    /** @test */
    public function a_user_can_create_new_forum_threads()
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
        $this->withExceptionHandling();
        $this->get('/threads/create')->assertRedirect(route('login'));
        $this->post('/threads')->assertRedirect('login');
    }


    /** @test */
    public function authenticated_users_must_first_confirm_their_email_address_before_creating_threads()
    {

        $user = create('App\User',['confirmed' => false]);

        $this->signIn($user);


        $this->post('/threads',make('App\Thread')->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');

        
    }


    /** @test */
    public function a_thread_requires_a_title()
    {

        $this->withExceptionHandling();
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }


    /** @test */
    public function a_thread_requires_a_body()
    {

        $this->withExceptionHandling();
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }



    /** @test */
    public function a_thread_requires_a_valid_channel()
    {

        $this->withExceptionHandling();
        factory('App\Channel', 2)->create();

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

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');


        $reply = create('App\Reply', ['thread_id' => $thread->id]);


        $this->delete($thread->path())->assertRedirect(route('login'));

        $this->signIn();

        $this->delete($thread->path())->assertStatus(403);
    }



    /** @test */
    public function an_athorized_user_can_delete_thread()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);


        $reply = create('App\Reply', ['thread_id' => $thread->id]);


        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }
}
