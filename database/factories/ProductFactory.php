<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name',
        'category_id',
        'sku',
        'description',
        'slug',
        'price',
        'min_quantity',
        'multiple',
        'quantity',
        'unit',
        'properties',
        'images',
        'main_features'
    ];
});
