<div id="reply-{{ $reply->id }}" class="card my-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('profiles', $reply->owner) }}">{{ $reply->owner->name }}</a>
            <span>said at </span>
            <span>{{ $reply->created_at->diffForHumans() }}</span>
        </div>
        <div>
            <form method="POST" action="{{ url("/replies/{$reply->id}/favorites") }}">
                @csrf()
                <button  type="submit" class="btn btn-outline-secondary btn-sm" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ str_plural('Favorite',$reply->favorites_count) }} {{ $reply->favorites_count }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <article>
            <div class="body">{{ $reply->body }}</div>
        </article>

    </div>
</div>