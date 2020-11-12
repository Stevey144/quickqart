<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Category::class, function (Faker $faker) {
    $name = 'Category ' . rand(0, 17833478);
    return [
        $name => $name, 'slug' => Str::slug($name),
        'property' => json_encode([]), 'description' => $faker->paragraph, 'store' => 'jumia',
    ];
});
