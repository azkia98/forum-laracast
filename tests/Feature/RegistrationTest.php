<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\User;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'mahdi',
            'email' => 'mahdi@email.com',
            'password' => 'foobar1234',
            'password_confirmation' => 'foobar1234'
        ]);


        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();
        $this->post('/register', [
            'name' => 'mahdi',
            'email' => 'mahdi@email.com',
            'password' => 'foobar1234',
            'password_confirmation' => 'foobar1234'
        ]);

        $user = User::whereName('mahdi')->first();


        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $response = $this->get("/register/confirm?token={$user->confirmation_token}");

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect(route('threads'));
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalidtoken']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
