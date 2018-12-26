<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClaPayFile implements ReportInterface
{

    public function generateReport($request)
     {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->leftJoin('CDS.ADR', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                    $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
                })
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('CDS.UDW', 'DBR.DBR_NO', '=', 'UDW.UDW_DBR_NO')
               
                ->select(DB::raw("DBR_ASSIGN_DATE_O, DBR_CLI_REF_NO, ADR_NAME, TRS_TRUST_CODE, ADR_NAME, ADR_SEQ_NO, DBR_CLIENT, UDW_FLD1, TRS_TRUST_CODE, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, UDW_SEQ"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '14', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? AND UDW.UDW_SEQ = ?', ['CLA%', '0CL'])
               
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        

        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];

            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'PMT';
            } else {
                $item->TRS_TRUST_CODE = 'RET';
            }

            return $item;
        });

        $report = '';

        foreach($data as $item) {
            $report .= 'XUP';
            $report .= "\t".$item->UDW_FLD1;
            $report .= "\t".Carbon::parse($item->DBR_ASSIGN_DATE_O)->format('m/d/Y');
            $report .= "\t".$item->DBR_CLI_REF_NO;
            $report .= "\t".str_replace('#', '', $item->ADR_NAME);
            $report .= "\t".$item->TRS_TRUST_CODE;
            $report .= "\t".Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $report .= "\t".$item->TRS_AMT;
            $report .= "\tXUP";
            $report .= "\t3";
            $report .= "\t2";
            $report .= "\t".number_format(($item->TRS_COMM_AMT/$item->TRS_AMT)* 100,2);
            if ($item->TRS_TRUST_CODE == 'MC') 
                $report .= "\tN";
            else
                $report .= "\tY";

            $report .= "\n";
        }

        $filename = 'PAY-XUP_'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}
