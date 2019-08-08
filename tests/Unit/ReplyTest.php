<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class ReplyTest extends TestCase
{

    use DatabaseMigrations;

    public function test_it_has_an_owner(){
        $reply = factory(Reply::class)->create();

        $this->assertInstanceOf(User::class,$reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {

        $reply = factory(Reply::class)->create();

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();


        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('App\Reply',[
            'body' => '@Ali wants to talk to @Mahdi'
        ]);


        $this->assertEquals(['Ali','Mahdi'],$reply->mentionedUsers());
    }


    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @Mahdi'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Mahdi">@Mahdi</a>',
            $reply->body
        );

    }
}
