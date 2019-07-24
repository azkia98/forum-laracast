<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    
    // /** @test */
    // public function it_records_activity_when_a_thread_is_created(){
    //     $thread = create('App\Thread');

    //     $this->signIn();

    //     $this->assertDatabaseHas('activities',[
    //         'type' => 'created_thread',
    //         'user_id' => auth()->id(),
    //         'subject_id' => $thread->id,
    //         'subject_type'  => 'App\Thread'
    //     ]);
    // }
}
