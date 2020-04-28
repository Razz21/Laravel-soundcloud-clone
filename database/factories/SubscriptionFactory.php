<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subscription;
use App\User;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'target_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
