<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class CascadeRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
               
                ->select(DB::raw("DBR_CLI_REF_NO, DBR_NAME1, DBR_NO, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, TRS_TRUST_CODE, DBR_STATUS, DBR_CLIENT, DBR_COM_RATE"))
    
                ->whereRaw('DBR_CLIENT LIKE ? ', ['CSD%'])
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14', '33'])
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();


        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TransactionType = 'PMT';
                } else  {
                $item->TransactionType = 'RET';
                }

            $sts = ['SIF','PIF'];
            if (in_array($item->DBR_STATUS,$sts)) {
                $item->DBR_STATUS = $item->DBR_STATUS;
                } else {
                $item->DBR_STATUS = '';
            }

            if ($item->TransactionType == 'Payment to Client'){
                $item->Remitted = '-'.$item->TRS_COMM_AMT;
            }else $item->Remitted = ($item->TRS_AMT-$item->TRS_COMM_AMT);
            

            $item->AccountID = $item->DBR_CLI_REF_NO;
            $item->OriginalAccountReported = ' ';
            $item->Amount = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->TranCode = $item->TransactionType;
            $item->TranDate = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->OriginalPayDate = ' ';
            $item->Reference = ' ';
            $item->PaymentID = '10';
            $item->TranSource = '2';
            $item->DueAgency = 'Y';
            $item->CommReported = [($item->DBR_COM_RATE/100),'setFormatCode' => '#%'];
            $item->ContingencyFee =[$item->TRS_COMM_AMT,'setFormatCode' => '#,##0.00'];
            $item->RemittoClient = [($item->Remitted), 'setFormatCode' => '#,##0.00'];
            $item->Closure = $item->DBR_STATUS;

            


            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect([
            'Account',
            'OriginalAccountReported',
            'Amount',
            'TranCode',
            'TranDate',
            'Original Pay date',
            'Reference',
            'PaymentID',
            'TranSource',
            'DueAgency',
            'Comm%Reported',
            'Comm$Reported',
            'NetCollReported',
            'Closure']);
        

        $columns = collect([
            'AccountID', 
            'OriginalAccountReported', 
            'Amount', 
            'TranCode', 
            'TranDate', 
            'OriginalPayDate', 
            'Reference',
            'PaymentID',
            'TranSource',
            'DueAgency', 
            'CommReported', 
            'ContingencyFee', 
            'RemittoClient', 
            'Closure']);

        $fileName = 'UNI_REM_'. Carbon::now()->format('mdy') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        (new ReportExcel)->setFont('Arial', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}