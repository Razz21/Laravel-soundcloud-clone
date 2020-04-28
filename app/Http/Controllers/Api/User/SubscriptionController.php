<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
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
        return auth()->user()->subscribed()->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $target = User::findOrFail($request->input('user_id'));
        $this->authorize('subscribe', [User::class, $target]);
        $request->user()->subscribed()->toggle($target->id);

        return $request->user()->subscribed()->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return auth()->user()->subscribed()->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function subscribers()
    {
        return auth()->user()->subscribers()->get();
    }

}
