@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>

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

                <replies  @removed="repliesCount--" @added="repliesCount++"></replies> 

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a> posted at: {{ $thread->created_at->diffForHumans() }}
                    </div>

                    <div class="card-body">

                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by 
                            <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a>, and currently has <span v-text="repliesCount"></span> {{ str_plural('comment',$thread->replies_count) }}.
                        </p>


                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection