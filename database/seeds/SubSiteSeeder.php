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
            'name'                            => 'US',
            'site_id'                         => function () {
                return factory(Site::class)->create([
                    'name'        => 'US',
                    'description' => ''
                ]);
            },
            'has_team_leaders'                => false,
            'description'                     => '',
            'min_desk_number'                 => 40,
            'max_desk_number'                 => 199,
            'collectone_id_assignment_method' => 1,
            'prefixes'                        => null,
        ]);

        factory(Subsite::class)->create([
            'name'                            => 'Manila',
            'site_id'                         => function () {
                return factory(Site::class)->create([
                    'name'        => 'Sargas',
                    'description' => ''
                ]);
            },
            'has_team_leaders'                => true,
            'description'                     => '',
            'min_desk_number'                 => 600,
            'max_desk_number'                 => 799,
            'collectone_id_assignment_method' => 2,
            'prefixes'                        => "m,n",
        ]);

        factory(Subsite::class)->create([
            'name'                            => 'Pampanga',
            'site_id'                         => 2,
            'has_team_leaders'                => true,
            'description'                     => '',
            'min_desk_number'                 => 200,
            'max_desk_number'                 => 399,
            'collectone_id_assignment_method' => 2,
            'prefixes'                        => "p,h",
        ]);

        factory(Subsite::class)->create([
            'name'                            => 'EMAPTA',
            'site_id'                         => function () {
                return factory(Site::class)->create([
                    'name'        => 'EMAPTA',
                    'description' => ''
                ]);
            },
            'has_team_leaders'                => true,
            'description'                     => '',
            'min_desk_number'                 => 400,
            'max_desk_number'                 => 599,
            'collectone_id_assignment_method' => 2,
            'prefixes'                        => "e",
        ]);
    }
}
