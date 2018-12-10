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
                ->leftJoin('CDS.ADR', function ($join) {
                    $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                    $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
                })

                // Jcap MT file

                ->join('CDS.ADR', 'DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO')
                ->join('CDS.UDW', 'DBR.DBR_NO', '=', 'UDW.UDW_DBR_NO')
                ->join('CDS.CHK', 'DBR.DBR_NO', '=', 'CHK.CHK_DBR_NO')
                ->join('CDS.ADR_1', 'DBR.DBR_NO', '=', 'ADR_DBR_NO')
                ->join('CDS.ADR_1', 'DBR.DBR_NO', '=', 'ADR_DBR_NO')
                ->join('CDS.DNT')
               
                ->select(DB::raw("CHK_POST_DATE_O, DBR_CLIENT, DBR_CLI_REF_NO, DBR_CL_MISC_2, DBR_ASSIGN_DATE_O, UDW_FLD1, UDW_SEQ, ADR_NAME, ADR_SEQ_NO, DAT_TRX_DATE_O, DNT_NOTE, ADR_1.ADR_SEQ_NO, ADR_1.ADR_PHONE1, ADR_1.ADR_PHONE2, DBR.DBR_STATUS"))
                


                ->whereNotIn('DBR_STATUS', ['PIF', 'SIF', 'XCR'])
                
                ->whereRaw('ADR_1.ADR_SEQ_NO LIKE ? AND DNT_NOTE LIKE ? AND UDW_SEQ LIKE ? AND DBR_CLIENT LIKE ? ', ['01','Phn1 Ch%' or 'Phn2 Chg%','0JC','jcap%'])
               
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();
        

        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303, 400, 401, 402, 403];
            $rtn = [19, 120, 121, 122, 123,  220, 221, 222, 223, 320, 321, 322, 323];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'AGPD';
            
            } else if (in_array($item->TRS_TRUST_CODE, $rtn)){
                $item->TRS_TRUST_CODE = 'AGCOR';
    
            } else if ($item->TRS_TRUST_CODE == '2'){
                $item->TRS_TRUST_CODE = 'DPS';
            } else {
                $item->TRS_TRUST_CODE = 'AGNSF';
            }

            return $item;
        });

        $report = '';

        foreach($data as $item) {
            $report .= $item->DBR_CLI_REF_NO;
            $report .= $item->UDW_FLD1;
            $report .= $item->DBR_ASSIGN_DATE_O;
            $report .= $item->DBR_CL_MISC_2;
            $report .= "MG";
            $report .= str_replace('#', '', $item->ADR_NAME);
            $report .= Carbon::parse($item->TRS_TRX_DATE_O)->format('Ymd');


            $report .= str_replace(',', '', $item->ADR_ADDR1);
            $report .= str_replace(',', '', $item->ADR_ADDR2);
            $report .= $item->ADR_CITY;
            $report .= $item->ADR_STATE;
            $report .= $item->ADR_ZIP_CODE;
            $report .= "USA";
            $report .= "\n";






            $report .= "\tUNIFIN";
            $report .= "\t".$item->DBR_NO;
            $report .= "\t".$item->TRS_TRUST_CODE;
            $report .= "USA";
            $report .= "\t".Carbon::parse($item->TRS_TRX_DATE_O)->format('Ymd');
            $report .= "\t";
            $report .= "\t $".number_format($item->DBR_PRINCIPAL_DUE,2);
            $report .= "\t $".number_format($item->TRS_AMT,2);
            $report .= "\t $".number_format($item->TRS_COMM_AMT,2);
            $report .= "\t\t".$item->TRS_DBR_NO.'-'. $item->TRS_SEQ_NO;
            
        }


        $headers = ['Capio Account Number', 'Provider System Account Number', 'Vendor', 'Unique Identifier to Vendor', 'Payment Type', 'Effective Date', 'Reference','Current Balance After Transaction',  'Transaction Amount',  'Fee', 'Reversal Unique Key', 'Billing ID'];
        $columns = collect(['DBR_CLI_REF_NO', 'ADR_NAME', ' ', 'DBR_NO', 'TRS_TRUST_CODE', 'TRS_TRX_DATE_O',' ','DBR_PRINCIPAL_DUE', 'TRS_AMT','TRS_COMM_AMT',' ',' ']);

        $filename = 'Unifin.PAY.'.Carbon::now()->format('Ymd').'.0923';
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);
/*Adds headers to */
        file_put_contents($filePath, implode("\t", $headers). "\n" . $reportContents);
	}
}

