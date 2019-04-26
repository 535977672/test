<?php

/**
 * 入口文件 
 * onWorkerStart回调中初始化数据库连接
 */
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';
require_once __DIR__ . '/../Plugins/mysql-master/src/Connection.php';

$text_worker = new Worker("websocket://0.0.0.0:2347");  //客户端即能通过ipv4地址访问，也能通过ipv6地址访问 监听地址写[::] new Worker('http://[::]:8080')


$text_worker->count = 1;

$text_worker->name = 'MyWebsocketWorker';

$text_worker->onWorkerStart = function($worker){
    // 将db实例存储在全局变量中(也可以存储在某类的静态成员中)
    global $db;
    $db = new \Workerman\MySQL\Connection('127.0.0.1', '3306', 'root', 'root', 'test', 'utf8');
};


$text_worker->onMessage = function($connection, $data){
    // 将db实例存储在全局变量中(也可以存储在某类的静态成员中)
    global $db;
    $d['db'] = $db->select('id,code,name')->from('test')->where('id> :id')->bindValues(array('id'=>10))->query();
        
    
    
    $connection->send(json_encode($d));
};

Worker::runAll();