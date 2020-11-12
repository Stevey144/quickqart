<?php

namespace App\Notifications;

use Gr8Shivam\SmsApi\Notifications\SmsApiChannel;
use Gr8Shivam\SmsApi\Notifications\SmsApiMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspensionNotification extends Notification
{
    use Queueable;

    private $user;
    private $reason;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $reason
     */
    public function __construct($user, $reason)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsApiChannel::class];
    }

    public function toSmsApi($notifiable)
    {
        $role = $this->user->role_names->join(', ');
        return (new SmsApiMessage)
            ->content("QuickQart: Your account as $role has been suspended because: $this->reason");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $role = $this->user->role_names->join(', ');
        return (new MailMessage)
            ->line("Your account as $role has been suspended. The reason is given below")
            ->line($this->reason)
            ->line('You can send a reply or complaint to complaints@quickqart.com for guidance and assistance.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
