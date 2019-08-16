@extends('layouts.app')

@section('header')
<link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">

<script>
    window.thread = @json($thread); 
</script>
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template>

    <div class="container">
        <div class="row ">
            <div class="col-md-8">
                <div class="card mb-1">
                    <div class="card-header d-flex justify-content-between  align-items-center">

                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <img src="{{ $thread->creator->avatar }}" alt="" width="25" height="25">
                            </div>
                            <div>
                                <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a>
                                posted at: {{ $thread->created_at->diffForHumans() }}
                            </div>
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

                <replies @removed="repliesCount--" @added="repliesCount++"></replies>

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a> posted at:
                        {{ $thread->created_at->diffForHumans() }}
                    </div>

                    <div class="card-body">

                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a>, and
                            currently has <span v-text="repliesCount"></span>
                            {{ str_plural('comment',$thread->replies_count) }}.
                        </p>


                        <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn">
                        </subscribe-button>

                        <button class="btn" v-if="authorize('admin')"
                            :class="locked ? 'btn-danger' : 'btn-outline-danger'" 
                            @click="lockToggle">@{{ this.locked ? 'Lock' : 'Unlock' }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection