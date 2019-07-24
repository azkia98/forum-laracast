@component('profiles.activities.activity')
    @slot('header')
        <div>
            {{ $profileUser->name }} replied to <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>        

        </div>
    @endslot
    @slot('body')
        
        <p class="card-text">{{ $activity->subject->body }}</p>
    @endslot
@endcomponent

