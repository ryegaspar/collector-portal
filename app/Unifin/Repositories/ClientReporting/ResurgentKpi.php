<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentKpi implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

      $data = DB::connection('sqlsrv2')
                ->table('RCS.KPI')

                ->select(DB::raw("*"))
               
                ->get();
        

        

        $report = '';
        $count = count($data);

      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'KPI';
        $report .= "\t".'01';
        $report .= "\t".$count;
        $report .= "\n";


        foreach($data as $item) {
            $report .= $item->RecType;
            $report .= "\t".$item->AcctID;
            $report .= "\t".$item->Acctnumber;
            $report .= "\t".$item->RightPartyContact;
            $report .= "\t".$item->CallAttempts;
            $report .= "\t".$item->Connects;
            $report .= "\t".$item->Letters;
            $report .= "\t".number_format($item->LastSIFOffered,2,'.','');
            $report .= "\t".$item->DateofLastSIFOffered;
            $report .= "\n";
        }



        $filename = 'ResurgentKpi'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}