<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentBwr implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('RCS.BWR')

                ->select(DB::raw("*"))
               
                ->get();
        

        $report = '';
        $count = count($data);

      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'BWR';
        $report .= "\t".'03';
        $report .= "\t".$count;
        $report .= "\n";


        foreach($data as $item) {
            $report .= $item->RecType;
            $report .= "\t" .$item->AcctID;
            $report .= "\t" .$item->AcctNumber;
            $report .= "\t" .$item->BwrSeq;
            $report .= "\t" .$item->SSN;
            $report .= "\t" .$item->FirstName;
            $report .= "\t" .$item->LastName;
            $report .= "\t" .$item->Address;
            $report .= "\t" .$item->Address2;
            $report .= "\t" .$item->City;
            $report .= "\t" .$item->State;
            $report .= "\t" .$item->Zip;
            $report .= "\t" .$item->WrongContactAddress;
            $report .= "\t" .$item->DateofBirth;
            $report .= "\t" .$item->HomePhone;
            $report .= "\t" .$item->WrongContactHomePhone;
            $report .= "\t" .$item->WorkPhone;
            $report .= "\t" .$item->WrongContactWorkPhone;
            $report .= "\t" .$item->OtherPhone;
            $report .= "\t" .$item->WrongContactOtherPhone;
            $report .= "\t" .$item->WirelessPhone;
            $report .= "\t" .$item->WrongContactWirelessPhone;
            $report .= "\t" .$item->LangCode;
            $report .= "\t" .$item->BankName;
            $report .= "\t" .$item->Employed;
            $report .= "\t" .$item->VerifiedHomeowner;
            $report .= "\n";
           

        }



        $filename = 'ResurgentBwr'.Carbon::now()->format('Ymd');
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}