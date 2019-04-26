<?php
//$servername = "127.0.0.1";
//$username = "root";
//$password = "11111111";
//$dbname = 'tp5';

$servername = "192.168.1.112";
$username = "slave";//防火墙开启3306端口访问规则
$password = "11111111";
$dbname = 'tp5';


$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

$conn->beginTransaction();

//使用命名（:name）参数来准备SQL语句
$stmt = $conn->prepare("select id,code from test1 where id < :id limit 2");
$stmt->execute(array('id' => 5));
$re = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($re);
  