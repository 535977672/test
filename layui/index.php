<?php


if(isset($_GET['type']) && $_GET['type'] == 1){
    selectDb1();
}
exit;



function selectDb1(){
    $page = isset($_GET['page'])?$_GET['page']:1;
    $limit = isset($_GET['limit'])?$_GET['limit']:20;
    $start = ($page-1)*$limit;
    $order = isset($_GET['order'])?$_GET['order']:'id asc';
    
    $sql = "select * from city order by $order limit $start,$limit";
    $data['data']['item'] = select($sql);
    
    $sql = "select count(*) from city";
    $data['total'] = selectcount($sql);
    
    $data['message'] = '';
    $data['status'] = 0;
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}

function select($sql){
    $conn = db();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectcount($sql){
    $conn = db();
    $stmt = $conn->query($sql);
    return $stmt->fetch()[0];
}

function db(){
    $servername = "127.0.0.1";
    $username = "root";
    $password = "root";
    $dbname = 'test';
    return $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
}


