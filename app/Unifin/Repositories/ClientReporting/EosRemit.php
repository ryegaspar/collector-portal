<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EosRemit implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
               
                ->select(DB::raw("DBR_CLI_REF_NO, TRS_TRUST_CODE, TRS_TRX_DATE_O, TRS_AMT, DBR_STATUS, TRS_COMM_AMT, DBR_CLIENT"))
                
                ->whereRaw('DBR_CLIENT LIKE ? ', ['EOS%'])
               
                ->where('TRS_AMT', '<>', 0)
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();
            

        

        $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303, 400, 401, 402, 403];
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'FOR';
            } else {
                $item->TRS_TRUST_CODE = 'NSF';
            }

            $sts = ['SIF','PIF'];
            if (in_array($item->DBR_STATUS,$sts)) {
                $item->DBR_STATUS = $item->DBR_STATUS;
            } else {
                $item->DBR_STATUS = 'PTL';
            }

            $item->NetRemittance = ($item->TRS_AMT - $item->TRS_COMM_AMT);
            $item->FeePercent = round(($item->TRS_COMM_AMT/$item->TRS_AMT)*100);

            return $item;
        });

        $report = '';

        foreach($data as $item) {
            $report .= str_pad('', 6, ' ');
            $report .= str_pad($item->DBR_CLI_REF_NO,23,' ');
            $report .= str_pad($item->TRS_TRUST_CODE,3, ' ');
            $report .= str_pad(Carbon::parse($item->TRS_TRX_DATE_O)->format('mdY'),8,' ');
            $report .= str_pad(number_format($item->TRS_AMT,2),8, ' ',STR_PAD_LEFT);
            $report .= "CC";
            $report .= str_pad('', 15, ' ');
            $report .= str_pad($item->DBR_STATUS,3,' ');
            $report .= "00000.00";
            $report .= "00000.00";
            $report .= str_pad(number_format($item->TRS_COMM_AMT,2),8, ' ',STR_PAD_LEFT);
            $report .= str_pad(number_format($item->NetRemittance,2),8, ' ',STR_PAD_LEFT);
            $report .= str_pad(number_format($item->FeePercent,2),4, ' ',STR_PAD_LEFT);
            $report .= "\n";
        }


        

        $filename = 'UNI-PMT_'.Carbon::now()->format('mdy');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);
    }
}