<?php

use App\Models\Lynx\Site;
use App\Models\Lynx\Subsite;
use Illuminate\Database\Seeder;

class SubSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subsite::truncate();
        Site::truncate();

        factory(Subsite::class)->create([
            'name'        => 'US',
            'site_id'     => function () {
                return factory(Site::class)->create([
                    'name'        => 'US',
                    'description' => ''
                ]);
            },
            'has_team_leaders' => false,
            'description' => '',
        ]);

        factory(Subsite::class)->create([
            'name'        => 'Manila',
            'site_id'     => function () {
                return factory(Site::class)->create([
                    'name'        => 'Sargas',
                    'description' => ''
                ]);
            },
            'has_team_leaders' => true,
            'description' => '',
        ]);

        factory(Subsite::class)->create([
            'name'        => 'Pampanga',
            'site_id'     => 2,
            'has_team_leaders' => true,
            'description' => '',
        ]);

        factory(Subsite::class)->create([
            'name'        => 'EMAPTA',
            'site_id'     => function () {
                return factory(Site::class)->create([
                    'name'        => 'EMAPTA',
                    'description' => ''
                ]);
            },
            'has_team_leaders' => true,
            'description' => '',
        ]);
    }
}
