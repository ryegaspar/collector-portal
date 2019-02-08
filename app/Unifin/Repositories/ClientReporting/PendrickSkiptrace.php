<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PendrickSkiptrace implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.ADR', 'DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('CDS.UDW', 'DBR.DBR_NO', '=', 'UDW.UDW_DBR_NO')

                ->select(DB::raw(" DBR_CLI_REF_NO, UDW_SEQ, DBR_NAME1, DAT_TYPE, DAT_TRX_DATE_O, DBR_CL_MISC_1, DNT_NOTE, ADR_ZIP_CODE, ADR_SEQ_NO, ADR_PHONE1"))
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '21', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? AND UDW_SEQ = ? AND ADR_SEQ_NO = ? AND DBR_CL_MISC_1 = ?', ['PEN%', '0PD', 'R2', 'PENDRICK CAPITAL PARTNERS'])
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

       

        $report = '';


        foreach($data as $item) {
            $report .= '09';
            $report .= $item->DBR_CLI_REF_NO;
            $report .= $item->ADR_PHONE1;
            $report .= $item->DBR_NAME1;

            if (strpos($item->DNT_NOTE, 'Addr1 Chg:*') !== false) {
            echo 'true';
                    }
            
            $report .='N';
            $report .='0';
            $report .='N';
            $report .=$item->DAT_TRX_DATE_O;
            $report .=$item->DAT_TRX_DATE_O;
            $report .='0';
            $report .='0';
            $report .='0';

            if (strpos($item->DNT_NOTE, 'Addr1 Chg:*') !== false) {
                substr($item->ADR_ZIP_CODE,1,5);
            } else {
                $item->ADR_ZIP_CODE = ''
            }

           

            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .='N';
            $report .='0';
            $report .='N';
            $report .=' ';
            $report .=' ';
            $report .='0';
            $report .='0';
            $report .='0';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .='N';
            $report .='0';
            $report .='N';
            $report .=' ';
            $report .=' ';
            $report .='0';
            $report .='0';
            $report .='0';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .='N';
            $report .='0';
            $report .='N';
            $report .=' ';
            $report .=' ';
            $report .='0';
            $report .='0';
            $report .='0';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .=' ';
            $report .='N';
            $report .='0';
            $report .='N';
            $report .=' ';
            $report .=' ';
            $report .='0';
            $report .='0';
            $report .='0';
            $report .=' ';
            $report .=' ';
   






            $report .= "\n";
        }

        $filename = 'UNIFIN_pmt_out_main_'.Carbon::now()->format('ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}

