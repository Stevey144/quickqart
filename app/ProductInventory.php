<?php

namespace App;

class ProductInventory extends StoreBaseModel
{
    protected $fillable = [
        'product_id', 'quantity', 'type', 'store'
    ];

    public function scopeStocks($query) {
        return $query->where('type', 'stock');
    }

    public function scopeReturned($query) {
        return $query->where('type', 'restock');
    }

    public function scopeBought($query) {
        return $query->where('type', 'bought');
    }


}
