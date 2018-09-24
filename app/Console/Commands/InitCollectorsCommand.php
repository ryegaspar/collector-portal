<?php

namespace App\Console\Commands;

use App\Models\Lynx\Collector;
use Carbon\Carbon;
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
        $csvFile = public_path('storage\\files\\roster.csv');

        $fileHandle = fopen($csvFile, "r");

        $dispatcher = Collector::getEventDispatcher();
        Collector::unsetEventDispatcher();

        DB::transaction(function () use ($fileHandle) {
            $row = 0;
            while (($data = fgetcsv($fileHandle)) !== false) {
                $row++;
                if ($row == 1) {
                    continue;
                }

                $startDate = Carbon::parse($data[2]);
                $tempDate = Carbon::parse($data[2])->day(1);
                $fifteenth = Carbon::parse($data[2])->day(15);
                $start_full_month_date = Carbon::parse($startDate) <= $fifteenth ? $tempDate : $tempDate->addMonth();

                $lname = $data[0];
                $fname = $data[1];
                $username = $this->makeUsername($fname, $lname);

                $collector = Collector::create([
                    'last_name'               => $lname,
                    'first_name'              => $fname,
                    'username'                => $username,
                    'start_date'              => $startDate,
                    'start_full_month_date'   => $start_full_month_date,
                    'desk'                    => str_pad($data[3], 3, '0', STR_PAD_LEFT),
                    'tiger_user_id'           => strtolower($data[4]),
                    'sub_site_id'             => $data[5],
                    'commission_structure_id' => $data[6],
                    'team_leader_id'          => $data[7] ?? null,
                ]);
            }
        });

        Collector::setEventDispatcher($dispatcher);

        $this->info("Collectors have been synced.");
    }

    /**
     * Generate a username
     *
     * @param $fname
     * @param $lname
     * @return array
     */
    protected function makeUsername($fname, $lname)
    {
        $username = $maybe_username = strtolower($fname[0] . str_replace(" ", "", $lname));
        $next = 2;

        while (Collector::where('username', '=', $username)->first()) {
            $username = "{$maybe_username}.$next}";
            $next++;
        }

        return $username;
    }
}
