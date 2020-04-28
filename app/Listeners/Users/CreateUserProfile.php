<?php

namespace App\Listeners\Users;

class CreateUserProfile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // $event->user->profile()->create([
        //     'name' => $event->user->username,
        //     'url' => Str::slug($event->user->username),
        // ]);
    }
}
