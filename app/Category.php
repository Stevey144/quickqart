<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends StoreBaseModel
{
    protected $fillable = ['name', 'parent_id', 'slug', 'property', 'description', 'store'];

    protected $appends = ['properties', 'products_count'];

    public function getPropertiesAttribute() {
        try {
        return json_decode($this->property);
        } catch (\Exception $e){}

        return [];
    }

    public function getProductsCountAttribute() {
        return $this->products()->count();
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id');
    }


}
