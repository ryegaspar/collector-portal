<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentSufDaily implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $abl = DB::connection('sqlsrv2')
                ->table('RCS.ABL')
                ->select(DB::raw("*"))
                ->get();           

        $bky =  DB::connection('sqlsrv2')
                ->table('RCS.BKY')
                ->select(DB::raw("*"))
                ->get(); 

        $bwr = DB::connection('sqlsrv2')
                ->table('RCS.BWR')
                ->select(DB::raw("*"))
                ->get(); 

        $dec = DB::connection('sqlsrv2')
                ->table('RCS.DEC')
                ->select(DB::raw("*"))
                ->get();

        $pdc = DB::connection('sqlsrv2')
                ->table('RCS.PDC')
                ->select(DB::raw("*")) 
                ->get();

        $wor = DB::connection('sqlsrv2')
                ->table('RCS.WOR')
                ->select(DB::raw("*"))
                ->get();
        
 //FHD Overall File Header
        $report = '';
        $report .= 'FHD';
        $report .= "\t".'01';
        $report .= "\t".'01';
        $report .= "\t".'SUF';
        $report .= "\t".'008357';
        $report .= "\t".'000075';
        $report .= "\t".Carbon::now()->format('m/d/Y');
        $report .= ' 10.23.00';
        $report .= "\t".'UNIFINDAILY';
        $report .= "\n";
 //ABL Data Row Count
        $ablcount = count($abl);
 //ABL Header
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'ABL';
        $report .= "\t".'01';
        $report .= "\t".$ablcount;
        $report .= "\n";
//ABL File Data
        foreach($abl as $item) {
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
//BKY Data Row Count
        $bkycount = count($bky);  
//BKY Header   
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'BKY';
        $report .= "\t".'01';
        $report .= "\t".$bkycount;
        $report .= "\n";
//BKY File Data
        foreach($bky as $itembky) {
            $report .= $itembky->RecType;
            $report .= "\t".$itembky->AcctID;
            $report .= "\t".$itembky->AcctNumber;
            $report .= "\t".$itembky->Chapter;
            $report .= "\t".$itembky->CaseNumber;
            $report .= "\t".$itembky->FileDate;
            $report .= "\t".$itembky->BKStatus;
            $report .= "\t".$itembky->BKStatusDate;
            $report .= "\n";
        }
//BWR Data Row Count
        $bwrcount = count($bwr);
//BWR Header     
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'BWR';
        $report .= "\t".'03';
        $report .= "\t".$bwrcount;
        $report .= "\n";
//BWR File Data
        foreach($bwr as $itembwr) {
            $report .= $itembwr->RecType;
            $report .= "\t" .$itembwr->AcctID;
            $report .= "\t" .$itembwr->AcctNumber;
            $report .= "\t" .$itembwr->BwrSeq;
            $report .= "\t" .$itembwr->SSN;
            $report .= "\t" .$itembwr->FirstName;
            $report .= "\t" .$itembwr->LastName;
            $report .= "\t" .$itembwr->Address;
            $report .= "\t" .$itembwr->Address2;
            $report .= "\t" .$itembwr->City;
            $report .= "\t" .$itembwr->State;
            $report .= "\t" .$itembwr->Zip;
            $report .= "\t" .$itembwr->WrongContactAddress;
            $report .= "\t" .$itembwr->DateofBirth;
            $report .= "\t" .$itembwr->HomePhone;
            $report .= "\t" .$itembwr->WrongContactHomePhone;
            $report .= "\t" .$itembwr->WorkPhone;
            $report .= "\t" .$itembwr->WrongContactWorkPhone;
            $report .= "\t" .$itembwr->OtherPhone;
            $report .= "\t" .$itembwr->WrongContactOtherPhone;
            $report .= "\t" .$itembwr->WirelessPhone;
            $report .= "\t" .$itembwr->WrongContactWirelessPhone;
            $report .= "\t" .$itembwr->LangCode;
            $report .= "\t" .$itembwr->BankName;
            $report .= "\t" .$itembwr->Employed;
            $report .= "\t" .$itembwr->VerifiedHomeowner;
            $report .= "\n";
        }
//DEC Data Row Count
        $deccount = count($dec);
//DEC Header    
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'DEC';
        $report .= "\t".'01';
        $report .= "\t".$deccount;
        $report .= "\n";
//DEC File Data
        foreach($dec as $itemdec) {
            $report .= $itemdec->RecType;
            $report .= "\t".$itemdec->AcctID;
            $report .= "\t".$itemdec->AcctNumber;
            $report .= "\t".$itemdec->BwrSeq;
            $report .= "\t".$itemdec->DateofDeath;
            $report .= "\n";
        }       
//PDC Data Row Count
        $pdccount = count($pdc);
//PDC Header      
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'PDC';
        $report .= "\t".'01';
        $report .= "\t".$pdccount;
        $report .= "\n";
//PDC File Data
        foreach($pdc as $itempdc) {
            $report .= $itempdc->RecType;
            $report .= "\t".$itempdc->AcctID;
            $report .= "\t".$itempdc->AcctNumber;
            $report .= "\t".$itempdc->PaymentType;
            $report .= "\t".$itempdc->PaymentScheduledDate;
            $report .= "\t".$itempdc->PaymentAmount;
            $report .= "\t".$itempdc->StandardEntryClassCode;
            $report .= "\t".$itempdc->ACHAcctType;
            $report .= "\t".$itempdc->BankRoutingNumber;
            $report .= "\t".$itempdc->BankAccountNumber;
            $report .= "\t".$itempdc->CCAcctType;
            $report .= "\n";
        }
 //WOR Data Row Count
        $worcount = count($wor);
 //WOR Header
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'WOR';
        $report .= "\t".'01';
        $report .= "\t".$worcount;
        $report .= "\n";
//WOR File Data
        foreach($wor as $item) {
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



//File Creation and Naming
        $filename = 'SUF_008357_01_'.Carbon::now()->format('Ymd').'_102300';
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}