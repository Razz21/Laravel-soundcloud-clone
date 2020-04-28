<?php

namespace App\Helpers;

use App\Like;
use App\User;

trait Likeable
{
    /**
     * Return active likes owners.
     *
     */
    public function likers()
    {
        return $this->morphToMany(User::class, 'likeable')->whereDeletedAt(null);
    }

    /**
     * Return active likes.
     *
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getIsLikedAttribute()
    {
        $like = null;
        if (auth()->check()) {
            $like = $this->likes()->whereUserId(auth()->user()->id)->first();
        }
        return (bool) $like;
    }
}
