<?php

namespace App\Http\Controllers\Api;

use App\History;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackResource;
use App\Track;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TrackResource::collection(auth()->user()->history()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        auth()->user()->history()->syncWithoutDetaching([$request->track => ['updated_at' => now()]]);

        return response('History updated', 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validateRequest($request);

        auth()->user()->history()->detach($request->track);
        return response('Track removed from history', 204);
    }

    private function validateRequest($request)
    {
        return $request->validate(['track' => ['required', Rule::unique('history', 'track_id')->where(function ($query) {
            return $query->where('user_id', auth()->id());
        })]]);
    }
}
