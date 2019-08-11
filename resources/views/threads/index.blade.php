@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')

            {{ $threads->links() }}
        </div>
        @if (count($trending))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Trending Threads
                    </div>
                    <div class="card-body ">
                        <ul class="list-group border-0">
                            @foreach ($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">
                                        {{ str_limit($thread->title,30) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection