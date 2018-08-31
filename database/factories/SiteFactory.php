<?php

use App\Models\Lynx\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->paragraph
    ];
});