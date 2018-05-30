<?php

namespace App\Unifin\Repositories\Placements;

use Illuminate\Support\Facades\Storage;

class Jcap
{

    protected $contents;
    protected $placements;
    protected $filename;

    protected $csvHeaders = [
        'BFrame ID',
        'Remote ID',
        'Placement Date',
        'Portfolio',
        'Record Type',
        'Credit Grantor Number',
        'Social Security',
        'Last Name',
        'First Name',
        'Address 1',
        'Address 2',
        'City',
        'State/Province',
        'Postal Code',
        'Country',
        'Home Telephone',
        'Business Telephone',
        'Other Telephone',
        'Address Status',
        'Date of Birth',
        'Type of Interest',
        'Commission Rate',
        'Account Balance',
        'Principal Balance',
        'Interest Balance',
        'Charges Balance',
        'Filler',
        'Filler',
        'Collection Cost',
        'Amount Last Paid',
        'Date Last Paid',
        'Interest Rate',
        'Interest Date',
        'Original Note Date',
        'Original Start Date',
        'Original APR',
        'Original Note Amount',
        'Original Term',
        'Original Balance',
        'Last Payment Amount prior to JCAP',
        'Original Maturity',
        'Adjusted Maturity',
        'Filler',
        'Last Payment date prior to JCAP',
        'Miscellaneous Description 1',
        'Miscellaneous Description 2',
        'Miscellaneous Description 3',
        'Miscellaneous Description 4',
        'Miscellaneous Id',
        'First Payment Default',
        'Write Off Amount',
        'Write Off Date',
        'Amount Last Transaction',
        'Filler',
        'Filler',
        'Social Security',
        'Last Name',
        'First Name',
        'Address 1',
        'Address 2',
        'City',
        'State/Province',
        'Postal Code',
        'Country',
        'Home Telephone',
        'Business Telephone',
        'Other telephone',
        'Employer',
        'Current Creditor'
    ];

    protected $cnRecord = [
        'RemoteIDs'                        => [10, 4],
        'PlacementDates'                   => [14, 8],
        'Portfolios'                       => [22, 12],
        'RecordType'                       => [34, 2],
        'CreditGrantorNumber'              => [36, 24],
        'SocialSecurity'                   => [60, 9],
        'LastName'                         => [69, 17],
        'FirstName'                        => [86, 13],
        'Address1'                         => [99, 30],
        'Address2'                         => [129, 30],
        'City'                             => [159, 20],
        'StateProvince'                    => [179, 2],
        'Postal'                           => [181, 9],
        'Country'                          => [190, 3],
        'HomeTelephone'                    => [193, 10],
        'BusinessTelephone'                => [203, 10],
        'OtherTelephone'                   => [213, 10],
        'Address Status'                   => [223, 1],
        'DateOfBirth'                      => [224, 8],
        'TypeInterest'                     => [232, 1],
        'CommissionRate'                   => [233, 5],
        'AccountBalance'                   => [238, 13],
        'PrincipalBalance'                 => [251, 13],
        'InterestBalance'                  => [264, 13],
        'ChargesBalance'                   => [277, 13],
        'Filler1'                          => [290, 13],
        'Filler2'                          => [303, 13],
        'CollectionCost'                   => [316, 13],
        'AmountLastPaid'                   => [329, 13],
        'DateLastPaid'                     => [342, 8],
        'InterestRate'                     => [350, 8],
        'InterestDate'                     => [358, 8],
        'OriginalNoteDate'                 => [366, 8],
        'OriginalStartDate'                => [374, 8],
        'OriginalAPR'                      => [382, 7],
        'OriginalNoteAmount'               => [389, 11],
        'OriginalTerm'                     => [400, 4],
        'OriginalBalance'                  => [404, 11],
        'OriginalPaymentAmountPriorToJCAP' => [415, 8],
        'OriginalMaturity'                 => [423, 8],
        'AdjustedMaturity'                 => [431, 8],
        'Filler3'                          => [439, 16],
        'OriginalPaymentDatePriorToJCAP'   => [455, 8],
        'MiscellaneousDescription1'        => [463, 25],
        'MiscellaneousDescription2'        => [488, 25],
        'MiscellaneousDescription3'        => [513, 25],
        'MiscellaneousDescription4'        => [538, 25],
        'MiscellaneousDescriptionID'       => [563, 6],
        'FirstPaymentDefault'              => [569, 1],
        'WriteOffAmount'                   => [570, 9],
        'WriteOffDate'                     => [579, 8],
        'AmountLastTransaction'            => [587, 9],
        'Filler4'                          => [596, 3],
        'Filler5'                          => [599, 1]
    ];

    protected $ccRecord = [
        'ccSocialSecurity'    => [60, 9],
        'ccLastName'          => [69, 17],
        'ccFirstName'         => [86, 13],
        'ccAddress1'          => [99, 30],
        'ccAddress2'          => [129, 30],
        'ccCity'              => [159, 20],
        'ccStateProvince'     => [179, 2],
        'ccPostal'            => [181, 9],
        'ccCountry'           => [190, 3],
        'ccHomeTelephone'     => [194, 10],
        'ccBusinessTelephone' => [204, 10]
    ];

    protected $ceRecord = [
        'ceName' => [60, 30]
    ];

    public function __construct($file, $filename)
    {
        $this->filename = $filename;
        foreach (file($file) as $line) {
            $contents[] = $line;
        }
        $this->contents = collect($contents);
        $this->processRecords();
    }

    public function processRecords()
    {
        foreach ($this->contents as $item) {
            $cnRecord = array();
            $ccRecord = array();
            $ceRecord = array();
            $bframeID = trim(substr($item, 0, 10));
            $recordType = substr($item, 34, 2);

            //cn record
            if ($recordType == 'CN') {
                foreach ($this->cnRecord as $recordKey => $recordItem) {
                    $cnRecord[$recordKey] = trim(substr($item, $recordItem[0], $recordItem[1]));
                }
                $this->placements[$bframeID] = $cnRecord;

                //create blank cc
                foreach ($this->ccRecord as $recordKey => $recordItem) {
                    $ccRecord[$recordKey] = '';
                }
                $this->placements[$bframeID] += $ccRecord;

                //create blank ce
                foreach ($this->ceRecord as $recordKey => $recordItem) {
                    $ceRecord[$recordKey] = '';
                }
                $this->placements[$bframeID] += $ceRecord;

                //create creditor name;
                $this->placements[$bframeID] += ['CurrentCreditor' => 'Jefferson Capital Systems, LLC'];
            }

            //cc record
            if ($recordType == 'CC') {
                foreach ($this->ccRecord as $recordKey => $recordItem) {
                    $ccRecord[$recordKey] = trim(substr($item, $recordItem[0], $recordItem[1]));
                }
                $this->placements[$bframeID] = array_merge($this->placements[$bframeID], $ccRecord);
            }

            //ce record
            if ($recordType == 'CE') {
                foreach ($this->ceRecord as $recordKey => $recordItem) {
                    $ceRecord[$recordKey] = trim(substr($item, $recordItem[0], $recordItem[1]));
                }
                $this->placements[$bframeID] = array_merge($this->placements[$bframeID], $ceRecord);
            }
        }
    }

    public function getPlacements()
    {
        return $this->placements;
    }

    public function getCSV()
    {

    }
}