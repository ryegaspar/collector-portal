<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AsgPayFile implements ReportInterface
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
               
                ->select(DB::raw("DBR_NAME1, DBR_ASSIGN_DATE_O, DBR_CLI_REF_NO, ADR_NAME, TRS_TRUST_CODE, ADR_NAME, ADR_SEQ_NO, DBR_CLIENT, UDW_FLD1, TRS_TRUST_CODE, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, UDW_SEQ"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '14', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['ASG%'])
               
               
                ->where('TRS_AMT', '>=', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        

        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            $rtn = [19, 120, 121, 122, 123,  220, 221, 222, 223, 320, 321, 322, 323];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'Payment to Agency';
            
            } else if (in_array($item->TRS_TRUST_CODE, $rtn)){
                $item->TRS_TRUST_CODE = 'Agency Refund';
    
            } else if ($item->TRS_TRUST_CODE == '2'){
                $item->TRS_TRUST_CODE = 'Payment to Client';
            } else {
                $item->TRS_TRUST_CODE = 'Agency NSF';
            }

            $item->ADR_NAME = str_replace('#', '', $item->ADR_NAME);
            $item->DBR_ASSIGN_DATE_O = Carbon::parse($item->DBR_ASSIGN_DATE_O)->format('m/d/Y');
            $item->TRS_TRX_DATE_O = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y ');
            
            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Debtor Name', 'Client File #', 'Loan ID', 'Assign Date', 'Transaction Code', 'Transaction Date', 'Transaction Amount']);
        $columns = collect(['DBR_NAME1', 'ADR_NAME', 'DBR_CLI_REF_NO', 'DBR_ASSIGN_DATE_O', 'TRS_TRUST_CODE', 'TRS_TRX_DATE_O', 'TRS_AMT']);

        $fileName = 'Unifin-ASG Pay File ' . Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        (new ReportExcel)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}