<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        // Utilizamos el objeto $faker para poder crear una oración de prueba como titulo.
        'title' => $faker->sentence
    ];
});
