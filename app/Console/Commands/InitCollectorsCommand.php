<?php

namespace App\Console\Commands;

use App\Models\Lynx\Collector;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitCollectorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unifin:initCollectors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'populate the collectors table from a csv file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csvFile = public_path('storage\\files\\cdsusr.csv');

        $fileHandle = fopen($csvFile, "r");

        DB::transaction(function () use ($fileHandle) {
            while (($data = fgetcsv($fileHandle)) !== false) {
                if ($data[17] == 'Y') {
                    $nameArr = $this->split_name($data[1]);
                    Collector::create([
                        'tiger_user_id'  => $data[0],
                        'desk'           => $data[16],
                        'username'       => $nameArr[2],
                        'last_name'      => $nameArr[1],
                        'first_name'     => $nameArr[0],
                        'team_leader_id' => 4,
                        'manager_id'     => 3,
                    ]);
                }
            }
        });
    }

    /**
     * Split the name into first name, last name
     *
     * @param $name
     * @return array
     */
    protected function split_name($name)
    {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));
        $user_name = strtolower($first_name[0] . str_replace(" ", "", $last_name));

        return array($first_name, $last_name, $user_name);
    }
}
