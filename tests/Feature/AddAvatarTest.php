<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->withExceptionHandling();

        $res = $this->json('post', '/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_mus_be_provided()
    {

        $this->withExceptionHandling()->signIn();

        $user = auth()->user();

        $res = $this->json('post', "/users/{$user->id}/avatar", [
            'avatar' => 'not_an_image'
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        Storage::fake('public');

        $user = auth()->user();

        $res = $this->json('post', "/users/{$user->id}/avatar", [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals("avatars/{$file->hashName()}",auth()->user()->avatar_path);

        Storage::disk('public')->assertExists("avatars/{$file->hashName()}");
    }
}
