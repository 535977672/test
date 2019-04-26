<?php
//导入

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '1024M');
set_time_limit(0);
ini_set('max_execution_time', 0);
date_default_timezone_set('Asia/Shanghai');
//header("Content-Type: text/html;charset=utf-8");

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$file = '../file/测试20181031163644.xlsx';
$file = iconv('utf-8', 'gb2312', $file);

$objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象 
if(!$objRead->canRead($file)){ 
    $objRead = new PHPExcel_Reader_Excel5(); 
    if(!$objRead->canRead($file)){
        die('No Excel!');
    }
}
$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 
    'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
    'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 
    'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
$obj = $objRead->load($file);  //建立excel对象 
$currSheet = $obj->getSheet(2);   //获取指定的sheet表 
$columnH = $currSheet->getHighestColumn();   //取得最大的列号
$columnCnt = array_search($columnH, $cellName);//返回它的键名
//$columnCnt = ord($columnH{0})-65;
$rowCnt = $currSheet->getHighestRow();   //获取总行数  
$data = array();
for($_row=1; $_row<=$rowCnt; $_row++){  //读取内容  
    for($_column=0; $_column<=$columnCnt; $_column++){
        //$cellId = $cellName[$_column].$_row;
        //$cellValue = $currSheet->getCell($cellId)->getValue();
        $cellValue = $currSheet->getCellByColumnAndRow($_column,$_row)->getValue();
        if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串
            $cellValue = $cellValue->__toString();
        }
        //$data[$_row][$cellName[$_column]] = $cellValue;
        $data[$_row][] = $cellValue;//合并单元格以第一个为准，其它为null
    }
}
var_dump($data);