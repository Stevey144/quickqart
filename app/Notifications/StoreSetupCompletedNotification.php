<?php

namespace App\Notifications;

use Gr8Shivam\SmsApi\Notifications\SmsApiChannel;
use Gr8Shivam\SmsApi\Notifications\SmsApiMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreSetupCompletedNotification extends Notification
{
    use Queueable;

    private $store;
    private $toOwner;

    /**
     * Create a new notification instance.
     *
     * @param $store
     * @param $toOwner
     */
    public function __construct($store, $toOwner)
    {
        $this->store = $store;
        $this->toOwner = $toOwner;
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
        if ($this->toOwner) {
            return (new SmsApiMessage)
                ->content("QuickQart: Your store setup has been completed. Your store URL is {$this->store->url}.");
        }

        return null;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->toOwner) {
            return (new MailMessage)
                ->markdown('mails.store-setup-completed', ['store' => $this->store]);
        } else {
            return (new MailMessage)
                ->line("A new store has been created successfully by {$this->store->user->name}. Checkout the store by using this link.")
                ->action('Visit Store', url($this->store->url));
        }
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
