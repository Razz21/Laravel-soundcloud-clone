<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Track;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $users = User::all()->pluck('id')->toArray();
    $tracks = Track::all();
    $track = $tracks->shuffle()->first();
    $comments = $track->comments()->get();
    $reply = $faker->boolean; // create randomly reply or new comment
    if (!$comments->count() || !$reply) {
        $parent_id = null;
        $created_time = $faker->dateTimeBetween($track->created_at, 'now');
    } else {
        $comment = $comments->shuffle()->first();
        $parent_id = $comment->id;
        $created_time = $faker->dateTimeBetween($comment->created_at, 'now');

    }
    return [
        'user_id' => $faker->randomElement($users),
        'track_id' => $track->id,
        'parent_id' => $parent_id,
        'body' => $faker->paragraph,
        'created_at' => $created_time,
    ];
});
