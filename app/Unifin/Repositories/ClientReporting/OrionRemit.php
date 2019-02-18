<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrionRemit implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();


            $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')

                ->select(DB::raw("DBR_CLI_REF_NO,Orig_Acct_No, TRS_TRX_DATE_O, TRS_AMT,TRS_TRUST_CODE, DBR_CLIENT, DBR_ASSIGN_DATE_O, DBR_COM_RATE, DBR_CL_MISC_3"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['ORI%'])
               
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();
        

        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303, 400, 401, 402, 403];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->PaymentType = 'Payment to Agency';
    
            } else if ($item->TRS_TRUST_CODE == '2'){
                $item->PaymentType = 'Payment to Client';
            } else {
                $item->PaymentType = 'Payment to Agency';
            }

            return $item;
        });

        $report = '';

        foreach($data as $item) {
            $report .= 'UNIF';
            $report .= "\t". $item->DBR_CL_MISC_3;
            $report .= "\t".Carbon::parse($item->DBR_ASSIGN_DATE_O)->format('m/d/Y');
            $report .= "\t". $item->Orig_Acct_No;
            $report .= "\t".$item->PaymentType;
            $report .= "\t".Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $report .= "\t".number_format($item->TRS_AMT,2);
            $report .= "\tUNIF";
            $report .= "\t3";
            $report .= "\t2";
            $report .= "\t".number_format($item->DBR_COM_RATE,2);
            $report .= "\n";
        }


      

        $filename = 'PAY-UNIF_'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
        
	}
}



