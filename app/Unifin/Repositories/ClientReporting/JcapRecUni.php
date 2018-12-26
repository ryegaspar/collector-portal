<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JcapRecUni implements ReportInterface
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
                ->join('CDS.CLT', 'DBR.DBR_CLIENT', '=', 'CLT.CLT_NO')
               
                ->select(DB::raw("DBR_CLI_REF_NO, UDW_FLD1, DBR_ASSIGN_DATE_O, DBR_CL_MISC_2, ADR_NAME, DBR_NAME1, TRS_TRX_DATE_O, TRS_TRUST_CODE, TRS_AMT, TRS_COMM_AMT, ADR_SEQ_NO, UDW_SEQ"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '14', '33'])
                
                ->whereRaw('CLT_NAME_1 LIKE ? AND UDW.UDW_SEQ = ?', ['Jefferson Capital Systems, LLC', '0JC'])
               
               
                ->where('TRS_AMT', '<>', 0)
                
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        

        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            $rtn = [19, 120, 121, 122, 123, 220, 221, 222, 223, 320, 321, 322, 323];

            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'PY';
            } else if (in_array($item->TRS_TRUST_CODE, $rtn)){
                $item->TRS_TRUST_CODE = 'RC';
            } else {
                $item->TRS_TRUST_CODE = 'RC';
            }

            if (number_format($item->TRS_AMT, 2, '.','')<0){
                $item->NEGATIVE = '-';
            } else {
                $item->NEGATIVE = '';
            }

            return $item;
        });
  

        $report = '';
        $report = 'HDR'.Carbon::now()->format('Ymd').'UNI';
        $report .= "\n";

        foreach($data as $item) {
            $report .= str_pad($item->DBR_CLI_REF_NO, 10, ' ');
            $report .= str_pad($item->UDW_FLD1, 4, ' ');
            $report .= Carbon::parse($item->DBR_ASSIGN_DATE_O)->format('Ymd');
            $report .= str_pad($item->DBR_CL_MISC_2, 12, ' ');
            $report .= str_pad('PY',2,' ');
            $report .= str_pad(str_replace('#', '', $item->ADR_NAME),24,' ',STR_PAD_LEFT);
            $report .= str_pad(' ', 16, ' ');;
            $report .=' ';
            $report .=' ';
            $report .='UNI';
            $report .= str_pad($item->DBR_NAME1, 30, ' ');
            $report .= Carbon::parse($item->TRS_TRX_DATE_O)->format('Ymd');
            $report .='A';
            $report .='4';
            $report .= str_pad($item->TRS_TRUST_CODE, 2, ' ');   
            $report .= str_pad(str_replace('-','',number_format($item->TRS_AMT,2)),10,' ',STR_PAD_LEFT);
            $report .= str_pad($item->NEGATIVE,1,' ');
            $report .= str_pad(str_replace('-','',number_format($item->TRS_COMM_AMT,2)),10,' ',STR_PAD_LEFT);
            $report .= str_pad($item->NEGATIVE,1,' ');
            $report .= str_pad(Carbon::now()->format('Ymd'),8,' ', STR_PAD_LEFT);
            $report .= "\n";
        }

        $filename = 'JCAPTest'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}
