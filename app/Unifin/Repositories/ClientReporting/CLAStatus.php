<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClaStatus implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->Join('CDS.UDW', 'DBR.DBR_NO', '=', 'UDW.UDW_DBR_NO')
                ->Join('CDS.DAT', 'DBR.DBR_NO', '=', 'DAT.DAT_DBR_NO')
                ->leftJoin('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')
                
                ->select(DB::raw("UDW_FLD1, DBR_ASSIGN_AMT, DBR_CLI_REF_NO, Orig_Acct_No, DAT_ACTION_CODE, DAT.DAT_TRX_DATE_O, 
                    UDW.UDW_SEQ, DBR.DBR_CLIENT"))               
                
                ->whereRaw('DBR_CLIENT LIKE ? AND UDW_SEQ = ? ', ['CLA%','OCL'])
               
                ->whereIn('DAT_ACTION_CODE', ['DNC', 'GET', 'PIF', 'PPA', 'SIF'])

                ->whereBetween('DAT_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

               

        $data = $data->map(function ($item) {
           
            if ($item->DAT_ACTION_CODE == 'SIF') {
                $item->DAT_ACTION_CODE = 'ASIF';
            } else if ($item->DAT_ACTION_CODE == 'PIF'){
                $item->DAT_ACTION_CODE = 'APIF';
            } else if ($item->DAT_ACTION_CODE == 'PPA'){
                $item->DAT_ACTION_CODE = 'APPA';
            } else if ($item->DAT_ACTION_CODE == 'GET'){
                $item->DAT_ACTION_CODE = 'ARTP';
            } else {
                $item->DAT_ACTION_CODE = 'ARTP';
            }

            return $item;
        });

        $report = '';

            foreach($data as $item) {
                $report .= 'XUP';
                $report .= $item->UDW_FLD1;
                $report .= $item->DBR_ASSIGN_DATE_O;
                $report .= $item->DBR_CLI_REF_NO;
                $report .= $item->Orig_Acct_No;
                $report .= $item->DAT_ACTION_CODE;
                $report .= "\n";
            }


        $filename = 'STA-XUP_'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}
