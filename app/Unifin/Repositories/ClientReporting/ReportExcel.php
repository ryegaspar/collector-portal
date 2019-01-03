<?php

namespace App\Unifin\Repositories\ClientReporting;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ReportExcel
{
    protected $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet;
    }

    /**
     * Make a header for xlsx document.
     * @param $fileName
     */
    protected function makeXlsxHeader($fileName)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
    }

    public function setFont($fontName, $size)
    {
        $this->spreadsheet->getDefaultStyle()->getFont()->setname($fontName);
        $this->spreadsheet->getDefaultStyle()->getFont()->setSize($size);
        // Set cell A8 with a numeric value, but tell PhpSpreadsheet it should be treated as a string


        return $this;
    }

    /**
     * Create a simple xlsx document from a collection.
     *
     * @param $collection
     * @param $fileName
     * @param $headers
     * @param $columns
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
   


    public function resurgentRemitXlsx($collection, $fileName, $filePath)
    {
        $spreadsheet = $this->spreadsheet;
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'AGENCY NAME');
        $spreadsheet->getActiveSheet()->setCellValue('A2', 'AGENCY ADDRESS');
        $spreadsheet->getActiveSheet()->setCellValue('A7', 'Activity for:
');
        $spreadsheet->getActiveSheet()->setCellValue('A8', 'Remit Date:
');
        $spreadsheet->getActiveSheet()->setCellValue('A9', 'VendorInvoiceNumber:
');
        $spreadsheet->getActiveSheet()->setCellValue('A12', 'Prepared For:
');
        $spreadsheet->getActiveSheet()->setCellValue('A13', 'Resurgent Capital Services, LP.
');
        $spreadsheet->getActiveSheet()->setCellValue('A14', '55 Beattie Place 
');
        $spreadsheet->getActiveSheet()->setCellValue('A15', 'Suite 110, MS 251
');
        $spreadsheet->getActiveSheet()->setCellValue('A16', 'Greenville, SC 29601
');
        $spreadsheet->getActiveSheet()->setCellValue('A18', '                PAYMENT FILE RECAP REPORT 
');
        $spreadsheet->getActiveSheet()->setCellValue('A21', 'Payments');
        $spreadsheet->getActiveSheet()->setCellValue('A22', 'PAYMENT TO AGENCY
');
        $spreadsheet->getActiveSheet()->setCellValue('A23', 'MISSAPPLIED PAYMENTS
');
        $spreadsheet->getActiveSheet()->setCellValue('A24', 'NET PAYMENTS
');
        $spreadsheet->getActiveSheet()->setCellValue('A26', "NSF's
");
        $spreadsheet->getActiveSheet()->setCellValue('A27', 'RETURNED CHECKS TO THE AGENCY:
');
        $spreadsheet->getActiveSheet()->setCellValue('A28', 'MISSAPPLIED NSF
');
        $spreadsheet->getActiveSheet()->setCellValue('A29', 'TRUE NSF RETURNED CHECKS
');
        $spreadsheet->getActiveSheet()->setCellValue('A31', 'Total # of Transactions:
');
        $spreadsheet->getActiveSheet()->setCellValue('A32', 'TOTAL ACH FROM AGENCY:
');
        $spreadsheet->getActiveSheet()->setCellValue('A33', 'Total Expected Commission from Resurgent Capital:
');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Unifin, Inc.
');
        $spreadsheet->getActiveSheet()->setCellValue('B2', '8950 Gross Point Rd.
');
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'Suite 500
');
        $spreadsheet->getActiveSheet()->setCellValue('B4', 'Skokie, IL 60077
');
        $spreadsheet->getActiveSheet()->setCellValue('B21', '# of Transactions
');
        $spreadsheet->getActiveSheet()->setCellValue('B22', '');
        $spreadsheet->getActiveSheet()->setCellValue('B23', '');
        $spreadsheet->getActiveSheet()->setCellValue('B24', '=B22+B23');
        $spreadsheet->getActiveSheet()->setCellValue('B27', '');
        $spreadsheet->getActiveSheet()->setCellValue('B28', '');
        $spreadsheet->getActiveSheet()->setCellValue('B29', '=B27-B28');
        $spreadsheet->getActiveSheet()->setCellValue('B31', '=B22+B23+B27+B28');

        $spreadsheet->getActiveSheet()->setCellValue('C21', 'Gross
');
        $spreadsheet->getActiveSheet()->setCellValue('C22', '');
        $spreadsheet->getActiveSheet()->setCellValue('C23', '');
        $spreadsheet->getActiveSheet()->setCellValue('C24', '=C22+C23');
        $spreadsheet->getActiveSheet()->setCellValue('C27', '');
        $spreadsheet->getActiveSheet()->setCellValue('C28', '');
        $spreadsheet->getActiveSheet()->setCellValue('C29', '=C27+C28');
        
        $spreadsheet->getActiveSheet()->setCellValue('D21', 'Expected Commission
');
        $spreadsheet->getActiveSheet()->setCellValue('D22', '');
        $spreadsheet->getActiveSheet()->setCellValue('D23', '');
        $spreadsheet->getActiveSheet()->setCellValue('D24', '=D22+D23');
        $spreadsheet->getActiveSheet()->setCellValue('D27', '');
        $spreadsheet->getActiveSheet()->setCellValue('D28', '');
        $spreadsheet->getActiveSheet()->setCellValue('D29', '=D27+D28');
        $spreadsheet->getActiveSheet()->setCellValue('D32', '=C24+C29');
        $spreadsheet->getActiveSheet()->setCellValue('D33', '=D24+D29');


    }


    public function makeSimpleXlsxFromCollection($collection, $fileName, $headers, $columns, $filePath)
    {
        $spreadsheet = $this->spreadsheet;

        $spreadsheet->getProperties()->setCreator('Claire')
            ->setLastModifiedBy('Claire');

        $headers->each(function ($item, $index) use ($spreadsheet) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow($index + 1, 1, $item);
        });

        $row = 2;
        $collection->each(function ($item) use ($spreadsheet, &$row, $columns) {
            $columns->each(function ($columnName, $index) use ($item, $spreadsheet, $row) {
                if (is_array($columnName)) {
                    $spreadsheet->setActiveSheetIndex(0)
                        // ->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName[0]][$columnName[1]]);
                    ->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName[0]][$columnName[1]]);
                } else {
                    if (is_array($item[$columnName])) {

                        if (array_key_exists('setFormatCode', $item[$columnName])) {
                
                            $coordinates = $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName][0])
                            ->getCoordinates();
                                
                            array_shift($item[$columnName]);

                            foreach ($item[$columnName] as $key => $format) {
                                $spreadsheet->getActiveSheet()->getStyle(array_pop($coordinates))->getNumberFormat()->$key($format);
                            }    
                        } else {

                            $lookup = [
                                'TYPE_STRING' => DataType::TYPE_STRING,
                                'TYPE_NUMERIC' => DataType::TYPE_NUMERIC
                            ];


                            $coordinates = $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValueExplicitByColumnAndRow($index + 1, $row, $item[$columnName][0], $lookup[$item[$columnName][1]])
                            ->getCoordinates();
                        }
                        
                    } else {
                        
                        $spreadsheet->setActiveSheetIndex(0)->setCellValueByColumnAndRow($index + 1, $row, $item[$columnName]);

                    }
                }
            });

            $row++;
        });

        $spreadsheet->setActiveSheetIndex(0);

        $this->makeXlsxHeader($fileName);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filePath);
    }
}