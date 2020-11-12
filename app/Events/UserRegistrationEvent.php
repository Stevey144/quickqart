<?php

namespace App\Events;

use App\Mail\GeneralMail;
use App\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UserRegistrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $token
     * @param bool $isNew
     */
    public function __construct($user, $isNew = true)
    {
        event(new Registered($user));
        if (!$isNew) {
            return;
        }

        $role = $user->role_names->join(', ');

        $message = "A new user $user->name with email: $user->email has been assigned a role $role";
        event(new LocalNotifyAdminEvent($message));
    }
}
