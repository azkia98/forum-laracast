<div class="card my-2">
    <div class="card-header">
        <a href="#">{{ $reply->owner->name }}</a>
        <span>said at </span>
        <span>{{ $reply->created_at->diffForHumans() }}</span>
    </div>
    <div class="card-body">
        <article>
            <div class="body">{{ $reply->body }}</div>
        </article>

    </div>
</div>