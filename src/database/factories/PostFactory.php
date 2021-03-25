<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'id' => $faker->randomNumber(),
        'title' => $faker->sentence,
        'content' => $faker->paragraph,
        'owner' => function() {
            return factory(\App\User::class)->create()->id;
        }
    ];
});
