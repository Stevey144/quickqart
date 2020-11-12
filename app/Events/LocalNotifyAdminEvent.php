<?php

namespace App\Events;

use App\Notifications\GeneralNotification;
use App\Role;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocalNotifyAdminEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param string $message
     * @param bool $users
     */
    public function __construct(string $message, $users = false)
    {
        if (!$users) {
            $users = User::query()->whereHas('roles', function ($query) {
                $query->where('name', Role::SYSADMIN);
            })->get();
        }

        foreach ($users as $user) {
            $user->localNotify($message);
            try {
                $user->notify(new GeneralNotification($message));
            } catch (\Exception $x) {
            }
        }
    }
}
