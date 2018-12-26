<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentWor implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('RCS.WOR')

                ->select(DB::raw("*"))
               
                ->get();
        

        $report = '';
        $count = count($data);

      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'WOR';
        $report .= "\t".'01';
        $report .= "\t".$count;
        $report .= "\n";


        foreach($data as $item) {
            $report .= $item->RecType;
            $report .= "\t" .$item->AcctID;
            $report .= "\t" .$item->AcctNumber;
            $report .= "\t" .$item->Status;
            $report .= "\t" .$item->CloseAndReturn;
            $report .= "\t" .$item->CommRate;
            $report .= "\t" .$item->OtherAssets;
            $report .= "\t" .$item->LastLetterDate;
            $report .= "\t" .$item->LastCallDate;
            $report .= "\t" .$item->LastSkipDate;
            $report .= "\t" .$item->LastGLBNoticeDate;
            $report .= "\n";
           

        }



        $filename = 'ResurgentWor'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}