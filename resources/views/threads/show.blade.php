@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row ">
        <div class="col-md-8">
            <div class="card mb-1">
                <div class="card-header d-flex justify-content-between  align-items-center">
                    
                    <div>
                        <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a> posted at: {{ $thread->created_at->diffForHumans() }}
                    </div>
                    <div>
                        @can('update', $thread)
                            <form class="form-inline" method="post" action="{{ $thread->path() }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-link">Delete</button>
                            </form>
                        @endcan
                    </div>
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
            @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach
            {{ $replies->links() }}
            @auth
                <form action="{{ $thread->path() . '/replies' }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" id="message" placeholder="Have something to say?" rows="5" class="form-control"></textarea>
                        <button type="submit" class="btn btn-secondary  mt-1">Post</button>
                    </div>
                </form>
            @endauth
            @guest
                <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
            @endguest
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a> posted at: {{ $thread->created_at->diffForHumans() }}
                </div>

                <div class="card-body">

                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }} by 
                        <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a>, and currently has {{ $thread->replies_count }} {{ str_plural('comment',$thread->replies_count) }}.
                    </p>


                </div>
            </div>
        </div>
    </div>






</div>
@endsection