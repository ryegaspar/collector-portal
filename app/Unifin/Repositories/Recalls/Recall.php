<?php

namespace App\Unifin\Repositories\Recalls;

use App\Unifin\Classes\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Recall implements RecallInterface
{
    protected $columns = [
        'DBR_CLI_REF_NO',
        'ADR_NAME',
        'DBR_NO',
        'DBR_NAME1',
        'DBR_ASSIGN_DATE_O',
        'DBR_CLOSE_DATE_O',
        'DBR_ASSIGN_AMT',
        'DBR_RECVD_TOT',
        'STS_DESC',
        'DBR_COM_RATE',
        'DBR_CLIENT',
        'DBR_LAST_WORKED_O',
        'DBR_STATUS',
        'count_pdc',
        'XCR_CODE'
    ];

    public function makeRecallByAssignedDate($client, $assignedDate)
    {
        $accounts = DB::connection('sqlsrv2')
            ->table('CDS.DBR')
            ->select(DB::raw("DBR_CLI_REF_NO, ADR.ADR_NAME, DBR_NO, DBR_NAME1, DBR_ASSIGN_DATE_O, DBR_CLOSE_DATE_O, DBR_ASSIGN_AMT, DBR_RECVD_TOT, STS.STS_DESC, DBR_COM_RATE, DBR_CLIENT, DBR_LAST_WORKED_O, DBR_STATUS, (SELECT COUNT(*) FROM CDSMSC.CHK WHERE DBR.DBR_NO = CHK.CHK_DBR_NO) as count_pdc, DBR_NO+'01XCR' as XCR_CODE"))
            ->leftJoin('CDS.STS', 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
            ->leftJoin('CDS.ADR', function ($join) {
                $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
            })
            ->whereRaw('DBR_CLIENT LIKE ?', ["%{$client}%"])
            ->whereDate('DBR_ASSIGN_DATE_O', $assignedDate)
            ->get();

        $accounts = collect(json_decode(json_encode($accounts), true));

        if ($accounts->isEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['client' => 'No results found']);
        }

        $fileName = "{$client} - {$assignedDate}";

        $columns = collect($this->columns);

        (new Report)->makeSimpleXlsxFromCollection($accounts, $fileName, $columns, $columns);
    }

    public function makeRecallByFileGeneric($client, $file, $genericType = 0)
    {
        $handle = fopen($file, "r");

        $accounts = collect();

        while (($data = fgetcsv($handle)) !== false) {
            $accounts->push($data[0]);
        }

        $accountsStatus = $this->getAccountsOnFile($accounts, $client, $genericType);

    }

    protected function getAccountsOnFile($accounts, $client, $genericType)
    {
        $accountsStatus = collect();
        $accounts->each(function ($item) use ($client, $accountsStatus, $genericType) {

            $builder = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->select(DB::raw("DBR_CLI_REF_NO, ADR.ADR_NAME, DBR_NO, DBR_NAME1, DBR_ASSIGN_DATE_O, DBR_CLOSE_DATE_O, DBR_ASSIGN_AMT, DBR_RECVD_TOT, STS.STS_DESC, DBR_COM_RATE, DBR_CLIENT, DBR_LAST_WORKED_O, DBR_STATUS, (SELECT COUNT(*) FROM CDSMSC.CHK WHERE DBR.DBR_NO = CHK.CHK_DBR_NO) as count_pdc, DBR_NO+'01XCR' as XCR_CODE"))
                ->leftJoin('CDS.STS', 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
                ->leftJoin('CDS.ADR', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                    $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
                })
                ->whereRaw('DBR_CLIENT LIKE ?', ["%{$client}%"]);

            if ($genericType == 0) { // by client ref no
                $account = $builder
                    ->where('DBR_CLI_REF_NO', $item)
                    ->first();

            } else { // by original accnt num
                $account = $builder
                    ->where('ADR_NAME', $item)
                    ->first();
            }

            if (! $account) {
                $account = new \stdClass();
                if ($genericType == 0) {
                    $account->DBR_CLI_REF_NO = $item;
                    $account->ADR_NAME = 'NOT FOUND';
                } else {
                    $account->DBR_CLI_REF_NO = 'NOT FOUND';
                    $account->ADR_NAME = $item;
                }
                $account->DBR_NO = 'NOT FOUND';
                $account->DBR_NAME1 = 'NOT FOUND';
                $account->DBR_ASSIGN_DATE_O = 'NOT FOUND';
                $account->DBR_CLOSE_DATE_O = 'NOT FOUND';
                $account->DBR_ASSIGN_AMT = 'NOT FOUND';
                $account->DBR_RECVD_TOT = 'NOT FOUND';
                $account->STS_DESC = 'NOT FOUND';
                $account->DBR_COM_RATE = 'NOT FOUND';
                $account->DBR_CLIENT = 'NOT FOUND';
                $account->DBR_LAST_WORKED_O = 'NOT FOUND';
                $account->DBR_STATUS = 'NOT FOUND';
                $account->count_pdc = 'NOT FOUND';
                $account->XCR_CODE = 'NOT FOUND';
            }

            $accountsStatus->push($account);
        });

        return $accountsStatus;
    }
}