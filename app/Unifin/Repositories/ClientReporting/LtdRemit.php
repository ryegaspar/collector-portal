<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class LtdRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                 ->join('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')
               
                ->select(DB::raw("DBR_CLI_REF_NO, Orig_Acct_No, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, TRS_TRUST_CODE, DBR_STATUS, DBR_CLIENT"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['ltd%'])
               
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

     


        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'Agency Wire';
    
            } else if ($item->TRS_TRUST_CODE == '2'){
                $item->TRS_TRUST_CODE = 'Client Wire';
            } else {
                $item->TRS_TRUST_CODE = 'Agency Refund';
            }

            $closure = ['SIF','PIF'];
            if (in_array($item->DBR_STATUS, $closure)){
                $item->DBR_STATUS = $item->DBR_STATUS;
            } else {
                $item->DBR_STATUS = '';
            }
            
            $item->Orig_Acct_No = [$item->Orig_Acct_No, 'TYPE_STRING'];

            $item->Remitted_AMT = [($item->TRS_AMT -$item->TRS_COMM_AMT),'setFormatCode' => '#,##0.00'];
            $item->TRS_AMT = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->TRS_COMM_AMT = [$item->TRS_COMM_AMT,'setFormatCode' => '#,##0.00'];
            $item->IssuerAccountNumber = '';
            $item->CheckNumber = '';          
            $item->notes = '';
          
            $item->TRS_TRX_DATE_O = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y ');
   
            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Account ID', 'ClientAccountID', 'IssuerAccountNumber', 'Check Number', 'Date', 'DebtorPayment', 'ContingencyFee','Remitted','Notes','PaymentType','Closure']);
        
        $columns = collect(['DBR_CLI_REF_NO','Orig_Acct_No', 'IssuerAccountNumber', 'CheckNumber', 'TRS_TRX_DATE_O', 'TRS_AMT', 'TRS_COMM_AMT', 'Remitted_AMT', 'notes', 'TRS_TRUST_CODE', 'DBR_STATUS']);

        $fileName = 'Unifin-LTD Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        (new ReportExcel)->setFont('Arial', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}