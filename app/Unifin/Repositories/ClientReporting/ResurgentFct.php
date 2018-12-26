<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentFct implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('RCS.FCT')

                ->select(DB::raw("*"))
               
                ->get();
        

        $report = '';
        $count = count($data);

      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'FCT';
        $report .= "\t".'02';
        $report .= "\t".$count;
        $report .= "\n";


        foreach($data as $item) {
            $report .= $item->RecType;
            $report .= "\t" .$item->ServicerID;
            $report .= "\t" .$item->AsOfDate;
            $report .= "\t" .$item->MTDCollections;
            $report .= "\t" .$item->PostdatesRemaining;
            $report .= "\t" .$item->TotalMTDCollections;
            $report .= "\t" .$item->OldBusinessForecast;
            $report .= "\t" .$item->NewBusinessForecast;
            $report .= "\t" .$item->TotalForecast;
            $report .= "\t" .$item->PlacementType;
            $report .= "\t" .$item->SpecialServicingType;
            $report .= "\t" .$item->MaxNewBusiness;
            $report .= "\t" .$item->FTE;
            $report .= "\n";
           

        }



        $filename = 'ResurgentFct'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}