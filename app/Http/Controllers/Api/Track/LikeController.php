<?php

namespace App\Http\Controllers\Api\Track;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrackResource;
use App\Like;
use App\Track;
use Illuminate\Http\Request;

class LikeController extends Controller
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
        return $track->likers()->get(); // return list of users
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Track $track)
    {
        // TODO CRUD routes
        $this->authorize('create', [Like::class, $track]);
        // check, if liked before
        $like = $track->likes()->withTrashed()->whereUserId(auth()->user()->id)->first();
        if ($like) {
            // toggle
            $this->authorize('update', $like);
            if ($like->trashed()) {
                $like->restore();
            } else {
                $like->delete();
            }
        } else {
            // create new like
            $like = new Like;
            $like->user()->associate(auth()->user());
            $track->likes()->save($like);
        }
        return TrackResource::make($track)->only(['id', 'isLiked', 'likes']);
    }
}
