@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Create a New Thread
                </div>

                <div class="card-body">
                    <form method="post" action="{{ url('/threads/') }}">
                        @csrf
                        <div class="form-group">
                            <label for="channel-select-box">Channel:</label>
                            <select id="channel-select-box" class="custom-select" name="channel_id" required>
                                <option>Chose one...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ $channel->id == old('channel_id') ? 'selected' : '' }}>{{  $channel->name }}</option>
                                @endforeach
                            </select>
                        </div>



                       <div class="form-group">
                           <label for="title">Title:</label>
                           <input id="title" class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                       </div> 
                       <div class="form-group">
                           <label for="body">Body:</label>
                           <textarea id="body" class="form-control" rows="8" name="body" required>{{ old('body') }}</textarea>
                       </div>

                       <div class="form-group">
                           <button type="submit" class="btn btn-secondary">Publish</button>
                       </div>
                       
                        @if (count($errors))
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection