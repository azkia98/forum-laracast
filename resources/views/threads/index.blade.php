@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($threads as $thread)
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>
                            <a href="{{ $thread->path() }}">
                                {{ $thread->title }}
                            </a>
                        </h4>
                        <strong>
                            <a href="{{ $thread->path() }}">
                                {{ str_plural('reply',$thread->replies_count) }} {{ $thread->replies_count }}
                            </a>
                        </strong>
                    </div>
                </div>
                <div class="card-body">
                    <div class="body">{{ $thread->body }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection