<?php

namespace Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PhpExcel
{
    protected $errMsg = '';
    protected $errCode = 0;

    //默认列宽
    public $defaultWidth = 20;
    //列宽
    public $columnWidth = []; //[['i'=>'1', 'v'=>'100']] 1-第一列
    //背景颜色
    public $cellColor = []; //[['A1'=>'ccc', 'B5'=>'red']]
    //文字颜色
    public $wordsColor = []; //[['A1'=>'ccc', 'B5'=>'red']]

    public function getMsg(){
        return $this->errMsg;
    }

    public function getCode(){
        return $this->errCode;
    }

    public function setError($msg, $code = 0){
        $this->errMsg = $msg;
        $this->errCode = $code;
    }

    /**
     * @param $inputFileName
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function excelToArray($inputFileName){
        try{
            $spreadsheet = IOFactory::load($inputFileName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray('', true, true, true);
            return $sheetData;
        } catch (\Exception $e){
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 数组转文档
     * @param $data 二位数组
     * @param int $output 1-下载 2-保存本地
     * @return bool|\PhpOffice\PhpSpreadsheet\Spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function arrayToXlsx($data, $output = 1, $path = ''){
        try{
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $this->columnWidth($sheet);
            $this->cellColor($sheet);
            $row = 1; // 从第一行开始写
            foreach ($data as $item) {
                $column = 1;
                foreach ($item as $value) {
                    // 单元格内容写入
                    $sheet->setCellValueByColumnAndRow($column, $row, $value);
                    $column++;
                }
                $row++;
            }
            if($output == 1){
                $inputFileName = date('YmdHis').time();
                $this->outputFileXlsx($inputFileName, $spreadsheet);
                return true;
            }else if($output == 2){
                $inputFileName = $path . date('YmdHis').time().'.xlsx';
                $this->outputFileXlsxSave($inputFileName, $spreadsheet);
                return $inputFileName;
            }
            return $spreadsheet;
        } catch (\Exception $e){
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param $sheet
     * @return mixed
     */
    private function columnWidth($sheet){
        $sheet->getDefaultColumnDimension()->setWidth($this->defaultWidth);
        if($this->columnWidth){
            foreach($this->columnWidth as $w){
                $sheet->getColumnDimensionByColumn($w['i'])->setWidth($w['v']);
            }
        }
        return $sheet;
    }

    /**
     * @param $sheet
     * @return mixed
     */
    private function cellColor($sheet){
        if($this->cellColor){
            foreach($this->cellColor as $key => $c){
                $sheet->getStyle($key)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($c);
            }
        }
        if($this->wordsColor){
            foreach($this->wordsColor as $key => $c){
                $sheet->getStyle($key)->getFont()->getColor()->setARGB($c);
            }
        }
        return $sheet;
    }


    /**
     * 保存
     * @param $outputFileName
     * @param $spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function outputFileXlsxSave($outputFileName, $spreadsheet){
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputFileName);
    }


    /**
     * 输出下载
     * @param $outputFileName
     * @param $spreadsheet
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function outputFileXlsx($outputFileName, $spreadsheet){
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$outputFileName.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}