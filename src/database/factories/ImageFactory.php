<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'post_id' => function() {
            return factory(\App\User::class)->create()->id;
        },
        'url' => $faker->imageUrl()
    ];
});
