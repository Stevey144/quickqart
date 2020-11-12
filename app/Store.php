<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Store extends BaseModel
{
    protected $fillable = ['user_id', 'slug', 'name', 'description', 'social', 'contact',
        'address', 'logo', 'mobile_logo', 'banner', 'currency', 'total_products'];

    protected $appends = ['banners', 'url', 'total_visitors', 'daily_visitors', 'total_orders'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function visits()
    {
        return $this->hasMany(StoreVisitor::class, 'user_id');
    }

    public function getAddressAttribute()
    {
        try {
            return json_decode($this->attributes['address']);
        } catch (\Exception $e) {
        }

        return ['address' => 'NA', 'country' => '', 'state' => ''];
    }

    public function getDailyVisitorsAttribute()
    {
        return DB::table('store_visitors')->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')
            ->where('store', $this->slug)->count();
    }

    public function getTotalOrdersAttribute()
    {
        return Order::query()->where('store', $this->slug)->count();
    }

//    public function getTotalProductsAttribute()
//    {
//        return Product::query()->where('store', $this->slug)->count();
//    }

    public function getTotalVisitorsAttribute()
    {
        return DB::table('store_visitors')->where('store', $this->slug)->count();
    }

    public function getUrlAttribute()
    {
        $baseUrl = env("APP_BASE_URL");
        if ($baseUrl === 'localhost') {
            $baseUrl .= ":8000";
        }
        return "https://$this->slug.$baseUrl";
    }

    public function getContactAttribute()
    {
        try {
            return json_decode($this->attributes['contact']);
        } catch (\Exception $e) {
        }

        return ['phone_number' => '', 'email' => '', 'state' => ''];
    }

    public function getSocialAttribute()
    {
        try {
            $social = $this->attributes['social'];
            if ($social)
                return json_decode($social);
        } catch (\Exception $e) {
        }

        return ['facebook' => '', 'twitter' => '', 'instagram' => '', 'youtube' => ''];
    }

    public function getCurrencyAttribute()
    {
        try {
            $jsn = $this->attributes['currency'];
            if ($jsn)
                return json_decode($jsn);
        } catch (\Exception $e) {
        }

        return collect(['code' => '₦', 'symbol' => 'NGN', 'name' => 'Naira', 'rate' => 1]);
    }

    public function getBannersAttribute()
    {
        $banners = [];

        $specialOffers = SpecialOffer::query()->active()->get();

        foreach ($specialOffers as $offer) {
            foreach ($offer->banners as $banner) {
                $banners[] = [
                    'url' => "/special-offers/$offer->slug",
                    'banner' => $banners
                ];
            }
        }

        $banners[] = [
            'url' => '',
            'image' => $this->banner
        ];

        return $banners;
    }

    public function scopeWithDates($query)
    {
        if (request()->has('from') && ($from = request()->input('from'))) {
            $from = $this->convertDate($from);
            $fromDate = date('Y-m-d', strtotime($from)) . ' 00:00:00';
            $query = $query->where('created_at', '>=', $fromDate);
        }
        if (request()->has('to') && ($from = request()->input('to'))) {
            $from = $this->convertDate($from);
            $toDate = date('Y-m-d', strtotime($from)) . ' 23:59:59';
            $query = $query->where('created_at', '<=', $toDate);
        }

        return $query;
    }

    private function convertDate($date)
    {
        $chunks = explode(' ', $date);
        if (count($chunks) > 5) {
            $date = [
                $chunks[1],
                $chunks[2],
                $chunks[3]
            ];

            return implode(' ', $date);
        }

        return $date;
    }

}
