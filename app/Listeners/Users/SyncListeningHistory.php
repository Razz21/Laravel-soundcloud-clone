<?php

namespace App\Listeners\Users;

class SyncListeningHistory
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
        $history = session()->get('history');
        if ($history && is_array($history)) {
            foreach ($history as $track) {
                $event->user->history()->syncWithoutDetaching([$track => ['updated_at' => now()]]);
            }
        }
    }
}
