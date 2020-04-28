<?php

namespace App\Http\Controllers\Api\Track;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Track;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Track $track)
    {
        return CommentResource::collection($track->comments()->with('replies')->latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Track $track)
    {
        $this->validate($request, [
            'body' => 'required|string',
            'parent_id' => ['integer', Rule::exists('comments', 'id')->where(function ($query) use ($track) {
                return $query->where('track_id', $track->id)->whereNull('parent_id');
            })],
        ]);
        // return response('', 204);
        $comment = new Comment;
        $comment->body = $request->body;
        if ($request->parent_id) {
            $comment->parent_id = $request->parent_id;
        }
        $comment->user()->associate(auth()->user());
        $track->comments()->save($comment);
        return new CommentResource($comment->load('replies'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track, Comment $comment)
    {
        return new CommentResource($comment->load('replies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Track $track, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Track $track, Comment $comment)
    {
        $comment->delete();
        return response('', 204);
    }
}
