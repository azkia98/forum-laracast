@component('mail::message')
# One Last Step

We just need you to confirm your email address to prove that you're a human. You get it, right? cool.

@component('mail::button', ['url' => '#'])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
