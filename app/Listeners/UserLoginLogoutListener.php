<?php

namespace App\Listeners;

use App\Events\UserLoginLogout;
use App\Models\UserAccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoginLogoutListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoginLogout $event): void
    {
        $user = $event->user;

        UserAccess::create([
            'user_id' => $user->id,
            'activity' => $event->activity
        ]);
    }
}
