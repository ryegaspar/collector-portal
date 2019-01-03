<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;

class WcrRemit implements ReportInterface
{

    public function generateReport($request) 
    {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('UFN.OriginalAccountNumber', 'OriginalAccountNumber.DBR_NO', '=', 'DBR.DBR_NO')
               
                ->select(DB::raw("DBR_CLIENT, DBR_CLI_REF_NO, Orig_Acct_No, DBR.DBR_NO, DBR_STATUS, TRS_TRX_DATE_O, TRS_AMT, TRS_COMM_AMT, TRS_ENTRY_NO,TRS_BATCH_NO, TRS_TOTAL_DUE, TRS_TRUST_CODE, DBR_CL_MISC_3 "))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['3', '14', '33'])
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['WCR%'])
               
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

        

     


        $data = $data->map(function ($item) {

            $item->record_code = '30';
            $item->fileno = $item->DBR_CLI_REF_NO;
            $item->forw_file = [$item->Orig_Acct_No, 'TYPE_STRING'];
            $item->maco_file = $item->DBR_NO;
            $item->firm_id = '';
            $item->forw_id = '';

            $closure = ['SIF','PIF'];
            if (in_array($item->DBR_STATUS, $closure)){
                $item->ret_code = '3';
            } else {
                $item->ret_code = '1';
            }
            $item->pay_date = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->gross_pay = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->net_client = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->check_amt = [($item->TRS_AMT -$item->TRS_COMM_AMT),'setFormatCode' => '#,##0.00'];
            $item->cost_ret = '';
            $item->fees = [($item->TRS_AMT - $item->TRS_COMM_AMT),'setFormatCode' => '#,##0.00'];
            $item->agent_fees = '';
            $item->forw_cut = '';
            $item->cost_rec = '';
            $item->bpj = 'B';
            $item->ta_no = [($item->TRS_ENTRY_NO),'setFormatCode' => '###0'];
            $item->rmt_no = [($item->TRS_BATCH_NO),'setFormatCode' => '###0'];
            $item->line1_3 = '';
            $item->line1_5 = [$item->TRS_TOTAL_DUE, 'setFormatCode' => '#,##0.00'];;
            $item->line1_6 = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->line2_1 = [$item->TRS_AMT, 'setFormatCode' => '#,##0.00'];
            $item->line2_2 = '';
            $item->line2_5 = '';
            $item->line2_6 = '';
            $item->line2_7 = '';
            $item->line2_8 = [($item->TRS_AMT - $item->TRS_COMM_AMT),'setFormatCode' => '#,##0.00'];
            $item->descr = '';
            $item->post_date = Carbon::parse($item->TRS_TRX_DATE_O)->format('m/d/Y');
            $item->remit_date = Carbon::now()->format('m/d/Y');
            
            if ($item->DBR_STATUS == 'SIF') {
                $item->ta_code = '*CC:A101';
            }
            else if ($item->DBR_STATUS == 'PIF'){
                $item->ta_code = '*CC:A100';
            }
            else {
                $item->ta_code = '*CC:A102';
            }

            $item->comment = ''; 
            $item->originaltanumber = '';
            $item->originalremitnumber = '';
            $item->originalremitdate = '';
            $item->costspentrecoverablefromdebtor = '';
            $item->costspentnonrecoverablefromdebtor = '';
            $item->costspentrecoverablefromclient = '';
            $item->costspentnonrecoverablefromclient = '';  
            $item->debtornumber = '';
   
            return $item;
        });

        $data = collect(json_decode(json_encode($data), true));

        $headers = collect(['record_code', 'fileno', 'forw_file', 'maco_file', 'firm_id','forw_id','ret_code', 'pay_date','gross_pay','net_client','check_amt' , 'cost_ret',  'fees',  'agent_fees', 'forw_cut','cost_rec', 'bpj', 'ta_no','rmt_no','line1_3','line1_5','line1_6', 'line2_1', 'line2_2', 'line2_5', 'line2_6', 'line2_7' ,'line2_8', 'descr','post_date', 'remit_date', 'ta_code', 'comment', 'originaltanumber', 'originalremitnumber', 'originalremitdate', 'costspentrecoverablefromdebtor', 'costspentnonrecoverablefromdebtor', 'costspentrecoverablefromclient', 'costspentnonrecoverablefromclient', 'debtornumber']);
        
        

        $columns = collect(['record_code', 'fileno', 'forw_file', 'maco_file', 'firm_id','forw_id','ret_code', 'pay_date','gross_pay','net_client','check_amt' , 'cost_ret',  'fees',  'agent_fees', 'forw_cut','cost_rec', 'bpj', 'ta_no','rmt_no','line1_3','line1_5','line1_6', 'line2_1', 'line2_2', 'line2_5', 'line2_6', 'line2_7' ,'line2_8', 'descr','post_date', 'remit_date', 'ta_code', 'comment', 'originaltanumber', 'originalremitnumber', 'originalremitdate', 'costspentrecoverablefromdebtor', 'costspentnonrecoverablefromdebtor', 'costspentrecoverablefromclient', 'costspentnonrecoverablefromclient', 'debtornumber']);

        $fileName = 'Unifin-WCR Remit '. Carbon::now()->format('m-d-Y') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        (new ReportExcel)->setFont('Arial', 10)->makeSimpleXlsxFromCollection($data, $fileName, $headers, $columns, $filePath);

    }
}

