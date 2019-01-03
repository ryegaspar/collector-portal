<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AsgStatus implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                
                ->leftJoin('UFN.UDW_020', 'DBR.DBR_NO', '=', 'UDW_020.UDW_DBR_NO')
                ->Join('CDS.DAT', 'DBR.DBR_NO', '=', 'DAT.DAT_DBR_NO')
                ->leftJoin('CDS.STS', 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
                ->leftJoin('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')
                ->select(DB::raw("DBR_NAME1, DBR_CLI_REF_NO, DBR_ASSIGN_DATE_O, DBR_ASSIGN_AMT, DBR_RECVD_TOT, STS_DESC, DBR_CLIENT, Orig_Acct_No, DBR_STATUS, DBR_CLOSE_DATE_O,  DAT_TYPE, DAT_TRX_DATE_O, UDW_020.UDW_FLD1"))               
                ->whereRaw('DBR_CLIENT LIKE ? ', ['ASG%'])
               
                ->where('DAT_TYPE', 'S')
                
                ->whereBetween('DAT_TRX_DATE_O', [$fromDate, $toDate])
                ->orderBy('DBR_NAME1')
                ->get();



        $data = $data->map(function ($item) {
            $cls = ['SIF', 'PIF', 'XCR'];
            if (in_array($item->DBR_STATUS,$cls)) {
                $item->DBR_STATUS = 'Closed';
            } else {
                $item->DBR_STATUS = 'Open';
            }
            $item->DBR_ASSIGN_DATE_O = Carbon::parse($item->DBR_ASSIGN_DATE_O)->format('m/d/Y');
            $item->DBR_CLOSE_DATE_O = $item->DBR_CLOSE_DATE_O ? Carbon::parse($item->DBR_CLOSE_DATE_O)->format('m/d/Y') : '';
            

            $item->DBR_ASSIGN_AMT = [$item->DBR_ASSIGN_AMT, 'setFormatCode' => '#,##0.00'];
            $item->DBR_RECVD_TOT = [$item->DBR_RECVD_TOT, 'setFormatCode' => '#,##0.00'];
            return $item;
        });




       

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Debtor Name','Client File #','Loan ID', 'Assign Date', 'Close Date', 'Amount Placed', 'Paid', 'Mode', 'Status','Additional Information']);
        
        $columns = collect(['DBR_NAME1', 'Orig_Acct_No', 'DBR_CLI_REF_NO', 'DBR_ASSIGN_DATE_O','DBR_CLOSE_DATE_O','DBR_ASSIGN_AMT', 'DBR_RECVD_TOT','DBR_STATUS', 'STS_DESC','UDW_FLD1']);

        $fileName = 'Unifin-ASG StatusReport '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        (new ReportExcel)->setFont('ARIAL', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}