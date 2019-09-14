<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Remazing_cc\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $max_product_price = env('MAXIMUM_PRODUCT_PRICE');
    $first_listed_price = env('FIRST_LISTED_DATE');
    $last_listed_price = env('LAST_LISTED_DATE');
    return [
        'title' => $faker->userName,
        'price' => $faker->randomFloat(2, 0, $max_product_price),
        'reviews_count' => $faker->randomNumber,
        'avr_rating' => $faker->randomFloat(1, 0, 5),
        'first_listed' => $faker->dateTimeBetween($first_listed_price, $last_listed_price),
    ];
});
