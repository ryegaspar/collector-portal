<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class PzaRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->leftJoin('CDS.ADR', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                    $join->on('ADR_SEQ_NO', '=', DB::raw("'01'"));
                })
               
                ->select(DB::raw("DBR_CLI_REF_NO, DBR_NAME1, DBR_NO, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, TRS_TRUST_CODE, DBR_STATUS, DBR_CLIENT, ADR_TAX_ID, DBR_CL_MISC_2"))
    
                ->whereRaw('DBR_CLIENT LIKE ? ', ['PZA%'])
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

            if ($item->TransactionType == 'Payment to Client'){
                $item->Remitted = '-'.$item->TRS_COMM_AMT;
            }else $item->Remitted = ($item->TRS_AMT-$item->TRS_COMM_AMT);
            

            $item->AccountID = $item->DBR_CLI_REF_NO;
            $item->DBRName = $item->DBR_NAME1;
            $item->UnifinAccountNumber = $item->DBR_NO;
            $item->DebtorPayment = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->ContingencyFee =[$item->TRS_COMM_AMT,'setFormatCode' => '#,##0.00'];
            $item->RemittoClient = [($item->Remitted), 'setFormatCode' => '#,##0.00'];
            $item->TransactionDate = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->TransactionType = $item->TransactionType;
            $item->Closure = $item->DBR_STATUS;
            $item->TaxID = $item->ADR_TAX_ID;
            $item->Lender = $item->DBR_CL_MISC_2;


            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Client Reference Number',
                            'Borrower Name',
                            'Unifin Account Number',
                            'SSN',
                            'Transaction',
                            'Unifin Fees',
                            'Remit to Client',
                            'Transaction Date', 
                            'Transaction Type',
                            'Closure Code',
                            'Lender']);
        

        $columns = collect(['AccountID', 
                            'DBRName', 
                            'UnifinAccountNumber',
                            'TaxID', 
                            'DebtorPayment', 
                            'ContingencyFee', 
                            'RemittoClient', 
                            'TransactionDate', 
                            'TransactionType', 
                            'Closure',
                            'Lender']);

        $fileName = 'Unifin-Plaza Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        (new ReportExcel)->setFont('Arial', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}