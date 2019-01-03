<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PendrickIndirectPaymentsPcp2 implements ReportInterface
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
                ->select(DB::raw("DBR_CLI_REF_NO, DBR_CL_MISC_3, UDW_FLD19, ADR_NAME, UDW_FLD18, TRS_TRX_DATE_O, TRS_AMT, DBR_STATUS, DBR_COM_RATE, DBR_PRINCIPAL_DUE"))
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '21', '33'])
                ->whereRaw('DBR_CLIENT LIKE ? AND UDW_SEQ = ? AND ADR_SEQ_NO = ? AND DBR_CL_MISC_1 = ?', ['PEN%', '0PD', 'R2', 'PENDRICK CAPITAL PARTNERS II'])
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        $report = '';

        foreach($data as $item) {
            $report .= str_pad('70'.$item->DBR_CLI_REF_NO, 18, ' ');
            $report .= $item->DBR_CL_MISC_3;
            $report .= $item->UDW_FLD19;
            $report .= str_pad(str_replace('#', '', $item->ADR_NAME), 20, ' ');
            $report .= $item->UDW_FLD18;
            $report .= Carbon::parse($item->TRS_TRX_DATE_O)->format('mdY');
            $report .= 'UN1';
  
            $report .= sprintf("%010d", $item->TRS_AMT * 100);
           

            if ($item->DBR_STATUS == 'SIF') 
                $report .= '15  ';
            else
                $report .= '11  ';
            $report .= sprintf("%06d", $item->DBR_COM_RATE * 100);
            $report .= str_pad('', 48, ' ');
            
            if ($item->DBR_PRINCIPAL_DUE>0)
                $report .= sprintf("%010d", $item->DBR_PRINCIPAL_DUE * 100);
            else
                $report .= sprintf("%09d", $item->DBR_PRINCIPAL_DUE * 100);
            
            $report .= str_pad('', 82, ' ');
            $report .= "\n";

        }

        $filename = 'UNIFIN_pmt_out_pcp2_'.Carbon::now()->format('ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        return redirect()->back()->with('status', 'Success!');
    }


}
    