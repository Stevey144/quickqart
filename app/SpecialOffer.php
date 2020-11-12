<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SpecialOffer extends StoreBaseModel
{
    protected $fillable = ['title', 'banners', 'description', 'store', 'slug', 'start', 'end', 'discount', 'threshold'];

    protected $appends = ['banner', 'time'];

    public function scopeActive($query) {
        return $query->where('end', '>', now());
    }

    public function offerProducts() {
        return $this->hasMany(SpecialOfferProduct::class, 'special_offer_id');
    }

    public function getBannersAttribute() {
        $banner =  $this->attributes['banners'];
        try{
            return (array) json_decode($banner);
        } catch (\Exception $exception){}

        return [];
    }

    public function getBannerAttribute() {
        if (count($this->banners)) {
            return $this->banners[0];
        }

        return '';
    }

    public function getTimeAttribute() {
        $format = 'Y-m-d H:i:s';
        $start = Carbon::createFromFormat($format, date($format, strtotime($this->start)));
        $end = Carbon::createFromFormat($format, date($format, strtotime($this->end)));

        return $start->diffInSeconds($end);
    }


}
