<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JcapMaintenance implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.DAT', 'DBR.DBR_NO', '=', 'DAT_DBR_NO')
                ->Join('CDS.DNT', function ($join) {
                    $join->on('DAT.DAT_DBR_NO', '=', 'DNT.DNT_DBR_NO');
                    $join->on('DAT.DAT_SEQ_NO', '=', 'DNT.DNT_SEQ_NO');
                })
                ->Join('CDS.ADR', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                    $join->on('ADR_SEQ_NO', '=', DB::raw("'01'"));
                })
                ->Join('CDS.UDW', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'UDW.UDW_DBR_NO');
                    $join->on('UDW_SEQ', '=', DB::raw("'0JC'"));
                })
                ->join('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')

               
                ->select(DB::raw("DBR_CLIENT, DBR_CLI_REF_NO, DBR_CL_MISC_2, DBR_ASSIGN_DATE_O, UDW_FLD1, UDW_SEQ, Orig_Acct_No, DAT_TRX_DATE_O, DNT_NOTE,ADR_SEQ_NO, ADR_ADDR1, ADR_ADDR2, ADR_CITY, ADR_STATE, ADR_ZIP_CODE, ADR_PHONE1, ADR_PHONE2, DBR.DBR_STATUS"))
                


                ->whereNotIn('DBR.DBR_STATUS', ['PIF', 'SIF', 'XCR'])
                
                ->whereRaw('DNT_NOTE LIKE ? AND DBR_CLIENT LIKE ? ', ['Phn1 Chg%','jcap%'])
               
               
                
                ->whereBetween('DAT_TRX_DATE_O', [$fromDate, $toDate])
                ->get();
    

        $report = '';

        foreach($data as $item) {
            $report .= $item->DBR_CLI_REF_NO;
            $report .= $item->UDW_FLD1;
            $report .= $item->DBR_ASSIGN_DATE_O;
            $report .= $item->DBR_CL_MISC_2;
            $report .= "MG";
            $report .= $item->Orig_Acct_No;
            $report .= Carbon::parse($item->DAT_TRX_DATE_O)->format('Ymd');
            $report .= " ";
            $report .= str_replace(',', '', $item->ADR_ADDR1);
            $report .= str_replace(',', '', $item->ADR_ADDR2);
            $report .= $item->ADR_CITY;
            $report .= $item->ADR_STATE;
            $report .= $item->ADR_ZIP_CODE;
            $report .= "USA";
            $report .= "\n";
            
        }



        $filename = 'JcapMaintenance'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

	}
}

