@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="#">{{ $thread->creator->name }}</a> posted at: {{ $thread->created_at }}
                </div>

                <div class="card-body">
                    <article>
                        <h4>
                            {{ $thread->title }}
                        </h4>
                        <div class="body">{{ $thread->body }}</div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>

    </div>

    @auth
        <div class="row justify-content-center">
            <div class="col-md-8 mt-1">
                <form action="{{ $thread->path() . '/replies' }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" id="message" placeholder="Have something to say?" rows="5" class="form-control"></textarea>
                        <button type="submit" class="btn btn-light mt-1">Post</button>
                    </div>
                </form>
            </div>
        
        

        </div>
    @endauth

    @guest
        <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
    @endguest


</div>
@endsection