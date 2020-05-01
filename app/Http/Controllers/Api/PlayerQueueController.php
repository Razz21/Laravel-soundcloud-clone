<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QueueResource;
use App\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlayerQueueController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->check()) {
            $queue = Cache::get("player-queue_" . auth()->id());
        } else {
            $queue = session()->get('player-queue');
        }
        if ($queue) {
            $tracks = [];
            $currentIndex = $queue->currentIndex ?? 0;
            if (is_array($queue['tracks'])) {
                $tracks = QueueResource::collection(Track::find($queue['tracks']));
            }
            return response()->json(compact('currentIndex', 'tracks'));
        }
        return response()->json(null);
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
            'tracks' => 'present|array',
            'tracks.*' => 'integer|min:0',
            'currentIndex' => 'required|numeric|between:0,' . (count($request->tracks) - 1),
        ]);

        $currentIndex = $request->currentIndex ?? 0;
        $data = ['tracks' => $request->tracks, 'currentIndex' => $currentIndex];

        if (auth()->check()) {
            $cacheKey = "player-queue_" . auth()->id();
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
            }
            Cache::put($cacheKey, $data);
        } else {
            session()->put('player-queue', $data);
        }

        $tracks = QueueResource::collection(Track::find($request->tracks));
        return response()->json(compact('currentIndex', 'tracks'));
    }
}
