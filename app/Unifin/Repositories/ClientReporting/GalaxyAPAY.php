<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GalaxyAPAY implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.ADR', 'DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')

                ->select(DB::raw("DBR_NO, DBR_CLI_REF_NO, ADR_NAME, TRS_AMT, TRS_TRX_DATE_O, TRS_SEQ_NO, TRS_SEQ_SUB, DBR_STATUS, DBR_COM_RATE, DBR_PRINCIPAL_DUE"))
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '21', '33'])
                ->whereRaw('DBR_CLIENT LIKE ? AND ADR_SEQ_NO = ?', ['GXY%', 'R2'])
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

       

        $report = '';
        $count = count($data);
        $trs = [];

        foreach($data as $transaction) {
            $trs[] = $transaction->TRS_AMT;
        }




        foreach($data as $item) {
            $report .= 'APAY';
            $report .= '|'.$item->DBR_CLI_REF_NO;
            $report .= '|'.str_replace('#', '', $item->ADR_NAME);
            $report .= '|'.$item->TRS_AMT;
            $report .= '|'.Carbon::parse($item->TRS_TRX_DATE_O)->format('Ymd');
            
            if ($item->TRS_AMT < 0) 
                $report .= '|'.'PAR';
            else
                $report .= '|'.'PA';
            $report .= '|'.$item->DBR_NO.'-'.$item->TRS_SEQ_NO.'-'.$item->TRS_SEQ_SUB;
            $report .= '|'.'';
            $report .='\n';
        }
        $report .='TRL';
        $report .='|UNIFIN';
        $report .='|'.$count;
        $report .='|'.number_format(array_sum($trs),2,'.','');
        $report .='|'.Carbon::now()->format('Ymd');


        $filename = 'AIM'.Carbon::now()->format('YmdHis').'UNI';
        $filePath = public_path('storage\\reports\\'. $filename .'.APAY');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}

