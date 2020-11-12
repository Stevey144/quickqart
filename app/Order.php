<?php

namespace App;

class Order extends StoreBaseModel
{
    protected $fillable = ['slug', 'contact', 'status', 'store'];

    protected $appends = ['amount', 'item_count', 'total', 'status_color', 'status_description', 'unique_link',
        'can_cancel', 'can_pay', 'can_be_delivered', 'requires_action'];

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getRequiresActionAttribute() {
        return !in_array(strtolower($this->status), ['cancelled', 'delivered']);
    }

    public function getAmountAttribute() {
        return $this->orderItems()->sum('amount');
    }

    public function getCanCancelAttribute() {
        return !in_array(strtolower($this->status), ['cancelled', 'delivered']);
    }

    public function getCanBeDeliveredAttribute() {
        return !in_array(strtolower($this->status), ['cancelled', 'delivered']);
    }

    public function getCanPayAttribute() {
        return !in_array(strtolower($this->status), ['cancelled', 'paid', 'delivered']);
    }

    public function getUniqueLinkAttribute() {
        return "https://{$this->store}.quickqart.com/track-order/{$this->slug}";
    }

    public function getTotalAttribute() {
        return $this->amount;
    }

    public function getAmountFormattedAttribute() {
        return number_format($this->amount, 2);
    }

    public function getItemCountAttribute() {
        return $this->orderItems()->count();
    }

    public function getContactAttribute() {
        $contact = $this->attributes['contact'];
        try {
            return (array) json_decode($contact);
        } catch(\Exception $ex) {}
        return [
            'firstName' => 'Anonymous',
            'lastName' => '',
            'email' => '',
            'phone' => '',
        ];
    }

    public function getStatusDescriptionAttribute() {
        switch (strtolower($this->status)) {
            case 'checkout':
                return 'Payment Pending';
            case 'payment_confirmed':
            case 'paid':
                return 'Pending Delivery';
            case 'delivery_confirmed':
            case 'delivered':
                return 'Delivered';
            case 'cancelled':
                return 'Order Cancelled';

        }

        return '';
    }

    public function getStatusColorAttribute() {
        switch (strtolower($this->status)) {
            case 'checkout':
                return 'warning';
            case 'payment_confirmed':
            case 'paid':
                return 'primary';
            case 'delivery_confirmed':
            case 'delivered':
                return 'success';
            case 'cancelled':
                return 'danger';

        }

        return '';
    }

    public function scopeOwned($query)
    {
        if (auth()->check()) {
            $storeSlug = request()->header('pref_store');
            $slug = $storeSlug ? $storeSlug : '';
            return $query->where('store', $slug);
        }
        return $query;
    }
}
