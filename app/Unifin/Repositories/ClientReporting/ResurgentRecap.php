<?php
namespace App\Unifin\Repositories\ClientReporting;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ResurgentRecap implements ReportInterface
{

    public function generateReport($request)
     {
        $fromDate = Carbon::parse($request->date1)->toDateString();
        $toDate = Carbon::parse($request->date2)->toDateString();

        $data = DB::connection('sqlsrv2')
                ->table('CDS.DBR')

                ->join('UFN.OriginalAccountNumber', 'DBR.DBR_NO', '=', 'OriginalAccountNumber.Dbr_no')
                ->join('CDS.TRS', 'DBR.DBR_NO', '=', 'TRS.TRS_DBR_NO')
                ->join('CDS.CLT', 'DBR.DBR_CLIENT', '=', 'CLT.CLT_NO')
               
                ->select(DB::raw("DBR.DBR_NO, DBR_CLI_REF_NO, TRS_TRX_DATE_O, TRS_TRUST_CODE, TRS_AMT, TRS_COMM_AMT, DBR_CL_MISC_3, TRS_SEQ_NO, Orig_Acct_No"))
                
                
                ->whereNotIn('TRS_TRUST_CODE', ['2', '3', '14', '33'])
                
                ->whereRaw('CLT_NAME_1 LIKE ?', ['Resurgent Capital Systems'])
               
               
                ->where('TRS_AMT', '<>', 0)
                
                ->whereBetween('TRS_TRX_DATE_O', [$fromDate, $toDate])
                ->get();

            $data = $data->map(function ($item) {
            $pmt = [1, 100, 101, 102, 103, 200, 201, 202, 203, 300, 301, 302, 303];
            $rtn = [19, 120, 121, 122, 123, 140,141,142,143, 220, 221, 222, 223, 320, 321, 322, 323];
            $nsf = [248];
            $notpmt = [19, 120, 121, 122, 123, 140,141,142,143, 220, 221, 222, 223, 320, 321, 322, 323, 248];
 
            if (in_array($item->TRS_TRUST_CODE, $pmt)) {
                $item->TRS_TRUST_CODE = 'PMT';
            } else if (in_array($item->TRS_TRUST_CODE, $rtn)){
                $item->TRS_TRUST_CODE = 'PRV';
            } else if (in_array($item->TRS_TRUST_CODE, $nsf)){
                $item->TRS_TRUST_CODE = 'NSF';
            } else {
                $item->TRS_TRUST_CODE = 'NRV';
            }

               



            $item->ABS_TRS_AMT = abs($item->TRS_AMT);
            $item->ABS_TRS_COMM_AMT = abs($item->TRS_COMM_AMT);
            $item->Remitted = ($item->TRS_AMT-$item->TRS_COMM_AMT);
            $item->DebtorPayment = $item->ABS_TRS_AMT;
            $item->ContingencyFee =$item->ABS_TRS_COMM_AMT;

            $Start = Carbon::now()->subDays(1)->format('m/d/Y');
            $End = Carbon::now()->subDays(7)->format('m/d/Y');
            $Today = Carbon::now()->format('m/d/Y');

    
           
            return $item;
        });
  


        $fileName = 'RECAP_8357_'. Carbon::now()->format('Ymd') . '.xlsx';
        $filePath = public_path('storage\\reports\\'. $fileName);

        // dd($data, $fileName, $headers, $columns, $filePath);

        $this->resurgentRemitXlsx($data, $fileName, $filePath);

    }



 public function resurgentRemitXlsx($data, $fileName, $filePath)
    {
        $spreadsheet = new spreadsheet;

        $spreadsheet->getProperties()->setCreator('Claire')
            ->setLastModifiedBy('Claire');




        $End = Carbon::now()->subDays(1)->format('m/d/Y');
        $Start = Carbon::now()->subDays(7)->format('m/d/Y');
        $Today = Carbon::now()->format('m/d/Y');


        $sumDebtorPayment = $data->pluck('DebtorPayment')->sum();
        $sumComPayment = $data->pluck('ContingencyFee')->sum();
        $sumRemitted = $data->pluck('Remitted')->sum();
        $countPMT = $data->pluck('countPMT');
        $countPRV = $data->pluck('countPRV');
         
    
   




        $styleBold = [
            'font' => [
                'bold' => true
            ]
        ];


        $spreadsheet->getActiveSheet()->setCellValue('A1', 'AGENCY NAME');
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('A2', 'AGENCY ADDRESS');
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('A7', 'Activity for:');
        $spreadsheet->getActiveSheet()->getStyle('A7')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('A8', 'Remit Date:');
        $spreadsheet->getActiveSheet()->getStyle('A8')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('A9', 'VendorInvoiceNumber:');
        $spreadsheet->getActiveSheet()->getStyle('A9')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('A12', 'Prepared For:');
        $spreadsheet->getActiveSheet()->getStyle('A12')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('A13', 'Resurgent Capital Services, LP.');
        $spreadsheet->getActiveSheet()->getStyle('A13')->applyFromArray($styleBold)->getFont()->setSize(14)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A14', '55 Beattie Place ');
        $spreadsheet->getActiveSheet()->getStyle('A14')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A15', 'Suite 110, MS 251');
        $spreadsheet->getActiveSheet()->getStyle('A15')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A16', 'Greenville, SC 29601');
        $spreadsheet->getActiveSheet()->getStyle('A16')->getFont()->setSize(10)->setName("Arial");


        $spreadsheet->getActiveSheet()->setCellValue('A18', '                PAYMENT FILE RECAP REPORT ');
        $spreadsheet->getActiveSheet()->getStyle('A18')->applyFromArray($styleBold)->getFont()->setSize(12)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A21', 'Payments');
        $spreadsheet->getActiveSheet()->getStyle('A21')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial")->setUnderline(true);
        
        $spreadsheet->getActiveSheet()->setCellValue('A22', 'PAYMENT TO AGENCY');
        $spreadsheet->getActiveSheet()->getStyle('A22')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A23', 'MISSAPPLIED PAYMENTS');
        $spreadsheet->getActiveSheet()->getStyle('A23')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A24', 'NET PAYMENTS');
        $spreadsheet->getActiveSheet()->getStyle('A24')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A26', "NSF's");
        $spreadsheet->getActiveSheet()->getStyle('A26')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial")->setUnderline(true);
        

        $spreadsheet->getActiveSheet()->setCellValue('A27', 'RETURNED CHECKS TO THE AGENCY:');
        $spreadsheet->getActiveSheet()->getStyle('A27')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A28', 'MISSAPPLIED NSF');
        $spreadsheet->getActiveSheet()->getStyle('A28')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A29', 'TRUE NSF RETURNED CHECKS');
        $spreadsheet->getActiveSheet()->getStyle('A29')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A31', 'Total # of Transactions:');
        $spreadsheet->getActiveSheet()->getStyle('A31')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('A32', 'TOTAL ACH FROM AGENCY:');
        $spreadsheet->getActiveSheet()->getStyle('A32')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");   

        $spreadsheet->getActiveSheet()->setCellValue('A33', 'Total Expected Commission from Resurgent Capital:');
        $spreadsheet->getActiveSheet()->getStyle('A33')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Unifin, Inc.');
        $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('B2', '8950 Gross Point Rd.');
        $spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'Suite 500');
        $spreadsheet->getActiveSheet()->getStyle('B3')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B4', 'Skokie, IL 60077');
        $spreadsheet->getActiveSheet()->getStyle('B4')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B7',$Start.' to '.$End);
        $spreadsheet->getActiveSheet()->getStyle('B7')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B8', $Today);
        $spreadsheet->getActiveSheet()->getStyle('B8')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B9', '43437');
        $spreadsheet->getActiveSheet()->getStyle('B9')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B21', '# of Transactions');
        $spreadsheet->getActiveSheet()->getStyle('B21')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('B22', $countPMT);
        $spreadsheet->getActiveSheet()->getStyle('B22')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B23', '');
        $spreadsheet->getActiveSheet()->getStyle('B23')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B24', '=B22+B23');
        $spreadsheet->getActiveSheet()->getStyle('B24')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B27', $countnotpmt);
        $spreadsheet->getActiveSheet()->getStyle('B27')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B28', '');
        $spreadsheet->getActiveSheet()->getStyle('B28')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B29', '=B27-B28');
        $spreadsheet->getActiveSheet()->getStyle('B29')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('B31', '=B22+B23+B27+B28');
        $spreadsheet->getActiveSheet()->getStyle('B31')->getFont()->setSize(11)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('C21', 'Gross');
        $spreadsheet->getActiveSheet()->getStyle('C21')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('C22',$sumDebtorPayment);
        $spreadsheet->getActiveSheet()->getStyle('C22')->getFont()->setSize(10)->setName("Arial");
     

        $spreadsheet->getActiveSheet()->setCellValue('C23', '');
        $spreadsheet->getActiveSheet()->getStyle('C23')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('C24', '=C22+C23');
        $spreadsheet->getActiveSheet()->getStyle('C24')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('C27', '');
        $spreadsheet->getActiveSheet()->getStyle('C27')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('C28', '');
        $spreadsheet->getActiveSheet()->getStyle('C28')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('C29', '=C27+C28');
        $spreadsheet->getActiveSheet()->getStyle('C29')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('D21', 'Expected Commission');
        $spreadsheet->getActiveSheet()->getStyle('D21')->applyFromArray($styleBold)->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('D22', $sumComPayment);
        $spreadsheet->getActiveSheet()->getStyle('D22')->getFont()->setSize(10)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('D23', '');
        $spreadsheet->getActiveSheet()->getStyle('D23')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('D24', '=D22+D23');
        $spreadsheet->getActiveSheet()->getStyle('D24')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");

        $spreadsheet->getActiveSheet()->setCellValue('D27', '');
        $spreadsheet->getActiveSheet()->getStyle('D27')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('D28', '');
        $spreadsheet->getActiveSheet()->getStyle('D28')->getFont()->setSize(10)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('D29', '=D27+D28');
        $spreadsheet->getActiveSheet()->getStyle('D29')->applyFromArray($styleBold)->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('D32', '=C24+C29');
        $spreadsheet->getActiveSheet()->getStyle('D32')->getFont()->setSize(11)->setName("Arial");
        
        $spreadsheet->getActiveSheet()->setCellValue('D33', '=D24+D29');
        $spreadsheet->getActiveSheet()->getStyle('D33')->getFont()->setSize(11)->setName("Arial");

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);

    }

}