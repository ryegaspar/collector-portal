<?php

use App\Models\Lynx\Site;
use App\Models\Lynx\Subsite;
use Faker\Generator as Faker;

$factory->define(Subsite::class, function (Faker $faker) {
    return [
        'name'             => $faker->name,
        'site_id'          => function () {
            return factory(Site::class)->create()->id;
        },
        'has_team_leaders' => $faker->boolean,
        'description'      => $faker->paragraph,

    ];
});
