<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

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
            $item->BorrowerName = $item->Orig_Acct_No;
            $item->DebtorPayment = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->ContingencyFee =[$item->TRS_COMM_AMT,'setFormatCode' => '#,##0.00'];

            $item->RemittoClient = [($item->Remitted), 'setFormatCode' => '#,##0.00'];
            

            $item->Date = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->TransactionType = $item->TransactionType;
            $item->Closure =$item->DBR_STATUS;

            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Account ID', ' ClientAccountID', 'IssuerAccountNumber', 'Check Number', 'Date', 'DebtorPayment', 'ContingencyFee', 'Remitted', 'Notes','PaymentType','Closure']);
        

        $columns = collect(['Account ID', ' ClientAccountID', ' ', ' ', 'Date', 'DebtorPayment', 'ContingencyFee', 'Remitted', 'Notes','PaymentType','Closure']);

        $fileName = 'Unifin-Musicians Institute Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        (new ReportExcel)->setFont('Arial', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}