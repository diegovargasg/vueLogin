<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\File::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => $faker->regexify('[a-z]{3}'),
        'size' => $faker->randomNumber(),
        'user_id' => 1
    ];
});
