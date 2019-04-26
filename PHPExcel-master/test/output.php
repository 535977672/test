<?php
//导出

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

$objPHPExcel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);//设置是否加粗
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置文字居中
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
$objPHPExcel->getActiveSheet()->getStyle('C4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
$objPHPExcel->getActiveSheet()->getStyle('C4')->getFill()->getStartColor()->setARGB('FF7F24');//设置填充颜色

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


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

//function downloadFile($filename){
//   //获取文件的扩展名
//   $allowDownExt=array ( 'rar','zip','png','txt','mp4','html');
//   //获取文件信息
//   $fileext=pathinfo($filename);
//   //检测文件类型是否允许下载
//   if(!in_array($fileext['extension'],$allowDownExt)) {
//      return false;
//   }
//   //设置脚本的最大执行时间，设置为0则无时间限制
//   set_time_limit(0);
//   ini_set('max_execution_time', '0');
//   //通过header()发送头信息
//   //因为不知道文件是什么类型的，告诉浏览器输出的是字节流
//   header('content-type:application/octet-stream');
//   //告诉浏览器返回的文件大小类型是字节
//   header('Accept-Ranges:bytes');
//   //获得文件大小
//   //$filesize=filesize($filename);//(此方法无法获取到远程文件大小)
//   $header_array = get_headers($filename, true);
//   $filesize = $header_array['Content-Length'];
//   //告诉浏览器返回的文件大小
//   header('Accept-Length:'.$filesize);
//   //告诉浏览器文件作为附件处理并且设定最终下载完成的文件名称
//   header('content-disposition:attachment;filename='.basename($filename));
//   //针对大文件，规定每次读取文件的字节数为4096字节，直接输出数据
//   $read_buffer=4096;
//   $handle=fopen($filename, 'rb');
//   //总的缓冲的字节数
//   $sum_buffer=0;
//   //只要没到文件尾，就一直读取
//   while(!feof($handle) && $sum_buffer<$filesize) {
//      echo fread($handle,$read_buffer);
//      $sum_buffer+=$read_buffer;
//   }
//   //关闭句柄
//   fclose($handle);
//   exit;
//
//}
