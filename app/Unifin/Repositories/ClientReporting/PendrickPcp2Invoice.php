<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class PendrickPcp2Invoice implements ReportInterface
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

               
                ->select(DB::raw("DBR_CLI_REF_NO, UDW_FLD20, ADR_SEQ_NO, ADR_NAME, DBR_NAME1, TRS_TRX_DATE_O, TRS_AMT, DBR_COM_RATE, UDW_SEQ, TRS_TRUST_CODE, DBR_NAME1, DBR_CL_MISC_1, TRS_COMM_AMT"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14','21', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? AND UDW_SEQ = ? AND ADR_SEQ_NO = ? AND DBR_CL_MISC_1 = ?', ['PEN%', '0PD', 'R2', 'PENDRICK CAPITAL PARTNERS II'])
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();





        $data = $data->map(function ($item) {
            $item->ADR_NAME = str_replace('#', '', $item->ADR_NAME);

            $item->TRS_TRX_DATE_O = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            
            // $item->TRS_AMT = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            
            /*Agency Payment*/
            if ($item->TRS_TRUST_CODE <> '2') 
                $item->TRS_AMT_AGENCY_PAYMENT = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            else
                $item->TRS_AMT_AGENCY_PAYMENT = [0, 'setFormatCode' => '#,##0.00'];
            
            /*Client Payment*/
            if ($item->TRS_TRUST_CODE == '2') 
               $item->TRS_AMT_CLIENT_PAYMENT = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            else
                $item->TRS_AMT_CLIENT_PAYMENT = [0, 'setFormatCode' => '#,##0.00'];
            
            $item->DBR_COM_RATE = [$item->DBR_COM_RATE, 'setFormatCode' => '#,##0.00'];

            /*Due Client*/
            if ($item->TRS_TRUST_CODE == '2') 
                $item->TRS_COMM_AMT = [$item->TRS_COMM_AMT * -1, 'setFormatCode' => '#,##0.00'];

            else
                $item->TRS_COMM_AMT = [$item->TRS_AMT-$item->TRS_COMM_AMT, 'setFormatCode' => '#,##0.00'];
                // [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];

            $item->TRS_AMT = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            
            $item->trsDue = [$item->TRS_AMT_AGENCY_PAYMENT[0] - $item->TRS_COMM_AMT[0], 'setFormatCode' => '#,##0.00'];
            
            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['Client Account Number', 'Debtor Name', 'Client Master #', 'Batch ID', 'Pay Date', 'Tendered', 'Paid Agency','Paid Client','Comm Rate','Due Agency','Due','Remarks']);
        $columns = collect(['ADR_NAME','DBR_NAME1', 'DBR_CLI_REF_NO', 'UDW_FLD20', 'TRS_TRX_DATE_O', 'TRS_AMT','TRS_AMT_AGENCY_PAYMENT','TRS_AMT_CLIENT_PAYMENT','DBR_COM_RATE','trsDue','TRS_COMM_AMT']);

        $fileName = 'Unifin-Pendrick PCP2 Invoice '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        (new ReportExcel)->setFont('ARIAL', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}