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
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '14', '30', '31', '32', '33', '34', '35' ])
                
                ->whereRaw('CLT_NAME_1 LIKE ?', ['Resurgent Capital Systems'])
               
               
                ->where('TRS_AMT', '<>', 0)
                
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        
       $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303, 400, 401, 402, 403, 500, 501, 502, 503];
            $rtn = [4, 120, 121, 122, 123, 140,141,142,143, 220, 221, 222, 223];
            $nsf = [241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 320, 321, 322, 323];

            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'PMT';
            } else if (in_array($item->TRS_TRUST_CODE, $rtn)){
                $item->TRS_TRUST_CODE = 'PRV';
            } else if (in_array($item->TRS_TRUST_CODE, $nsf)){
                $item->TRS_TRUST_CODE = 'NSF';
            } else {
                $item->TRS_TRUST_CODE = 'NRV';
            }


            $item->ABS_TRS_AMT = abs($item->TRS_AMT);
            $item->ABS_TRS_COMM_AMT = abs($item->TRS_COMM_AMT);


            return $item;
        });
  

        $report = '';


        foreach($data as $item) {
            $report .= $item->Orig_Acct_No;
            $report .= "\t".number_format($item->ABS_TRS_AMT,2);
            $report .= "\t".$item->TRS_TRUST_CODE;
            $report .= "\t".Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $report .= "\t".number_format($item->TRS_COMM_AMT,2);
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