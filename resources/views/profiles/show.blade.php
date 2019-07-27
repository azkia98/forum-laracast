@extends('layouts.app')

@section('content')

<div class="container">
    <div class="jumbotron ">
        <h1 class="display-4">{{ $profileUser->name }} <small class="font-italic text-muted">since {{ $profileUser->created_at->diffForHumans() }}</small></h1>
        <p class="lead">{{ $profileUser->email }}</p>
        {{--  <hr class="my-4">  --}}
    </div>


    @forelse ($activities as $date => $activity)
        <h4>{{ $date }}</h4> <hr>

       @foreach ($activity as $record)
            @if (view()->exists("profiles.activities.{$record->type}"))
                @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endif
       @endforeach 
       @empty
       There is no activity for this user yet.
    @endforelse

    <div class="mt-3">
        {{--  {{ $activities->links() }}  --}}

    </div>
</div>
@endsection