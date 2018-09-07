<?php

use App\Models\Lynx\Site;
use App\Models\Lynx\Subsite;
use Faker\Generator as Faker;

$factory->define(Subsite::class, function (Faker $faker) {
    return [
        'name'                            => $faker->name,
        'site_id'                         => function () {
            return factory(Site::class)->create()->id;
        },
        'has_team_leaders'                => $faker->boolean,
        'description'                     => $faker->paragraph,
        'min_desk_number'                 => $faker->numberBetween(1, 200),
        'max_desk_number'                 => $faker->numberBetween(201, 800),
        'collectone_id_assignment_method' => $faker->numberBetween(1, 2),
        'prefixes'                        => $faker->randomLetter
    ];
});
