@extends('layouts.app')

@section('content')

<div class="container">
    <div class="jumbotron ">
        <h1 class="display-4">{{ $profileUser->name }} <small class="font-italic text-muted">since {{ $profileUser->created_at->diffForHumans() }}</small></h1>
        <p class="lead">{{ $profileUser->email }}</p>
        {{--  <hr class="my-4">  --}}
    </div>


    @foreach ($threads as $thread)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
              <a href="{{ $thread->path() }}">{{ $thread->title }}</a>  
              <a href="{{ $thread->path() }}">{{ str_plural('reply',$thread->replies_count) }} {{ $thread->replies_count }}</a>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $thread->body }}</p>
            </div>
            {{--  <div class="card-footer">
                Plublished At: {{ $thread->created_at->diffForHumans() }}
            </div>  --}}
        </div>
    @endforeach

    <div class="mt-3">
        {{ $threads->links() }}

    </div>
</div>
@endsection