<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentRemit implements ReportInterface
{

    public function generateReport($request)
     {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')

                ->join('UFN.OriginalAccountNumber', 'DBR.DBR_NO', '=', 'OriginalAccountNumber.Dbr_no')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('CDS.CLT', 'DBR.DBR_CLIENT', '=', 'CLT.CLT_NO')
               
                ->select(DB::raw("DBR.DBR_NO, DBR_CLI_REF_NO, TRS_TRX_DATE_O, TRS_TRUST_CODE, TRS_AMT, TRS_COMM_AMT, DBR_CL_MISC_3, TRS_SEQ_NO, Orig_Acct_No"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '14', '33'])
                
                ->whereRaw('CLT_NAME_1 LIKE ?', ['Resurgent Capital Systems'])
               
               
                ->where('TRS_AMT', '<>', 0)
                
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        
        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            $rtn = [19, 120, 121, 122, 123, 220, 221, 222, 223, 320, 321, 322, 323];
            $nsf = [248];

            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'PMT';
            } else if (in_array($item->TRS_TRUST_CODE, $rtn)){
                $item->TRS_TRUST_CODE = 'PRV';
            } else if (in_array($item->TRS_TRUST_CODE, $nsf)){
                $item->TRS_TRUST_CODE = 'NSF';
            } else {
                $item->TRS_TRUST_CODE = 'NRV';
            }

            return $item;
        });
  

        $report = '';


        foreach($data as $item) {
            $report .= $item->Orig_Acct_No;
            $report .= "\t".abs(number_format($item->TRS_AMT,2));
            $report .= "\t".$item->TRS_TRUST_CODE;
            $report .= "\t".Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $report .= "\t".abs(number_format($item->TRS_COMM_AMT,2));
            $report .= "\t".$item->DBR_CL_MISC_3;
            $report .= "\t".$item->TRS_TRUST_CODE;
            $report .= "\t".$item->DBR_CLI_REF_NO;
            $report .= "\t".' ';
            $report .= "\t".$item->DBR_NO.'-'.$item->TRS_SEQ_NO;
            $report .= "\n";
        }

        $filename = 'PA2_8357_'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}