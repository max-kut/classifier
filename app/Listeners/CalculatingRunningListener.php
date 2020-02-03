<?php

namespace App\Listeners;

use App\Events\CalculatingRunning;
use App\Jobs\CalculatingJob;
use App\Models\Fields\User\CalculatingStatus;

class CalculatingRunningListener
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
    public function handle(CalculatingRunning $event)
    {
        $event->user->calculating_status = CalculatingStatus::PENDING();
        $event->user->save();

        CalculatingJob::dispatch($event->user);
    }
}
