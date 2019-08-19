<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

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


        $response = $this->get(route('register.confirm', ['token' => $user->confirmation_token]));


        $response->assertRedirect(route('threads'));

        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalidtoken']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
