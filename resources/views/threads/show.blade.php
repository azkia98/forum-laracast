@extends('layouts.app')

@section('header')
<link href="{{ asset('css/vendor/jquery.atwho.css') }}" rel="stylesheet">

<script>
    window.thread = @json($thread); 
</script>
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template v-cloak>

    <div class="container">
        <div class="row ">
            <div class="col-md-8">

                @include('threads._question')


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