@component('profiles.activities.activity')
    @slot('header')

        <div>
            {{ $profileUser->name }} published a <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>        
        </div>
        
    @endslot
    @slot('body')
        <p class="card-text">{{ $activity->subject->body }}</p>
    @endslot
@endcomponent
