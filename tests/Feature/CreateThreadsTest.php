<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return $this->mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function a_user_can_create_new_forum_threads()
    {
        $this->signIn();


        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray() + ['recaptcha_res' => 'token']);


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

        $user = create('App\User', ['confirmed' => false]);

        $this->signIn($user);


        $this->post('/threads', make('App\Thread')->toArray())
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
    public function a_thread_requires_recaptcha_verification()
    {
        $this->withExceptionHandling();

        unset(app()[Recaptcha::class]);

        $this->publishThread(['recaptcha_res' => 'test'])
            ->assertSessionHasErrors('recaptcha_res');
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
    public function an_authorized_user_can_delete_thread()
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

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $string = 'foo-title';

        create('App\Thread', [], 2);

        $thread = create('App\Thread', ['title' => 'Foo Title']);


        $t = $this->postJson(route('threads'), $thread->toArray() + ['recaptcha_res' => 'test'])->json();


        $this->assertEquals("$string-{$t['id']}", $t['slug']);

        $t = $this->postJson(route('threads'), $thread->toArray()+ ['recaptcha_res' => 'test'])->json();


        $this->assertTrue(Thread::whereSlug("$string-{$t['id']}")->exists());
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $t = $this->postJson(route('threads'), $thread->toArray()+ ['recaptcha_res' => 'test'])->json();



        $this->assertTrue(Thread::whereSlug("some-title-24-{$t['id']}")->exists());
    }
}
