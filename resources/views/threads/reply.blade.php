<reply inline-template :attributes="{{ $reply }}" v-cloak>
    <div id="reply-{{ $reply->id }}" class="card my-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('profiles', $reply->owner) }}">{{ $reply->owner->name }}</a>
                <span>said at </span>
                <span>{{ $reply->created_at->diffForHumans() }}</span>
            </div>
            <div>
                <favorite :reply="{{ $reply }}"></favorite>
                {{--  <form method="POST" action="{{ url("/replies/{$reply->id}/favorites") }}">
                    @csrf()
                    <button  type="submit" class="btn btn-outline-secondary btn-sm" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ str_plural('Favorite',$reply->favorites_count) }} {{ $reply->favorites_count }}
                    </button>
                </form>  --}}
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body">{{ $reply->body }}</textarea>
                </div>

                <button class="btn btn-sm btn-primary" @click="update()">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else>
                <div class="body" v-text="body"></div>
            </div>
        </div>
        @can('update', $reply)
            <div class="card-footer d-flex">
                <button class="btn btn-sm btn-outline-secondary mr-1" @click="editing = true">Edit</button>
                <button type="submit" class="btn btn-outline-danger btn-sm" @click="destroy()">Delete</button>
            </div>
        @endcan
    </div>

</reply>
