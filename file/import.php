<?php
//{name: "242431.xls", type: "application/vnd.ms-excel", tmp_name: "C:\Windows\php61B1.tmp", error: 0, size: 6656}
// 允许上传的后缀
$allowedExts = array("xls", "xlsx", "txt", "png", "jpg", "jpeg");
$temp = explode(".", $_FILES["file"]["name"]);
$size = $_FILES["file"]["size"];
$extension = end($temp);     // 获取文件后缀名
if (!in_array($extension, $allowedExts)) {
    exit(json_encode(array('status' => 0, 'msg' => "非法的文件格式", 'file' => $_FILES["file"])));
}
if ($_FILES["file"]["error"] > 0) {
    exit(json_encode(array('status' => 0, 'msg' => $_FILES["file"]["error"], 'file' => $_FILES["file"])));
}
$filename = md5(uniqid()) . '.' .$extension;
$dir = "./upload/" . $filename;
move_uploaded_file($_FILES["file"]["tmp_name"], $dir);
exit(json_encode(array('status' => 0, 'msg' => "11", 'file' => $_FILES["file"])));