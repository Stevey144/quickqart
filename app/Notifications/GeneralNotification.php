<?php

namespace App\Notifications;

use Gr8Shivam\SmsApi\Notifications\SmsApiChannel;
use Gr8Shivam\SmsApi\Notifications\SmsApiMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;
    private $message;
    private $url;
    private $subject;

    /**
     * Create a new notification instance.
     *
     * @param $message
     * @param string $url
     * @param string $subject
     */
    public function __construct($message, $url = '', $subject = 'Notification')
    {
        $this->message = $message;
        $this->url = $url;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toSmsApi($notifiable)
    {
        return (new SmsApiMessage)
            ->content($this->message);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $not = (new MailMessage)
            ->subject($this->subject)
                    ->line($this->message);

        if ($this->url) {
            $not->action('Click here', $this->url);
        }

        return $not;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
