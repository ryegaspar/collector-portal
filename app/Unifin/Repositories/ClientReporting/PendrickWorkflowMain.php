<?php

namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PendrickWorkflowMain implements ReportInterface
{
	public function generateReport($request) {

		$fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')
                ->join('CDS.DAT', 'DBR.DBR_NO', '=', 'DAT.DAT_DBR_NO')
                ->join('CDS.CLT', 'DBR.DBR_CLIENT', '=', 'CLT.CLT_NO')
                ->join('CDS.020_UDW', 'DBR.DBR_NO', '=', 'UDW_DBR_NO')
                ->join('CDS.SOL_CRTF', 'DBR.DBR_NO', '=', 'DebtorNumber')
                ->join('CDS.01_ADR', 'DBR.DBR_NO', '=', '01_ADR.DBR_NO')
                
                ->select(DB::raw("DAT.DAT_TRX_DATE_O, 
                                DAT.DAT_ACTION_CODE, 
                                DBR.DBR_NAME1, 
                                DBR.DBR_CLI_REF_NO, 
                                DBR.DBR_CLIENT, 
                                020_UDW.UDW_FLD1, 
                                020_UDW.UDW_FLD21, 
                                020_UDW.UDW_FLD2, 
                                020_UDW.UDW_FLD23, 
                                DBR.DBR_ASSIGN_DATE_O,
                                020_UDW.UDW_FLD25, 
                                020_UDW.UDW_FLD27, 
                                020_UDW.UDW_FLD17, 
                                020_UDW.UDW_FLD18, 
                                020_UDW.UDW_FLD20, 
                                020_UDW.UDW_FLD14, 
                                01_ADR.ADR_ADDR1, 
                                01_ADR.ADR_STATE, 
                                01_ADR.ADR_ZIP_CODE, 
                                DBR.DBR_CL_MISC_1, 
                                DAT.DAT_PHONE_NO, 
                                SOL_CRTF.StatuteOfLimitations"))
                
                ->whereRaw('DBR_CLIENT LIKE ? AND DBR_CL_MISC_1 = ? ', ['PEN%','PENDRICK CAPITAL PARTNERS'])
               
                ->whereIn('DAT_ACTION_CODE', ['017', 'CR1', 'CR2', 'CR3','CR4', 'CR5', 'CR6', 'DNC','IB1', 'IB2', 'LTR', 'XCR'])
                
                ->whereBetween('DAT.DAT_TRX_DATE_O', [$fromDate, $toDate])
                ->get();
            

        

        $data = $data->map(function ($item) {
            $fullName = explode(','$item->DBR_NAME1)
            $lastName = $fullName[0]
            $firstName = $fullName[1]

            if  ($item->DAT_ACTION_CODE = 'LTR' AND $item->StatuteOfLimitations = 'OOS'){ 
                $item->DAT_ACTION_CODE = '4502';
            } else if ($item->DAT_ACTION_CODE = 'IB1'){
                $item->DAT_ACTION_CODE = '1010';
            } else if ($item->DAT_ACTION_CODE = 'IB2'){
                $item->DAT_ACTION_CODE = '1015';
            } else if ($item->DAT_ACTION_CODE = 'CR1'){
                $item->DAT_ACTION_CODE = '2000';
            } else if ($item->DAT_ACTION_CODE = 'CR2'){
                $item->DAT_ACTION_CODE = '2001';
            } else if ($item->DAT_ACTION_CODE = 'CR3'){
                $item->DAT_ACTION_CODE = '2003';
            } else if ($item->DAT_ACTION_CODE = 'CR4'){
                $item->DAT_ACTION_CODE = '2002';
            } else if ($item->DAT_ACTION_CODE = 'CR5'){
                $item->DAT_ACTION_CODE = '2005';
            } else if ($item->DAT_ACTION_CODE = 'CR6'){
                $item->DAT_ACTION_CODE = '2006';
            } else if ($item->DAT_ACTION_CODE = '017'){
                $item->DAT_ACTION_CODE = '4505';
            } else if ($item->DAT_ACTION_CODE = 'XCR'){
                $item->DAT_ACTION_CODE = '5510';
            } else {
                $item->DAT_ACTION_CODE = '';
                




            


   IF ({DAT.DAT_ACTION_CODE} = 'DNC'
        and {020_UDW.UDW_FLD1} = 'Validation Request'
        and {020_UDW.UDW_FLD21} = 'General Valid. Request') 
    THEN '1000'
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC'
        and {020_UDW.UDW_FLD1} = 'Validation Request'
        and {020_UDW.UDW_FLD21} = 'Itemized Bill Request')
    THEN '1000'
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC'
        and {020_UDW.UDW_FLD1} = 'Validation Request'
        and {020_UDW.UDW_FLD21} = 'Non-Itemized Bill Request')
    THEN '1001'
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC'
        and {020_UDW.UDW_FLD1} = 'Validation Request')
    THEN '1000'
    ELSE
//
//Credit Bureau Reporting (CBR) Section
//
//
//Dispute Section - Account Balance Incorrect
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Account Balance Incorrect'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3023' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Account Balance Incorrect'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3024' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Account Balance Incorrect'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3021' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Account Balance Incorrect'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3022' 
    ELSE
//
//Dispute Section - Bad Service Provided
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Bad Service Provided'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3015' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Bad Service Provided'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3016' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Bad Service Provided'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3013' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Bad Service Provided'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3014' 
    ELSE
//
//Dispute Section - Charity Care
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Charity Care'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3031' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Charity Care'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3032' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Charity Care'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3029' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Charity Care'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3030' 
    ELSE
//
//Dispute Section - General
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'General'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3003' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'General'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3004' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'General'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3001' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'General'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3002' 
    ELSE
//
//Dispute Section - Insurance Claim Error
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Insurance Claim Error'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3027' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Insurance Claim Error'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3028' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Insurance Claim Error'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3025' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Insurance Claim Error'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3026' 
    ELSE
//
//Dispute Section - Multiple Reasons
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Multiple Reasons'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3051' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Multiple Reasons'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3052' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Multiple Reasons'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3049' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Multiple Reasons'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3050' 
    ELSE
//
//Dispute Section - Never Recieved Service
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Never Recieved Service'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3011' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Never Recieved Service'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3012' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Never Recieved Service'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3009' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Never Recieved Service'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3010' 
    ELSE
//
//Dispute Section - Date of Service Wrong
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Date of Service Wrong'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3007' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Date of Service Wrong'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3008' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Date of Service Wrong'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3005' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Date of Service Wrong'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3006' 
    ELSE
//
//Dispute Section - Not Responsible for Bill
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Not Responsible for Bill'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3039' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Not Responsible for Bill'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3040' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Not Responsible for Bill'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3037' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Not Responsible for Bill'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3038' 
    ELSE
//
//Dispute Section - Paid Prior
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Paid Prior'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3035' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and {020_UDW.UDW_FLD23} = 'Paid Prior'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3036' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Paid Prior'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3033' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} = 'Paid Prior'
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3034' 
    ELSE
//
//Dispute Section - Other
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written'
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3055' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD2} = 'Written' 
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3056' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and currentdate<=({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3053' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and currentdate>({DBR.DBR_ASSIGN_DATE_O}+30)) 
    THEN '3054' 
    ELSE
//
//Cease & Desist Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Cease & Desist' 
        and {020_UDW.UDW_FLD2} = 'Written') 
    THEN '4002' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Cease & Desist') 
    THEN '4001' 
    ELSE
//
//Fraud Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Fraud Claim' 
        and {020_UDW.UDW_FLD2} = 'Written') 
    THEN '4051' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Fraud Claim') 
    THEN '4050' 
    ELSE
//
//Complaint Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Complaint' 
        and {020_UDW.UDW_FLD25} = 'Agency Complaint') 
    THEN '5001' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Complaint' 
        and {020_UDW.UDW_FLD25} = 'Collector Complaint') 
    THEN '5001' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Complaint' 
        and {020_UDW.UDW_FLD25} = 'Orig. Creditor Complaint') 
    THEN '5002' 
    ELSE
//
//Regulatory Complaint Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Complaint' 
        and {020_UDW.UDW_FLD25} = 'Regulatory (Admin Only)') 
    THEN '5103' 
    ELSE
//
//Attorney and Lawsuit Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Attorney Handles' 
        and {020_UDW.UDW_FLD27} = 'Single Plaintiff Letter') 
    THEN '5201' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Attorney Handles' 
        and {020_UDW.UDW_FLD27} = 'Class Action Letter') 
    THEN '5202' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Attorney Handles' 
        and {020_UDW.UDW_FLD27} = 'Single Plaintiff Lawsuit') 
    THEN '5301' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Attorney Handles' 
        and {020_UDW.UDW_FLD27} = 'Class Action Lawsuit') 
    THEN '5302' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Attorney Handles') 
    THEN '6001' 
    ELSE
//
//Account Closed Section
//
IF ({DAT.DAT_ACTION_CODE} = 'XCR' 
        and {020_UDW.UDW_FLD1} = 'Disputing Debt' 
        and {020_UDW.UDW_FLD23} <> '') 
    THEN '5501' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'XCR' 
        and {020_UDW.UDW_FLD1} = 'Complaint' 
        and {020_UDW.UDW_FLD25} <> '') 
    THEN '5502' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'XCR' 
        and {020_UDW.UDW_FLD1} = 'Attorney Handles' 
        and {020_UDW.UDW_FLD27} <> '') 
    THEN '5503' 
    ELSE
//
//Bankruptcy and Deceased Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Bankruptcy' 
        and {020_UDW.UDW_FLD17} <> ''
        and {020_UDW.UDW_FLD18} <> '') 
    THEN '5901' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Deceased' 
        and {020_UDW.UDW_FLD14} <> ''
        and {01_ADR.ADR_STATE} <> '') 
    THEN '5902' 
    ELSE
//
//Military Deployment Section
//
IF ({DAT.DAT_ACTION_CODE} = 'DNC' 
        and {020_UDW.UDW_FLD1} = 'Active Military') 
    THEN '4150' 
    ELSE
//
//Default Values Section
//
IF ({DAT.DAT_ACTION_CODE} = 'XCR') 
    THEN '5510' 
    ELSE
IF ({DAT.DAT_ACTION_CODE} = 'DNC') 
    THEN '4001' 
    ELSE ''


            return $item;
        });

        $report = '';

        foreach($data as $item) {
            $report .= $item->DBR_CLI_REF_NO."|";
            $report .= sprintf("[%5.5s]",$lastName)
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