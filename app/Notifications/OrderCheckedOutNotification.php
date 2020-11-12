<?php

namespace App\Notifications;

use App\Order;
use Gr8Shivam\SmsApi\Notifications\SmsApiChannel;
use Gr8Shivam\SmsApi\Notifications\SmsApiMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCheckedOutNotification extends Notification
{
    use Queueable;
    /**
     * @var Order
     */
    private $order;
    private $store;
    private $isStoreOwner;

    /**
     * Create a new notification instance.
     *
     * @param $order
     */
    public function __construct($store, $order, $isStoreOwner = true)
    {
        $this->order = $order;
        $this->store = $store;
        $this->isStoreOwner = $isStoreOwner;
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
        $currency = '₦';
        if ($this->store->currency && isset($this->store->currency->code)) {
            $currency = $this->store->currency->code;
        }

        $order = $this->order;

        if ($this->isStoreOwner) {
            $message = "QuickQart: " . $order->contact['lastName'] . " has just placed an order for $order->item_count item(s) totalling $currency $order->amount_formatted";
        } else {
            $message = "QuickQart: Your order of {$this->order->item_count} item(s) totalling $currency {$this->order->amount_formatted} has just been received. You will get a call from us soon.";
        }
        return (new SmsApiMessage)
            ->content($message);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $currency = '₦';
        if ($this->store->currency && isset($this->store->currency->code)) {
            $currency = $this->store->currency->code;
        }

        if ($this->isStoreOwner) {
            return (new MailMessage)
                ->subject('Order #' . $this->order->slug . ' checkout notification')
                ->markdown('mails.order-checked-out', ['order' => $this->order, 'store' => $this->store, 'currency' => $currency]);
        } else {
            return (new MailMessage)
                ->subject('Order #' . $this->order->slug . ' notification')
                ->greeting("{$this->order->contact['firstName']},")
                ->line("Your order of {$this->order->item_count} item(s) totalling $currency {$this->order->amount_formatted} has just been received. You will get contacted soon for directives on how payment and delivery will be made to you.")
                ->action('View Order Details', "http://{$this->store->slug}.quickqart.com/track-order/{$this->order->slug}")
                ->line("Thank you for your patronage.");
        }
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
