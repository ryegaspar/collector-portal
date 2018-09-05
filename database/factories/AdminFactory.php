<?php

use App\Models\Lynx\Admin;
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

$factory->define(Admin::class, function (Faker $faker) {

    $firstName = $faker->firstName;
    $lastName = $faker->lastName;
    $tigerId = strtolower($firstName[0] . $lastName[0] . $faker->numberBetween(1, 9));

    return [
        'last_name'      => $lastName,
        'first_name'     => $firstName,
        'email'          => $faker->email,
        'username'       => $faker->unique()->userName,
        'tiger_user_id'  => $tigerId,
        'password'       => bcrypt('Password1'),
        'remember_token' => str_random(10),
    ];
});
