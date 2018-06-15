<?php

use App\Collector;
use Illuminate\Database\Seeder;

class CollectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collector::truncate();

        factory(Collector::class)->create([
            'username' => 'admin',
            'password' => bcrypt('password123')
        ]);
    }
}
