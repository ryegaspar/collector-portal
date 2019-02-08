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