<?php
//保存本地

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Shanghai');
//header("Content-Type: text/html;charset=utf-8");

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$filename = '测试'.date('YmdHis');

// Set document properties
$objPHPExcel->getProperties()->setCreator("Mf")//作者
							 ->setLastModifiedBy("M f")//最后一次保存者
							 ->setTitle("T")//标题
							 ->setSubject("主题")//主题
							 ->setDescription("备注")//备注
							 ->setKeywords("标记")//标记
							 ->setCategory("类别");//类别


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', '天猫')
            ->setCellValue('A5', '淘宝');

// Rename worksheet sheet表名
$objPHPExcel->getActiveSheet()->setTitle('京东');


//创建一个新的工作空间(sheet)
$objPHPExcel->createSheet();
$objPHPExcel->setactivesheetindex(1);
$objPHPExcel->setactivesheetindex(1)
            ->setCellValue('A1', 'Hello2')
            ->setCellValue('B2', 'world2!')
            ->setCellValue('C1', 'Hello2')
            ->setCellValue('D2', 'world2!')
            ->setTitle('京东2');
//$objPHPExcel->getActiveSheet()->setTitle('京东2');


//数组保存
$arr = [
    [1234561,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝'],
    [1234562,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝'],
    [1234563,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝'],
    [1234564,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝'],
    [1234565,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝'],
    [1234566,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝'],
    [1234567,789012,34567878899887766555444444,'aea','fewfe','天猫','淘宝']
];
$objPHPExcel->createSheet();
$objPHPExcel->setactivesheetindex(2);
$objPHPExcel->getActiveSheet()->setTitle('京东3')->fromArray($arr, null, 'C3');

//单元格宽度
$w = array('E'=>50);
foreach ($w as $key => $value) {
    $objPHPExcel ->getActiveSheet()->getColumnDimension($key)->setWidth($value);
}

//单元格合并
$m = array('A1'=>'C2', 'C4'=>'C5');
foreach ($m as $key => $value) {
    $objPHPExcel ->getActiveSheet()->mergeCells($key.':'.$value);
}



// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//打开默认页
$objPHPExcel->setActiveSheetIndex(2);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$name = '../file/'.$filename.'.xlsx';
$name = iconv('utf-8', 'gb2312', $name);
$objWriter->save($name);
exit('已保存');
