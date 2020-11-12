<?php

namespace App;

use Illuminate\Support\Str;

class Product extends StoreBaseModel
{
    protected $fillable = ['name', 'category_id', 'sku', 'description', 'slug',
        'price', 'min_quantity', 'multiple', 'quantity', 'unit', 'properties', 'images', 'active',
        'search_count', 'checkout_count', 'main_features', 'old_price', 'brand', 'is_featured', 'variants', 'store'];

    protected $appends = [
        'cover',
        'excerpt',
        'features',
        'rating',
        'specifications',
        'review_count',
        'options'
    ];

    public function storeObj() {
        return $this->belongsTo(Store::class, 'store');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getRatingAttribute() {
        $count = $this->reviews()->count();
        if ($count) {
            return $this->reviews()->sum('rating') / $count;
        }

        return 0;
    }

    public function getOptionsAttribute() {
        return $this->variants;
    }

    public function getReviewCountAttribute() {
        return $this->reviews()->count();
    }

    public function getSpecificationsAttribute() {
        return $this->properties;
    }

    public function reviews() {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function inventory() {
        return $this->hasMany(ProductInventory::class, 'product_id')->latest();
    }

    public function scopeAvailable($query) {
        return $query->where(function ($q) {
           $q->where('quantity', '>', 0)
               ->where('active', true);
        });
    }

    public function scopeFeatured($query) {
        $query->where('is_featured', true);
    }

    public function getCoverAttribute() {
        if ($this->images && count($this->images)) {
            return $this->images[0];
        }

        return null;
    }

    public function getFeaturesAttribute() {
        $features = [];
        if (isset($this->attributes['main_features']) && ($mainFeatures = $this->attributes['main_features'])) {
            $features = explode("\n", $mainFeatures);
        }

        if (count($features)) {
            return array_map(function ($value) {
                $d = explode(":", $value, 2);
                $name = '';
                $value = '';
                switch (count($d) ) {
                    case 1:
                        $value = $d[0];
                        break;
                    case 2:
                        $name = $d[0];
                        $value = $d[1];
                        break;
                }

                return [
                    'name' => $name,
                    'values' => explode(',', $value),
                    'value' => $value,
                ];
            }, $features);
        }

        return $this->properties;
    }

    public function getExcerptAttribute() {
        return Str::limit(strip_tags($this->description), 150);
    }

    public function getPropertiesAttribute() {
        $items = $this->attributes['properties'];

        try{
            if ($items) {
                return json_decode($items);
            }
        } catch(\Exception $e){}

        return [];
    }

    public function getVariantsAttribute() {
        $items = $this->attributes['variants'];

        try{
            if ($items) {
                return json_decode($items);
            }
        } catch(\Exception $e){}

        return [];
    }

    public function getBrandAttribute() {
        $brand = $this->attributes['brand'];

        try{
            return json_decode($brand);
        } catch(\Exception $e){}

        return ['name' => '', 'country' => '', 'logo' => ''];
    }

    public function getImagesAttribute() {
        $items = $this->attributes['images'];

        try{
            $items = str_replace("http://", "https://", $items);
            return json_decode($items);
        } catch(\Exception $e){}

        return [];
    }


}
