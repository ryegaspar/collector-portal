<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResurgentSufMonthly implements ReportInterface
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

        $kpi = DB::connection('sqlsrv2')
                ->table('RCS.KPI')
                ->select(DB::raw("*"))      
                ->get();

         $pdc = DB::connection('sqlsrv2')
                ->table('RCS.PDC')
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
        $report .= ' 16.23.00';
        $report .= ' UNIFIN';
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
    //KPI Data Row Count
        $kpicount = count($kpi);
    //KPI Header       
        $report .= 'RHD';
        $report .= "\t".'01';
        $report .= "\t".'KPI';
        $report .= "\t".'01';
        $report .= "\t".$kpicount;
        $report .= "\n";
    //KPI File Data
        foreach($kpi as $itemkpi) {
            $report .= $itemkpi->RecType;
            $report .= "\t".$itemkpi->AcctID;
            $report .= "\t".$itemkpi->Acctnumber;
            $report .= "\t".$itemkpi->RightPartyContact;
            $report .= "\t".$itemkpi->CallAttempts;
            $report .= "\t".$itemkpi->Connects;
            $report .= "\t".$itemkpi->Letters;
            $report .= "\t".number_format($itemkpi->LastSIFOffered,2,'.','');
            $report .= "\t".$itemkpi->DateofLastSIFOffered;
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


        $filename = 'SUF_008357_01_'.Carbon::now()->format('Ymd').'_162300Month';
        $filePath = public_path('storage\\reports\\'. $filename .'.txt');
        $handle = fopen($filePath, 'w');
        fwrite($handle, $report);

        $reportContents = file_get_contents($filePath);

	}
}