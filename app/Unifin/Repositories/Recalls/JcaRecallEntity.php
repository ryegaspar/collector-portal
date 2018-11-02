<?php

namespace App\Unifin\Repositories\Recalls;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JcaRecallEntity implements JcaRecallInterface
{
    protected $record;
    protected $report;

    protected $recordFormat = [
        'BFrameId'         => [0, 10],
        'RemoteId'         => [10, 4],
        'PlacementDate'    => [14, 8],
        'Portfolio'        => [22, 12],
        'RecordType'       => [34, 2],
        'CreditGrantorNum' => [36, 24],
        'ServiceId'        => [60, 3],
        'DateCreated'      => [63, 8],
        'Name'             => [71, 30],
        'AccountBalance'   => [101, 10, 'number'],
        'Reason'           => [111, 6]
    ];

    public function generateReport()
    {
        request()->file('file_input')->storeAs('public\files\recalls',
            request()->file('file_input')->getClientOriginalName());

        $filePath = public_path('storage\\files\\recalls\\' . request()->file('file_input')->getClientOriginalName());

        $file = new \SplFileObject($filePath, "r");

        while (! $file->eof()) {
            $line = $file->fgets();

            $this->getRecord($line);
        }

        $this->appendInfo();

        $columns = collect($this->recordFormat)
            ->keys()
            ->concat(['DbrNo'])
            ->concat(['DbrStatus'])
            ->concat(['CountPdc'])
            ->concat(['XcrCode']);

        return [$this->record, $columns];
    }

    protected function getRecord($item)
    {
        foreach ($this->recordFormat as $recordKey => $recordItem) {
            $account[$recordKey] = $this->formatToNumber(trim(substr($item, $recordItem[0], $recordItem[1])),
                $recordItem);
        }

        $this->record[] = $account;
    }

    protected function appendInfo()
    {
        array_shift($this->record);

        $this->record = collect($this->record)->map(function ($item) {
            $account = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->select(DB::raw("DBR_NO, DBR_STATUS, (SELECT COUNT(*) FROM CDSMSC.CHK WHERE DBR.DBR_NO = CHK.CHK_DBR_NO) as count_pdc, DBR_NO+'01XCR' as XCR_CODE"))
                ->whereRaw('DBR_CLIENT LIKE ?', ["%" . request()->client . "%"])
                ->where('DBR_CLI_REF_NO', $item)
                ->first();

            if (! $account) {
                $account = new \stdClass();
                $account->DBR_NO = 'NOT FOUND';
                $account->DBR_STATUS = 'NOT FOUND';
                $account->count_pdc = 'NOT FOUND';
                $account->XCR_CODE = 'NOT FOUND';
            }

            $item['DbrNo'] = $account->DBR_NO;
            $item['DbrStatus'] = $account->DBR_STATUS;
            $item['CountPdc'] = $account->count_pdc;
            $item['XcrCode'] = $account->XCR_CODE;

            return $item;
        });
    }

    private function formatToNumber($value, $record)
    {
        if (isset($record[2]) && $record[2] == 'number') {
            return floatval($value);
        }

        return $value;
    }
}