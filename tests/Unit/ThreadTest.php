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


        $this->assertCount(1,$this->thread->replies);
     }
}
