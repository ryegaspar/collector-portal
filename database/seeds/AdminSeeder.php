<?php

use App\Models\Lynx\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        $user = factory(Admin::class)->create([
            'username'     => 'admin',
            'password'     => bcrypt('Password'),
        ]);

        $user->syncRoles('super-admin');

        factory(Admin::class)->create([
            'username' => 'admin2',
            'password' => bcrypt('Password'),
        ]);

        $manager = factory(Admin::class)->create([
            'username' => 'manager',
            'password' => bcrypt('Password')
        ]);

        $manager->syncRoles('manager');

        $site_manager = factory(Admin::class)->create([
            'username' => 'sitemanager',
            'password' => bcrypt('Password')
        ]);

        $site_manager->syncRoles('site-manager');

        $sub_site_manager = factory(Admin::class)->create([
            'username' => 'subsitemanager',
            'password' => bcrypt('Password')
        ]);

        $sub_site_manager->syncRoles('sub-site-manager');


        $teamleader1 = factory(Admin::class)->create([
            'username' => 'teamleader',
            'password' => bcrypt('Password'),
        ]);

        $teamleader1->syncRoles('team-leader');

        $teamleader2 = factory(Admin::class)->create([
            'username' => 'teamleader2',
            'password' => bcrypt('Password'),
        ]);

        $teamleader2->syncRoles('team-leader');
    }
}
