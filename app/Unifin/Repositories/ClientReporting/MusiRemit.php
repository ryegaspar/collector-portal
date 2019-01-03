<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class MusiRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
               
                ->select(DB::raw("DBR_CLI_REF_NO, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, TRS_TRUST_CODE, DBR_STATUS, DBR_CLIENT, DBR_NAME1, DBR_NO"))
    
                ->whereRaw('DBR_CLIENT LIKE ? ', ['MUSI%'])
                ->whereNotIn('TRS_TRUST_CODE', ['2','3', '14', '33'])
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



            $sts = ['SIF','PIF'];
            if (in_array($item->DBR_STATUS,$sts)) {
                $item->DBR_STATUS = $item->DBR_STATUS;
                } else {
                $item->DBR_STATUS = '';
            }
            
            $item->ClientReferenceNumber = $item->DBR_CLI_REF_NO;
            $item->BorrowerName = $item->DBR_NAME1;
            $item->UnifinAccountNumber = $item->DBR_NO; 
            $item->TransactionAmount = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->UnifinFees =[$item->TRS_COMM_AMT,'setFormatCode' => '#,##0.00'];
            $item->RemittoClient = [($item->TRS_AMT-$item->TRS_COMM_AMT), 'setFormatCode' => '#,##0.00'];
            $item->TransactionDate = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->TransactionType = $item->TransactionType;
            $item->ClosureCode =$item->DBR_STATUS;

            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Client Reference Number', 'Borrower Name', 'Unifin Account Number', 'Transaction Amount', 'Unifin Fees', 'Remit to Client', 'Transaction Date', 'Transaction Type', 'Closure Code']);
        

        $columns = collect(['ClientReferenceNumber', 'BorrowerName', 'UnifinAccountNumber', 'TransactionAmount', 'UnifinFees', 'RemittoClient', 'TransactionDate', 'TransactionType', 'ClosureCode']);

        $fileName = 'Unifin-Musicians Institute Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        (new ReportExcel)->setFont('Arial', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}