<?php

namespace App\Unifin\Repositories\Recalls;

use App\Unifin\Classes\Report;
use App\Unifin\Repositories\Recalls\Contracts\RecallActionInterface;
use Illuminate\Support\Facades\DB;

class RecallFile implements RecallActionInterface
{
    protected $client;
    protected $fileName;
    protected $filePath;
    protected $generic_type;

    protected $headerKeyIndex = 0;
    protected $accounts;

    /**
     * RecallFile constructor.
     *
     * @param $client
     * @param $fileName
     * @param $filePath
     * @param int $generic_type
     */
    public function __construct($client, $fileName, $filePath, $generic_type = 0)
    {
        $this->client = $client;
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        $this->generic_type = $generic_type;
    }

    /**
     * Create excel report.
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generate()
    {
        $this->parseFile()->getAccounts();

        $columns = collect($this->originalColumnHeaders())->concat(array_keys($this->columns()));

        (new Report)->makeSimpleXlsxFromCollection($this->accounts, $this->fileName, $columns, $columns);
    }

    /**
     * Columns appended to the record.
     * @return array
     */
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
            'count_pdc'         => '(SELECT COUNT(*) FROM [CDSMSC].[CHK] WHERE [DBR].[DBR_NO] = [CHK].[CHK_DBR_NO]) as count_pdc',
            'XCR_CODE'          => "DBR_NO+'01XCR' as XCR_CODE"
        ];
    }

    /**
     * Original column headers.
     *
     * @return array
     */
    protected function originalColumnHeaders()
    {
        return ['given'];
    }

    /**
     * Parses the given file.
     *
     * @return $this
     */
    protected function parseFile()
    {
        $this->accounts = collect();
        $handle = fopen($this->filePath, "r");

        while (($data = fgetcsv($handle)) !== false) {
            foreach($this->originalColumnHeaders() as $header) {
                $this->accounts->push([$header => $data[0]]);
            }
        }

        if ($this->accounts->isEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages(['client' => 'No results found']);
        }

        return $this;
    }

    /**
     * Retrieve accounts from the database.
     *
     * @return $this
     */
    protected function getAccounts()
    {
        $columns = $this->columns();
        $client = $this->client;
        $generic_type = $this->generic_type;
        $accountInfo = collect();

        ($this->accounts)->each(function ($item) use ($columns, $client, $generic_type, $accountInfo) {
            $builder = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->select(DB::raw(implode(',', array_values($columns))))
                ->leftJoin('CDS.STS', 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
                ->leftJoin('CDS.ADR', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                    $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
                })
                ->whereRaw('DBR_CLIENT LIKE ?', ["%{$client}%"]);

            if ($generic_type == 0) { // by client ref no
                $account = $builder
                    ->where('DBR_CLI_REF_NO', $item[$this->originalColumnHeaders()[$this->headerKeyIndex]])
                    ->get();

            } else { // by original accnt num
                $account = $builder
                    ->where('ADR_NAME', $item[$this->originalColumnHeaders()[$this->headerKeyIndex]])
                    ->get();
            }

            if ($account->isEmpty()) {
                $noRecordAccount = new \stdClass();
                foreach (array_keys($this->columns()) as $column) {
                    $noRecordAccount->$column = 'NO RECORD';
                }
                $account[] = $noRecordAccount;
            }

            collect($account)->each(function ($a) use ($item, $accountInfo) {
                foreach($this->originalColumnHeaders() as $header) {
                    $a->$header = $item[$header];
                }
                $accountInfo->push($a);
            });
        });

        $this->accounts = collect(json_decode(json_encode($accountInfo),true));

        return $this;
    }
}