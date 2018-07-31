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

        factory(User::class)->create([
            'username'     => 'admin',
            'password'     => bcrypt('Password'),
            'access_level' => 1
        ]);

        factory(User::class)->create([
            'username' => 'admin2',
            'password' => bcrypt('Password'),
            'access_level' => 2
        ]);
    }
}
