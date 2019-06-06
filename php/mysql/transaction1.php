<?php
//CREATE TABLE IF NOT EXISTS `bingfa` (
//  `id` int(10) NOT NULL AUTO_INCREMENT,
//  `name` varchar(20) NOT NULL,
//  `time` int(10) NOT NULL,
//  PRIMARY KEY (`id`)
//) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='beginTransaction' ;

set_time_limit(0);

ini_set('memory_limit', '-1');

$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = 'test';
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

$back = isset($_GET['back'])?intval($_GET['back']):0;
$sleep1 =  isset($_GET['sleep1'])?intval($_GET['sleep1']):0;
$sleep2 =  isset($_GET['sleep2'])?intval($_GET['sleep2']):0;
    
try {
    

    $conn->beginTransaction();

    $stmt = $conn->prepare("select * from bingfa where id = :id");
    $stmt->execute(['id' => 4]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    var_dump($data);
    
    if($sleep1) sleep(30);
    
    $time = time();echo ' s '.$time;
    $stmt = $conn->prepare("update `bingfa` set `time` = :t where `bingfa`.`id` = :id");
    $re = $stmt->execute(['t' => $time, 'id' => 4]);//bool(true) 
    var_dump($re);
    
    if($sleep2)  sleep(30);
    
    echo ' e '.time();
    
    if($back){
        throw new PDOException('hh');
    }else{
        $conn->commit();
    }
}catch(PDOException $e){
    $conn->rollBack();
    echo $e->getMessage();
}