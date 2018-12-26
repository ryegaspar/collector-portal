<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentAbl implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

      $data = DB::connection('sqlsrv2')
                ->table('RCS.ABL')

                ->select(DB::raw("*"))
               
                ->get();
        

        

        $report = '';
        $count = count($data);

      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'ABL';
        $report .= "\t".'01';
        $report .= "\t".$count;
        $report .= "\n";


        foreach($data as $item) {
            $report .= $item->RecType;
            $report .= "\t".$item->AcctID;
            $report .= "\t".$item->Acctnumber;
            $report .= "\t".number_format($item->PrinBal,2,'.','');
            $report .= "\t".number_format($item->IntBal,2,'.','');
            $report .= "\t".number_format($item->CostBal,2,'.','');
            $report .= "\t".number_format($item->FeeBal,2,'.','');
            $report .= "\t".$item->LastPayDate;
            $report .= "\t".number_format($item->LastPayAmt,2,'.','');
            $report .= "\n";
        }



        $filename = 'ResurgentAbl'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}