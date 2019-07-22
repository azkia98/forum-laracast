@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Thread</div>

                <div class="card-body">
                    @foreach ($threads as  $thread)
                       <article>
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
                            <div class="body">{{ $thread->body }}</div>
                        </article> 
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

