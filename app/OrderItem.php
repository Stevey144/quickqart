<?php

namespace App;

class OrderItem extends StoreBaseModel
{
    protected $fillable = [
        'slug', 'order_id', 'product_id', 'options', 'quantity', 'unit_price', 'amount', 'store', 'options'
    ];

    protected $appends = ['price'];

    public function getPriceAttribute() {
        return $this->unit_price;
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function scopeOwned($query)
    {
        if (auth()->check()) {
            $storeSlug = request()->header('PREF-STORE');
            $slug = $storeSlug ? $storeSlug : '';
            return $query->where('store', $slug);
        }
        return $query;
    }

    public function getOptionsAttribute() {
        $items = $this->attributes['options'];

        try{
            if ($items) {
                return json_decode($items);
            }
        } catch(\Exception $e){}

        return [];
    }
}
