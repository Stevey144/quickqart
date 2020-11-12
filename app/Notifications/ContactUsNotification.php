<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactUsNotification extends Notification
{
    use Queueable;
    private $subject;
    private $full_name;
    private $message;
    private $email;

    /**
     * Create a new notification instance.
     *
     * @param $email
     * @param $full_name
     * @param $subject
     * @param $message
     */
    public function __construct($email, $full_name, $subject, $message)
    {
        //
        $this->subject = $subject;
        $this->full_name = $full_name;
        $this->message = $message;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->replyTo($this->email, $this->full_name)
            ->line($this->message)
            ->line("")
            ->line("Sent by: $this->full_name ($this->email)");
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
