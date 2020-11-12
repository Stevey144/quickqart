<?php

namespace App\Events;

use App\Mail\GeneralMail;
use App\Role;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PasswordResetEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $link = url('auth/reset-password/' . $token);
        $name = $user->name;
        $email = $user->email;
        $data = [
            'name' => $name,
            'link' => $link,
            'date' => date('Y-m-d H:i')
        ];
        if ($email) {
            Mail::to($email)->queue(new GeneralMail('change-password', $data));
        }

        $users = User::query()->whereHas('roles', function ($query) {
            $query->where('name', Role::SYSADMIN);
        })->get();

        $ip = request()->ip();
        $message = "Password reset requested by {$name} from {$ip}. A Password reset <a href='{$link}'>link</a> was sent to: {$email} so the recipient can complete the request.";
        event(new LocalNotifyAdminEvent($message, $users));
    }

}
