<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->only('update', 'destroy');
    }
    /**
     * Display a request user.
     *
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $user = User::where('url', $url)->firstOrFail();
        return new UserProfileResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->hasFile('image')) {
            // allow only one image
            auth()->user()->clearMediaCollection('avatars');
            $media = auth()->user()->addMediaFromRequest('image')->toMediaCollection('avatars');
            unlink($media->getPath()); // delete original after conversions
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response('Your account has beed deleted successfully!', 204);
    }

}
