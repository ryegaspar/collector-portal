<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Lynx\LetterRequestType::class, function (Faker $faker) {
    return [
        'name'        => $faker->colorName,
        'description' => $faker->sentence,
    ];
});
