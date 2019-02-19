<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FfamPlacementAcknowledgement implements ReportInterface
{

    public function generateReport($request)
     {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->leftJoin('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')

                ->select(DB::raw("DBR_CLI_REF_NO, Orig_Acct_No, DBR_ASSIGN_AMT, DBR_ASSIGN_DATE_O, DBR_PRINCIPAL_DUE, 
                    ISNULL(DBR_LAST_TRUST_DATE_O, '') as DBR_LAST_TRUST_DATE_O, DBR_LAST_TRUST_AMT"))

                
                ->whereRaw('DBR_CLIENT LIKE ?', ['FAM%'])
               
    
                ->whereBetween('DBR_ASSIGN_DATE_O', [$fromDate, $toDate])
                ->get();



        
        $report = '';
        $recordCount = count($data);
        $sumAssignAmt = [];
        $sumPrincipleDueAmt = [];

        foreach($data as $AssignAmt) {
            $sumAssignAmt[] = $AssignAmt->DBR_ASSIGN_AMT;
        }

        foreach($data as $PrincipleDueAmt) {
            $sumPrincipleDueAmt[] = $PrincipleDueAmt->DBR_PRINCIPAL_DUE;
        }


        foreach($data as $item) {
            $report .= 'AACK';
            $report .= "|".$item->DBR_CLI_REF_NO;
            $report .= "|".$item->Orig_Acct_No;
            $report .= "|".number_format($item->DBR_ASSIGN_AMT,2,'.','');
            $report .= "|".Carbon::parse($item->DBR_ASSIGN_DATE_O)->format('Ymd');
            $report .= "|".number_format($item->DBR_PRINCIPAL_DUE,2,'.','');
            $report .= "|".str_replace('19000101', '', Carbon::parse($item->DBR_LAST_TRUST_DATE_O)->format('Ymd'));
            $report .= "|".number_format($item->DBR_LAST_TRUST_AMT,2,'.','');
            $report .= "|";
            $report .= "\n";

        }

            $report .= 'ATRL';
            $report .= "|".'369';
            $report .= "|".'Unifin';
            $report .= "|".$recordCount;
            $report .= "|".number_format(array_sum($sumAssignAmt),2,'.','');
            $report .= "|".number_format(array_sum($sumPrincipleDueAmt),2,'.','');
            $report .='|'.Carbon::now()->format('Ymd');


           

        $filename = 'AIM'.Carbon::now()->format('Ymd').'120000_369';
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}
