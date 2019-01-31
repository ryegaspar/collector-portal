<?php

namespace App\Unifin\Repositories\Recalls;

class RcsRecall extends RecallFile
{
    protected $headerKeyIndex = 1;

    /**
     * Definitions of each record in the file.
     *
     * @var array
     */
    protected $recordFormat = [
        'FileType',
        'AccountId',
        'AccntNumber',
        'DebtorNo',
        'Fname',
        'Lname',
        'SSN',
        'AccntStatusId'
    ];

    /**
     * RcsRecall constructor.
     *
     * @param $fileName
     * @param $filePath
     */
    public function __construct($fileName, $filePath)
    {
        $client = 'RCS';
        $generic_type = 0;

        parent::__construct($client, $fileName, $filePath, $generic_type);
    }

    /**
     * Columns appended to the record.
     *
     * @return array
     */
    protected function columns()
    {
        return [
            'DBR_STATUS' => 'DBR_STATUS',
            'DBR_NO'     => 'DBR_NO',
            'count_pdc'  => '(SELECT COUNT(*) FROM [CDSMSC].[CHK] WHERE [DBR].[DBR_NO] = [CHK].[CHK_DBR_NO]) as count_pdc',
            'XCR_CODE'   => "DBR_NO+'01XCR' as XCR_CODE"
        ];
    }

    /**
     * Original column headers.
     *
     * @return array
     */
    protected function originalColumnHeaders()
    {
        return $this->recordFormat;
    }

    /**
     * Parses the given file.
     *
     * @return $this|RecallFile
     */
    protected function parseFile()
    {
        $this->accounts = collect();

        $file = fopen($this->filePath, "r");

        while (!feof($file)) {
            $line = fgets($file, 2048);

            $data = str_getcsv($line, "\t");

            if (sizeof($data) <= 1) continue;

            $index = 0;
            foreach($this->recordFormat as $recordItem) {
                $account[$recordItem] = $data[$index];
                $index++;
            }

            $this->accounts[] = $account;
        }

        fclose($file);

        return $this;
    }

    /**
     * Format to a number format, 2 decimal places.
     *
     * @param $value
     * @param $record
     * @return float
     */
    private function formatToNumber($value, $record)
    {
        if (isset($record[2]) && $record[2] == 'number') {
            return floatval($value);
        }

        return $value;
    }
}
