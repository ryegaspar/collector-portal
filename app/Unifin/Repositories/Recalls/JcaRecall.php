<?php

namespace App\Unifin\Repositories\Recalls;

class JcaRecall extends RecallFile
{
    protected $headerKeyIndex = 0;

    /**
     * Definitions of each record in the file.
     *
     * @var array
     */
    protected $recordFormat = [
        'BFrameId'         => [0, 10],
        'RemoteId'         => [10, 4],
        'PlacementDate'    => [14, 8],
        'Portfolio'        => [22, 12],
        'RecordType'       => [34, 2],
        'CreditGrantorNum' => [36, 24],
        'ServiceId'        => [60, 3],
        'DateCreated'      => [63, 8],
        'LastName'         => [71, 22],
        'FirstName'        => [93, 7],
        'AccountBalance'   => [100, 10, 'number'],
        'Reason'           => [111, 6]
    ];

    /**
     * JcaRecall constructor.
     * @param $fileName
     * @param $filePath
     */
    public function __construct($fileName, $filePath)
    {
        $client = 'JCA';
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
        return array_keys($this->recordFormat);
    }

    /**
     * Parses the file given.
     *
     * @return $this|RecallFile
     */
    protected function parseFile()
    {
        $this->accounts = collect();

        $file = new \SplFileObject($this->filePath, "r");

        $index = 0;

        while (! $file->eof()) {

            $line = $file->fgets();

            if ($index == 0) {
                $index++;
                continue;
            }

            foreach ($this->recordFormat as $recordKey => $recordItem) {
                $account[$recordKey] = $this->formatToNumber(trim(substr($line, $recordItem[0], $recordItem[1])), $recordItem);
            }

            $this->accounts[] = $account;
            $index++;
        }

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
