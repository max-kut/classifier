<?php

namespace App\Listeners;

use App\Events\CalculatingCompleted;
use App\Models\Fields\User\CalculatingStatus;

class CalculatingCompletedListener
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
    public function handle(CalculatingCompleted $event)
    {
        $event->user->calculating_status = $event->user->phrases()->has('proposed')->exists()
            ? CalculatingStatus::HAS_PREDICTED()
            : null;

        $event->user->save();
    }
}
