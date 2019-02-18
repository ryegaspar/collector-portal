<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlsRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('CDS.ADR', 'DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO')

               
                ->select(DB::raw("TRS_TRX_DATE_O, ADR_NAME, DBR_CLI_REF_NO, DBR_CL_MISC_3, DBR_STATUS, TRS_AMT, DBR_COM_RATE, TRS_TOTAL_DUE, TRS_COMM_AMT, ADR_STATE, DBR_CLIENT, TRS_TRUST_CODE"))
    
                
                ->whereRaw('DBR_CLIENT LIKE ? AND ADR.ADR_SEQ_NO = ?', ['PLS%', '01'])

                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();


        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TransactionType = 'Payment to Agency';
                
                } else if ($item->TRS_TRUST_CODE == '2'){
                $item->TransactionType = 'Payment to Client';

                } else {
                $item->TransactionType = 'Payment to Agency';
                }

           

            $item->Remitted = ($item->TRS_AMT-$item->TRS_COMM_AMT);
            $item->TransactionDate = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->AdrName = $item->ADR_NAME;
            $item->AccountID = $item->DBR_CLI_REF_NO;
            $item->StoreName = $item->DBR_CL_MISC_3;
            $item->STSCode = $item->DBR_STATUS;
            $item->AmountPaid = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->AmountPaidToPls = [($item->Remitted),'setFormatCode' => '#,##0.00'];
            $item->OurCommission = [($item->DBR_COM_RATE/100),'setFormatCode' => '#%'];
            $item->RemainingBalance = [$item->TRS_TOTAL_DUE,'setFormatCode' => '#,##0.00'];
            $item->AmountDueToYou = [$item->TRS_COMM_AMT,'setFormatCode' => '#,##0.00'];
            $item->DebtorState = $item->ADR_STATE;

            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['DATE','NAME','ACCOUNT NUMBER','STORE NUMBER','STS CODE', 'AMOUNT PAID', 'AMOUNT PAID TO PLS', 'OUR COMMISSION','REMAINING BALANCE', 'AMOUNT DUE YOU',  
            'DEBTOR STATE']);
        

        $columns = collect(['TransactionDate', 'AdrName', 'AccountID', 'StoreName', 'STSCode', 'AmountPaid', 'AmountPaidToPls', 'OurCommission', 'RemainingBalance','AmountDueToYou','DebtorState']);

        $fileName = 'UNIFIN REMIT '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        $this->createExcel($data, $fileName, $headers, $columns, $filePath);

    }

    public function createExcel($data, $fileName, $headers, $columns, $filePath)
    {
        $spreadsheet = new Spreadsheet;

        $spreadsheet->getProperties()->setCreator('Claire')
            ->setLastModifiedBy('Claire');

        $spreadsheet->setActiveSheetIndex(0)->setTitle('Remit');
        $spreadsheet->createSheet()->setTitle('DPs');



        $dps = $data->filter(function ($value, $key) {
            return $value['TransactionType'] == 'Payment to Client';
        });

        $ups = $data->filter(function ($value, $key) {
            return $value['TransactionType'] == 'Payment to Agency';
        });

        $headers->each(function ($item, $index) use ($spreadsheet) {
            $spreadsheet->setActiveSheetIndexByName('Remit')
                ->setCellValueByColumnAndRow($index + 1, 1, $item);
            $spreadsheet->setActiveSheetIndexByName('DPs')
                ->setCellValueByColumnAndRow($index + 1, 1, $item);
        });

        $row = 2;
        $dps->each(function ($item) use ($spreadsheet, &$row, $columns) {
            $columns->each(function ($columnName, $index) use ($item, $spreadsheet, $row) {
                $this->xlsxAddSheet($spreadsheet, $item, $columnName, $index, 'DPs', $row);
            });
            $row++;
        });

        $row = 2;
        $ups->each(function ($item) use ($spreadsheet, &$row, $columns) {
            $columns->each(function ($columnName, $index) use ($item, $spreadsheet, $row) {
                $this->xlsxAddSheet($spreadsheet, $item, $columnName, $index, 'Remit', $row);
            });
            $row++;
        });




        $dpsCount = $dps->count() + 1;
        $upsCount = $ups->count() + 1;


       
        $spreadsheet->setActiveSheetIndexByName('Remit')->getStyle('G2:G'. $upsCount)->getNumberFormat()->setFormatCode("#,##0.00_);[Red](#,##0.00)");

        $spreadsheet->setActiveSheetIndexByName('DPs')->getStyle('G2:G'. $dpsCount)->getNumberFormat()->setFormatCode("[Red](#,##0.00)");




        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
    }

    private function xlsxAddSheet($spreadsheet, $item, $columnName, $index, $sheetName, $row) {
        if (is_array($columnName)) {
            $spreadsheet->setActiveSheetIndexByName($sheetName)
                // ->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName[0]][$columnName[1]]);
            ->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName[0]][$columnName[1]]);
        } else {
            if (is_array($item[$columnName])) {

                if (array_key_exists('setFormatCode', $item[$columnName])) {

                    $coordinates = $spreadsheet->setActiveSheetIndexByName($sheetName)
                    ->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName][0])
                    ->getCoordinates();
                        
                    array_shift($item[$columnName]);

                    foreach ($item[$columnName] as $key => $format) {
                        $spreadsheet->getActiveSheet()->getStyle(array_pop($coordinates))->getNumberFormat()->$key($format);
                    }    
                } else {

                    $lookup = [
                        'TYPE_STRING' => DataType::TYPE_STRING,
                        'TYPE_NUMERIC' => DataType::TYPE_NUMERIC
                    ];


                    $coordinates = $spreadsheet->setActiveSheetIndexByName($sheetName)
                    ->setCellValueExplicitByColumnAndRow($index + 1, $row, $item[$columnName][0], $lookup[$item[$columnName][1]])
                    ->getCoordinates();
                }
                
            } else {
                
                $spreadsheet->setActiveSheetIndexByName($sheetName)->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName]);

            }
        }
    }

}