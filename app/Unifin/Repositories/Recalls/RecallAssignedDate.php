<?php

namespace App\Unifin\Repositories\Recalls;

use App\Unifin\Classes\Report;
use App\Unifin\Repositories\Recalls\Contracts\RecallActionInterface;
use Illuminate\Support\Facades\DB;

class RecallAssignedDate implements RecallActionInterface
{
    protected $client;
    protected $assignedDate;

    private $accounts;

    public function __construct($client, $assignedDate)
    {
        $this->client = $client;
        $this->assignedDate = $assignedDate;
    }

    public function generate()
    {
        $this->getAccounts();

        $this->makeExcel();
    }

    protected function columns()
    {
        return [ //column => select query
            'DBR_CLI_REF_NO'    => 'DBR_CLI_REF_NO',
            'ADR_NAME'          => 'ADR_NAME',
            'DBR_NAME1'         => 'DBR_NAME1',
            'DBR_ASSIGN_DATE_O' => 'DBR_ASSIGN_DATE_O',
            'DBR_CLOSE_DATE_O'  => 'DBR_CLOSE_DATE_O',
            'DBR_ASSIGN_AMT'    => 'DBR_ASSIGN_AMT',
            'DBR_RECVD_TOT'     => 'DBR_RECVD_TOT',
            'STS_DESC'          => 'STS_DESC',
            'DBR_COM_RATE'      => 'DBR_COM_RATE',
            'DBR_CLIENT'        => 'DBR_CLIENT',
            'DBR_LAST_WORKED_O' => 'DBR_LAST_WORKED_O',
            'DBR_STATUS'        => 'DBR_STATUS',
            'DBR_NO'            => 'DBR_NO',
            'count_pdc'         => '(SELECT COUNT(*) FROM ' . env('DB_DATABASE2') . '.[CDSMSC].[CHK] WHERE [DBR].[DBR_NO] = [CHK].[CHK_DBR_NO]) as count_pdc',
            'XCR_CODE'          => "DBR_NO+'01XCR' as XCR_CODE"
        ];
    }

    protected function getAccounts()
    {
        $accounts = DB::connection('sqlsrv2')
            ->table('CDS.DBR')
            ->select(DB::raw(implode(",", array_values($this->columns()))))
            ->leftJoin('CDS.STS', 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
            ->leftJoin('CDS.ADR', function ($join) {
                $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
            })
            ->whereRaw('DBR_CLIENT LIKE ?', ["%{$this->client}%"])
            ->whereDate('DBR_ASSIGN_DATE_O', $this->assignedDate)
            ->get();

        $this->accounts = collect(json_decode(json_encode($accounts), true));

        if (($this->accounts)->isEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['client' => 'No results found']);
        }

        return $this;
    }

    protected function makeExcel()
    {
        $fileName = "{$this->client} - {$this->assignedDate}";
        $columns = collect(array_keys($this->columns()));

        (new Report)->makeSimpleXlsxFromCollection($this->accounts, $fileName, $columns, $columns);
    }
}
