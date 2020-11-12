<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialOfferProduct extends Model
{
    protected $fillable = ['special_offer_id', 'special_offer_slug', 'product_id', 'discount', 'threshold', 'store'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
