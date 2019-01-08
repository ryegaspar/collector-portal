<?php

namespace App\Unifin\Classes;

use App\Models\Lynx\Collector;
use App\Models\Lynx\Subsite;
use Carbon\Carbon;

class NewCollectorFromBatch
{
    protected $file;
    protected $batchData;
    protected $collectorBatch;

    public function __construct($file, $batchData, $collectorBatch)
    {
        $this->file = $file;
        $this->batchData = $batchData;
        $this->collectorBatch = $collectorBatch;
    }

    /**
     * Make collectors from batch file.
     *
     */
    public function makeCollectors()
    {
        $fileHandle = $this->loadFile();

        $row = 0;
        while (($data = fgetcsv($fileHandle)) !== false) {
            $row++;
            if ($row == 1) {
                continue;
            }

            $newCollector = $this->makeCollectorFromBatch($data);

            $this->collectorBatch->collectors()->save($newCollector);
        }
    }

    /**
     * Open uploaded csv file.
     *
     * @return bool|resource
     */
    protected function loadFile()
    {
        $this->file->storeAs('public\files', $this->batchData['name'] . ".csv");

        $filePath = public_path('storage\\files\\' . $this->batchData['name'] . ".csv");

        return fopen($filePath, "r");
    }

    /**
     * Create a new collector from file.
     *
     * @param $dataFromFile
     * @return Collector
     */
    protected function makeCollectorFromBatch($dataFromFile)
    {
        $startDate = Carbon::parse($this->batchData['start_date']);
        $lastName = $dataFromFile[0];
        $firstName = $dataFromFile[1];

        $ids = (new NewCollector($this->batchData['sub_site_id'], $firstName, $lastName))
            ->generateId();

        $tempDate = Carbon::parse($this->batchData['start_date'])->day(1);
        $fifteenth = Carbon::parse($this->batchData['start_date'])->day(15);
        $this->batchData['start_full_month_date'] = $startDate <= $fifteenth ? $tempDate : $tempDate->addMonth();

        $group = Subsite::find($this->batchData['sub_site_id'])->default_collector_group;

        return new Collector([
            'desk'                    => $ids[0],
            'tiger_user_id'           => $ids[1],
            'username'                => $ids[2],
            'last_name'               => $lastName,
            'first_name'              => $firstName,
            'sub_site_id'             => $this->batchData['sub_site_id'],
            'group'                   => $group,
            'team_leader_id'          => $this->batchData['team_leader_id'] ?? '',
            'commission_structure_id' => $this->batchData['commission_structure_id'],
            'start_date'              => $this->batchData['start_date'],
            'start_full_month_date'   => $this->batchData['start_full_month_date']
        ]);
    }


}