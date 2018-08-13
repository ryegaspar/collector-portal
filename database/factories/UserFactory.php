<?php

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'last_name'      => $faker->lastName,
        'first_name'     => $faker->firstName,
        'email'          => $faker->email,
        'username'       => $faker->unique()->userName,
        'password'       => bcrypt('Password1'),
        'remember_token' => str_random(10),
    ];
});

$factory->state(User::class, 'admin', function ($faker) {
    return [
        'access_level' => 2,
    ];
});

$factory->state(User::class, 'superadmin', function ($faker) {
    return [
        'access_level' => 1,
    ];
});
