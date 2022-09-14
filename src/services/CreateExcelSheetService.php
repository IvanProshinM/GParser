<?php

namespace app\services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CreateExcelSheetService
{

    public function CreateSheet(array $resultArray) :Void
    {
        $sheetName = [
            'A1' => 'Name',
            'B1' => 'Category',
            'C1' => 'Country',
            'D1' => 'City',
            'E1' => 'Address',
            'F1' => 'locationLat',
            'G1' => 'LocationLng',
            'H1' => 'Website',
            'I1' => 'Rating',
            'J1' => 'Monday',
            'K1' => 'Tuesday',
            'L1' => 'Wednesday',
            'M1' => 'Thursday',
            'N1' => 'Friday',
            'O1' => 'Saturday',
            'P1' => 'Sunday',
        ];

        $spreadsheet = new Spreadsheet();
        $i=0;
        $j=2;
        foreach ($sheetName as $index => $name) {
            $sheet = $spreadsheet->getActiveSheet()->setCellValue($index, $name);
            $sheet = $spreadsheet->getActiveSheet()->getStyle($index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
        }

        foreach ($resultArray as $dataInput) {
            $sheet = $spreadsheet->getActiveSheet()->setCellValue([++$i, $j], $dataInput);
        }


        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('salary_manager.xlsx')
            . '"');
        ob_end_clean();
        $writer->save('php://output');
        exit();

    }
}