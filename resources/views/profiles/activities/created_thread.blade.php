@component('profiles.activities.activity')
    @slot('header')

        <div>
            {{ $profileUser->name }} published a <a href="{{ $activity->subject->path() }}">thread</a>        
        </div>
        
    @endslot
    @slot('body')
        <p class="card-text">{{ $activity->subject->body }}</p>
    @endslot
@endcomponent
