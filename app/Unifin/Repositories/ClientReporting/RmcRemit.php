<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class RmcRemit implements ReportInterface
{

    public function generateReport($request) 
       {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')
               
                ->select(DB::raw("DBR.DBR_NO, DBR_CLIENT, DBR_CLI_REF_NO, Orig_Acct_No, DBR_STATUS, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, DBR_PRINCIPAL_DUE, TRS_TRUST_CODE, DBR_CL_MISC_3 "))
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['RMC%'])
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();   


        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'Payment to Agency';
            
            } 
            $sts = ['PAY','BRK','CON'];
            if (in_array($item->DBR_STATUS,$sts)) {
                $item->DBR_STATUS = ' ';
             }    else {
                $item->DBR_STATUS = $item->DBR_STATUS;
            }
            
            $item->AccountID = $item->DBR_CLI_REF_NO;
            $item->ClientAccountID = $item->DBR_CL_MISC_3 ;
            $item->IssuerAccountNumber = $item->Orig_Acct_No; 
            $item->CheckNumber = '';
            $item->Date =Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->DebtorPayment = $item->TRS_AMT; // number format
            $item->ContingencyFee = '';
            $item->Remitted = $item->TRS_AMT - $item->TRS_COMM_AMT; // number format
            $item->Notes = $item->TRS_COMM_AMT; // number format
            $item->PmtType = $item->TRS_TRUST_CODE;  
            $item->Closure = $item->DBR_STATUS;
   
            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['AccountID','ClientAccountID','IssuerAccountNumber', 'CheckNumber','Date','DebtorPayment','ContingencyFee','Remitted','Notes','PmtType','Closure']);
        
        

        $columns = collect(['AccountID','ClientAccountID','IssuerAccountNumber', 'CheckNumber','Date','DebtorPayment','ContingencyFee','Remitted','Notes','PmtType','Closure']);

        $fileName = 'Unifin-Rocky Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        $this->createExcel($data, $fileName, $headers, $columns, $filePath);

    }

    public function createExcel($data, $fileName, $headers, $columns, $filePath)
    {
        $spreadsheet = new Spreadsheet;

        $spreadsheet->getProperties()->setCreator('Claire')
            ->setLastModifiedBy('Claire');

        $spreadsheet->setActiveSheetIndex(0)->setTitle('DebtorPayments');
        $spreadsheet->createSheet()->setTitle('DebtorPayNSFs');
        $spreadsheet->createSheet()->setTitle('DirectPayments');
        $spreadsheet->createSheet()->setTitle('DirectPayNSFs');

        $headers->each(function ($item, $index) use ($spreadsheet) {
            $spreadsheet->setActiveSheetIndexByName('DebtorPayments')
                ->setCellValueByColumnAndRow($index + 1, 1, $item);
        });

        $row = 2;
        $data->each(function ($item) use ($spreadsheet, &$row, $columns) {
            $columns->each(function ($columnName, $index) use ($item, $spreadsheet, $row) {
                $spreadsheet->setActiveSheetIndexByName('Remit')->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName]);
            });

            $row++;
        });

        $recordCount = $data->count() + 1;

        $spreadsheet->setActiveSheetIndexByName('Remit')->getStyle('F2:I'. $recordCount)->getNumberFormat()->setFormatCode('#,##0.00');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
    }

}