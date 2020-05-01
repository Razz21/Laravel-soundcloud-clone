<?php

namespace App\Http\Controllers\Api\User;

use App\History;
use App\Http\Controllers\Controller;
use App\Http\Resources\TrackResource;

class HistoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return TrackResource::collection($user->history()->paginate(5));
    }
}
