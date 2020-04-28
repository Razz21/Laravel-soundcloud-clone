<?php

namespace App\Policies;

use App\Like;
use App\Track;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LikePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any likes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the like.
     *
     * @param  \App\User  $user
     * @param  \App\Track  $track
     * @return mixed
     */
    public function view(User $user, $track)
    {
        //
    }

    /**
     * Determine whether the user can create likes.
     *
     * @param  \App\User  $user
     * @param  @likeable  $model
     * @return mixed
     */
    public function create(User $user, $model)
    {
        // TODO check, if $model is likeable
        return $user->id != $model->user_id;
    }

    /**
     * Determine whether the user can update the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function update(User $user, Like $like)
    {
        return $user->id === $like->user_id;
    }

    /**
     * Determine whether the user can delete the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function delete(User $user, Like $like)
    {
        //
    }

    /**
     * Determine whether the user can restore the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function restore(User $user, Like $like)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the like.
     *
     * @param  \App\User  $user
     * @param  \App\Like  $like
     * @return mixed
     */
    public function forceDelete(User $user, Like $like)
    {
        //
    }
}
