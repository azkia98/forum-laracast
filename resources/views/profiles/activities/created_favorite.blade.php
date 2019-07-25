@component('profiles.activities.activity')
    @slot('header')
        <div>
            {{ $profileUser->name }} favorited this <a href="{{ $activity->subject->favorited->path() }}">Reply</a>        

        </div>
    @endslot
    @slot('body')
        
        <p class="card-text">{{ $activity->subject->favorited->body }}</p>
    @endslot
@endcomponent

