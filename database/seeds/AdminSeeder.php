<?php

use App\Admin;
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

        factory(Admin::class)->create([
            'username' => 'admin',
            'password' => bcrypt('password123'),
        ]);
    }
}
