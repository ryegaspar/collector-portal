<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class LtdRemit implements ReportInterface
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
               
                ->select(DB::raw("DBR_CLI_REF_NO, ADR_SEQ_NO, ADR_NAME, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, TRS_TRUST_CODE, DBR_STATUS, DBR_CLIENT"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['ltd%'])
               
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();





        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'Agency Wire';
    
            } else if ($item->TRS_TRUST_CODE == '2'){
                $item->TRS_TRUST_CODE = 'Client Wire';
            } else {
                $item->TRS_TRUST_CODE = 'Agency Refund';
            }
            $report .= $item->DBR_CLI_REF_NO;
            $report .= str_replace('#', '', $item->ADR_NAME);
            $report .= '';
            $report .= '';
            $report .= $item->TRS_TRX_DATE_O = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y ');
            $report .= str_pad(number_format($item->TRS_AMT,2),9,' ');
            $report .= str_pad(number_format($item->TRS_COMM_AMT,2),9,' ');
            
            
            if ($item->TRS_TRUST_CODE == 'Client Wire') 
                $report .= number_format($item->TRS_AMT,2) ;
            else
                $report .= '11  ';
            $report .= '';
            
            $report .= $item->TRS_TRUST_CODE;
            
            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Account ID', 'ClientAccountID', 'IssuerAccountNumber', 'Check Number', 'Date', 'DebtorPayment', 'ContingencyFee','Remitted','Notes','PaymentType']);
        $columns = collect(['DBR_NAME1', 'ADR_NAME', 'DBR_CLI_REF_NO', 'DBR_ASSIGN_DATE_O', 'TRS_TRUST_CODE', 'TRS_TRX_DATE_O', 'TRS_AMT']);

        $fileName = 'Unifin-LTD Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        (new ReportExcel)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}