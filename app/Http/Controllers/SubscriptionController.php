<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        $request->user()->subscribed()->sync([
            $user->id,
        ]);
        return $request->user()->subscribed();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $subscription = $request->user()->subscribed()->findOrFail($id);
        $subscription->delete();
        return response('', 204);
    }
}
