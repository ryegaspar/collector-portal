<?php

use Faker\Generator as Faker;

$factory->define(App\Collector::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->unique()->safeEmail,
        'password' => bcrypt('password123')
    ];
});
