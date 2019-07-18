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
                    <form method="post" action="{{ route('threads.store') }}">
                        @csrf
                       <div class="form-group">
                           <label for="title">Title:</label>
                           <input id="title" class="form-control" type="text" name="title">
                       </div> 
                       <div class="form-group">
                           <label for="body">Body:</label>
                           <textarea id="body" class="form-control" rows="8" name="body"> </textarea>
                       </div>


                       <button type="submit" class="btn btn-secondary">Publish</button>

                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection