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
            'username' => 'dalia',
            'password' => bcrypt('Password1'),
            'desk' => '042'
        ]);

        factory(User::class)->create([
            'username' => 'kenny',
            'password' => bcrypt('Password1'),
            'desk' => '118'
        ]);
    }
}
