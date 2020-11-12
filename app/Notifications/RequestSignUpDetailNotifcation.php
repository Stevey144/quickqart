<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestSignUpDetailNotifcation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            ->subject('How to become a store owner on QuickQart.')
            ->line('Thank you for using our platform.')
            ->line('If your are a seller, or you own a business you wish to put online with no cost at all, this is you opportunity. First create an account on our platform by using the action button below.')
            ->action('Register an account', url('http://quickqart.com/register'))
            ->line('The registration requires a valid email address, a confirmation email will be sent to you after which when confirmed, you will follow a few simple steps to setup your store. You can thereafter manage your store, products, inventory and orders with our simplified tools.')
            ->line('Thank you!');
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
