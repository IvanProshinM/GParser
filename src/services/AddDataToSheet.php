<?php

namespace app\service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AddDataToSheet
{

    public function addData(Spreadsheet $blankSheet,  $indexRow, $indexCol, array $resultArray)
    {

            $sheet = $blankSheet->getActiveSheet()->setCellValueByColumnAndRow($indexCol, $indexRow, $);



        /*$writer = new Xlsx($spreadsheet);
           header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header('Content-Disposition: attachment; filename="' . urlencode('salary_manager.xlsx')
               . '"');
           ob_end_clean();
           $writer->save('php://output');
           exit();*/
    }


}