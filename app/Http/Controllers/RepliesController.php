<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;
use App\Thread;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CreatePostRequest;
use App\User;
use App\Notifications\YouWereMentioned;

class RepliesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channel, Thread $thread, CreatePostRequest $form)
    {


        // if (Gate::denies('create', new Reply())) {
        //     return response('You are posting to frequently. Please take a break. :)', 422);
        // }

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);

        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::where('name',$name)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }



        $reply->load('owner');

        return response($reply, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {


        $this->authorize('update', $reply);

        try {
            $this->validate(request(), ['body' => 'required|spamfree']);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }

        $reply->update($request->only('body'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted!']);
        }

        return back();
    }
}
