<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentDec implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

      $data = DB::connection('sqlsrv2')
                ->table('RCS.DEC')

                ->select(DB::raw("*"))
               
                ->get();
        

        

        $report = '';
        $count = count($data);

      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'DEC';
        $report .= "\t".'01';
        $report .= "\t".$count;
        $report .= "\n";


        foreach($data as $item) {
            $report .= $item->RecType;
            $report .= "\t".$item->AcctID;
            $report .= "\t".$item->AcctNumber;
            $report .= "\t".$item->BwrSeq;
            $report .= "\t".$item->DateofDeath;
            $report .= "\n";
        }



        $filename = 'ResurgentDec'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}