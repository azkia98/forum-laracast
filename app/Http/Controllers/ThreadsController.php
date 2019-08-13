<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use App\Channel;
use App\Filters\ThreadFilters;
use App\Trending;

class ThreadsController extends Controller
{


    /**
     * ThreadsController constructor
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }


        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
        ]);



        $thread = Thread::create([
            'body' => $request->body,
            'title' => $request->title,
            'channel_id' => $request->channel_id,
            'user_id' => auth()->id(),
            'slug' => $request->title
        ]);


        return redirect($thread->path())->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {

        if (auth()->check())
            auth()->user()->read($thread);

        $trending->push($thread);

        $thread->increment('visits');


        return view('threads.show', [
            'thread' => $thread,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {

        $this->authorize('update', $thread);


        $thread->delete();

        if (request()->wantsJson())
            return response([], 204);


        return redirect('/threads');
    }


    public function getThreads($channel, $filters)
    {

        $threads = Thread::with(['channel'])->latest()->filter($filters);

        if ($channel->exists) {
            $threads = Thread::where('channel_id', $channel->id);
        }

        return $threads->paginate(6);
    }
}
