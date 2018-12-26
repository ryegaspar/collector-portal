<?php
/**
 * discontinued
 *
 * recall file that creates temporary table and joins it with
 * the tiger database, apparently it takes a while to load 2k+
 * records, maybe using a queue would be better.
 */

namespace App\Unifin\Repositories\Recalls;

use App\Unifin\Classes\Report;
use App\Unifin\Repositories\Recalls\Contracts\RecallActionInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecallFile2 implements RecallActionInterface
{
    protected $client;
    protected $fileName;
    protected $filePath;
    protected $generic_type;

    protected $numOfHeaders = 1;
    protected $originalColumnHeaders = ['given'];
    protected $headerKey = 1;
    protected $tempTable;
    protected $accounts;

    public function __construct($client, $fileName, $filePath, $generic_type)
    {
        $this->client = $client;
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        $this->generic_type = $generic_type;
    }

    public function generate()
    {
        $accounts = collect(json_decode(json_encode($this->parseFile()->makeTempTable()->getAccounts()), true));

        Schema::dropIfExists($this->tempTable);

        $columnHeaders = collect(array_keys($this->columns()));

        foreach ($this->originalColumnHeaders as $columnHeader) {
            $columnHeaders->prepend($columnHeader);
        }

        $this->makeExcel($accounts, $this->client, $this->fileName, $columnHeaders);
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
            'count_pdc'         => '(SELECT COUNT(*) FROM [' . env('DB_HOST2') . "]." . env('DB_DATABASE2') . '.[CDSMSC].[CHK] WHERE [DBR].[DBR_NO] = [CHK].[CHK_DBR_NO]) as count_pdc',
            'XCR_CODE'          => "DBR_NO+'01XCR' as XCR_CODE"
        ];
    }

    protected function parseFile()
    {
        $this->accounts = collect();
        $handle = fopen($this->filePath, "r");

        while (($data = fgetcsv($handle)) !== false) {
            $this->accounts->push(['column01' => $data[0]]);
        }

        return $this;
    }

    protected function makeTempTable()
    {
        $this->tempTable = 'Z' . substr(str_shuffle(str_repeat("ABCDEFGHJKLMNPQRSTUVWXYZ23456789", 24)), 0, 5);

        Schema::create($this->tempTable, function (Blueprint $table) {
            $table->increments('id');
            for ($x = 1; $x <= $this->numOfHeaders; $x++) {
                $table->string('column' . sprintf('%02d', $x));
            }
        });

        DB::table($this->tempTable)->insert($this->accounts->toArray());

        return $this;
    }

    protected function getAccounts()
    {
        $dbr = DB::connection('sqlsrv2')
            ->table(env('DB_HOST2') . "." . env('DB_DATABASE2') . ".CDS.DBR")
            ->select(DB::raw(implode(",", array_values($this->columns()))))
            ->join(env('DB_HOST2') . "." . env('DB_DATABASE2') . ".CDS.STS", 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
            ->join(env('DB_HOST2') . "." . env('DB_DATABASE2') . ".CDS.ADR", function ($join) {
                $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
            })
            ->whereRaw('DBR_CLIENT LIKE ?', ["%{$this->client}%"]);

        if ($this->generic_type == 0) { // by cli ref no

            return DB::connection('sqlsrv')
                ->table(env('DB_DATABASE') . ".dbo.{$this->tempTable}")
                ->select(DB::raw(implode(",", $this->generateColumns())))
                ->leftJoinSub($dbr, 'dbr', function ($join) {
                    $join->on('column' . sprintf('%02d', $this->headerKey), '=', 'dbr.DBR_CLI_REF_NO');
                })
                ->get();
        }

        return DB::connection('sqlsrv')
            ->table(env('DB_DATABASE') . ".dbo.{$this->tempTable}")
            ->select(DB::raw(implode(",", $this->generateColumns())))
            ->leftJoinSub($dbr, 'dbr', function ($join) {
                $join->on('column' . sprintf('%02d', $this->headerKey), '=', 'dbr.ADR_NAME');
            })
            ->get();
    }

    private function generateColumns()
    {
        $columns = [];

        for ($i = 0; $i < $this->numOfHeaders; $i++) {
            $columns[$i] = 'column' . sprintf('%02d', $i + 1) . ' as ' . $this->originalColumnHeaders[$i];
        }

        foreach (array_keys($this->columns()) as $column) {
            $columns[] = 'dbr.' . $column;
        }

        return $columns;
    }

    private function makeExcel($accounts, $client, $subtitle, $columns)
    {
        $fileName = "{$client} - {$subtitle}";

        (new Report)->makeSimpleXlsxFromCollection($accounts, $fileName, $columns, $columns);
    }
}