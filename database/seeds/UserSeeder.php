<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $user = factory(User::class)->create([
            'username'     => 'admin',
            'password'     => bcrypt('Password'),
        ]);

        $user->syncRoles('super-admin');

        factory(User::class)->create([
            'username' => 'admin2',
            'password' => bcrypt('Password'),
        ]);
    }
}
