<?php

/**
 * 入口文件
 * 异步任务样例
 */
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';

$text_worker = new Worker("text://0.0.0.0:60000");
$text_worker->count = 1;

$text_worker->name = 'worker_asynctcpcon_start_sync';

Worker::$logFile = __DIR__ . '/tmp/workerman.log';


$text_worker->onMessage = function ($connection, $message){
    $data = json_decode($message, true);
    sleep(3);//连续触发间隔时间累加
    $data['sync'] = 1;
    $data['time_sync'] = time();
    $connection->send(json_encode($data));
};

Worker::runAll();
    