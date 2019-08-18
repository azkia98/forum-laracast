{{--  Viewing  --}}
<div class="card mb-1" v-if="! editing">
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
    </div>

    <div class="card-body">
        <article>
            <h4 v-text="title">
            </h4>
            <div class="body" v-text="body"></div>
        </article>
    </div>

    <div class="card-footer">
        <button class="btn btn-xs btn-outline-secondary btn-sm" @click="editing = true">Edit</button>
    </div>
</div>


{{--  Editing  --}}
<div class="card mb-1" v-if="editing">
    <div class="card-header d-flex justify-content-between  align-items-center bg-white">
        <input type="text" class="form-control" v-model="form.title">
    </div>

    <div class="card-body">
        <textarea class="form-control" cols="30" rows="10" v-model="form.body"></textarea>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <div>
            <button class="btn btn-primary btn-sm" @click="update">Update</button>
            <button class="btn btn-outline-secondary btn-sm" @click="resetForm()">Cancel</button>
        </div>

        @can('update', $thread)
        <form class="form-inline" method="post" action="{{ $thread->path() }}">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-link">Delete</button>
        </form>
        @endcan
    </div>
</div>