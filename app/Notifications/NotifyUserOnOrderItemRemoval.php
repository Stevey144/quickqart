<?php

namespace App\Notifications;

use Gr8Shivam\SmsApi\Notifications\SmsApiChannel;
use Gr8Shivam\SmsApi\Notifications\SmsApiMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUserOnOrderItemRemoval extends Notification
{
    use Queueable;

    private $orderItem;

    /**
     * Create a new notification instance.
     *
     * @param $orderItem
     */
    public function __construct($orderItem)
    {
        $this->orderItem = $orderItem;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsApiChannel::class];
    }

    public function toSmsApi($notifiable)
    {
        return (new SmsApiMessage)
            ->content("QuickQart: {$this->orderItem->product->name} has been removed from the order with ID {$this->orderItem->order->slug}");
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("{$this->orderItem->product->name} has been removed from the order with ID {$this->orderItem->order->slug}")
                    ->line('Thank you!');
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
