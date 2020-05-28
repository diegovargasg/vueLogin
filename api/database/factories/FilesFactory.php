<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\File::class, function (Faker $faker) {

    //Fake according file size
    $bytes = $faker->randomNumber(7);
    if ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } else {
        $bytes = $bytes . ' bytes';
    }

    $file  = $faker->image(storage_path('app'), 300, 300, 'cats', false);

    return [
        'name' => $faker->word,
        'type' => $faker->fileExtension,
        'size' => $bytes,
        'generated_name' => $file,
        'user_id' => $faker->numberBetween(1, 2)
    ];
});
