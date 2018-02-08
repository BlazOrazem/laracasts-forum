<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Inspections\Spam;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Fetch all relevant replies.
     *
     * @param integer $channelId
     * @param Thread  $thread
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Persist a new reply.
     *
     * @param integer $channelId
     * @param Thread  $thread
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validateReply();

        $reply = $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validateReply();

        $reply->update(request(['body']));
    }

    /**
     * Delete the given reply.
     *
     * @param Reply $reply
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    /**
     * Validate the incoming reply.
     */
    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
