<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AsgRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')
               
                ->select(DB::raw("DBR_CLI_REF_NO, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT,TRS_TRUST_CODE, DBR_STATUS, DBR_CLIENT,Orig_Acct_No"))
    
                ->whereRaw('DBR_CLIENT LIKE ? ', ['ASG%'])
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14', '33'])
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();


        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TransactionType = 'Agency Wire';
                
                } else if ($item->TRS_TRUST_CODE == '2'){
                $item->TransactionType = 'Client Wire';

                } else {
                $item->TransactionType = 'Agency Refund';
                }

            $sts = ['SIF','PIF'];
            if (in_array($item->DBR_STATUS,$sts)) {
                $item->DBR_STATUS = $item->DBR_STATUS;
                } else {
                $item->DBR_STATUS = '';
            }

            if ($item->DBR_STATUS == 'Client Wire'){
                $item->Remitted = '-'.$item->TRS_COMM_AMT;
            }else $item->Remitted = ($item->TRS_AMT-$item->TRS_COMM_AMT);
            

            $item->AccountID = $item->DBR_CLI_REF_NO;
            $item->ClientAccountID = $item->Orig_Acct_No;
            $item->IssuerAccountNumber = '';
            $item->CheckNumber = '';
            $item->DebtorPayment = $item->TRS_AMT;
            $item->ContingencyFee =$item->TRS_COMM_AMT;
            $item->Remitted = $item->Remitted;
            $item->Date = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->PaymentType = $item->TransactionType;
            $item->Closure =$item->DBR_STATUS;

            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect([
        'Account ID',
         'ClientAccountID', 
         'IssuerAccountNumber', 
         'Check Number', 
         'Date', 
         'DebtorPayment', 
         'ContingencyFee', 
         'Remitted', 
         'Notes',
         'PaymentType',
         'Closure']);
        

        $columns = collect([
        'AccountID', 
        'ClientAccountID',
        'IssuerAccountNumber', 
        'IssuerAccountNumber', 
        'Date', 
        'DebtorPayment',
        'ContingencyFee', 
        'Remitted', 
        'IssuerAccountNumber',
        'PaymentType',
        'Closure']);

        $fileName = 'Unifin-ASG Remittance Report '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        $this->createExcel($data, $fileName, $headers, $columns, $filePath);

    }

    public function createExcel($data, $fileName, $headers, $columns, $filePath)
    {
        $spreadsheet = new Spreadsheet;

        $spreadsheet->getProperties()->setCreator('Claire')
            ->setLastModifiedBy('Claire');

        $headers->each(function ($item, $index) use ($spreadsheet) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($index + 1, 1, $item);
        });

        $row = 2;

        $data->each(function ($item) use ($spreadsheet, &$row, $columns) {
            $columns->each(function ($columnName, $index) use ($item, $spreadsheet, $row) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName]);
            });

            $row++;
        });

      

        $recordCount = $data->count() + 1;

        $sumDebtorPayment = $data->pluck('DebtorPayment')->sum();
        $sumPayment = $data->pluck('ContingencyFee')->sum();
        $sumRemitted = $data->pluck('Remitted')->sum();

        $style = [
            'font' => [
                'bold' => true
            ]
        ];


        $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow(5, $row + 1, 'Totals:');
        $spreadsheet->getActiveSheet()->getStyleByColumnAndRow(5, $row + 1)->applyFromArray($style);

        $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow(6, $row + 1, $sumDebtorPayment);
        $spreadsheet->getActiveSheet()->getStyleByColumnAndRow(6, $row + 1)->applyFromArray($style);

        $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow(7, $row + 1 ,$sumPayment);
        $spreadsheet->getActiveSheet()->getStyleByColumnAndRow(7, $row + 1)->applyFromArray($style);

        $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow(8, $row + 1 ,$sumRemitted);
        $spreadsheet->getActiveSheet()->getStyleByColumnAndRow(8, $row + 1)->applyFromArray($style);

        //$spreadsheet->setActiveSheetIndexByName('Remit')->getStyle('F2:I'. $recordCount)->getNumberFormat()->setFormatCode('#,##0.00');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
    }

}