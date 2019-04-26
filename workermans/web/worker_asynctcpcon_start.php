<?php

/**
 * 入口文件
 * 异步任务样例
 */
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;
require_once __DIR__ . '/../Workerman/Autoloader.php';

function handle_connection($connection)
{   
    $data = [
        'client_id' => $connection->id,
        'type' => 'connect'
    ];
    $connection->send(json_encode($data));
}

$text_worker = new Worker("websocket://0.0.0.0:2348");
$text_worker->count = 1;

$text_worker->name = 'worker_asynctcpcon_start';

Worker::$logFile = __DIR__ . '/tmp/workerman.log';

$text_worker->onConnect = 'handle_connection';

$text_worker->onMessage = function ($connection, $message){

    // 异步服务的连接
    $connection_to_task = new AsyncTcpConnection('text://127.0.0.1:60000');

    $connection_to_task->onMessage = function($connection_to_task, $buffer)use($connection)
    {
        $connection->send($buffer);
    };

    $connection_to_task->onClose = function($connection_to_task)use($connection)
    {
        $connection->close();
    };

    $connection_to_task->onError = function($connection_to_task)use($connection)
    {
        $connection->close();
    };

    // 执行异步连接
    $connection_to_task->connect();

    
    
    // 客户端发来数据时，转发
    $connection->onMessage = function($connection, $message)use($connection_to_task)
    {
        $data = json_decode($message, true);
        $data['time'] = time();
        $connection_to_task->send(json_encode($data));
    };
    
    // 客户端连接断开时，断开对应的连接
    $connection->onClose = function($connection)use($connection_to_task)
    {
        $connection_to_task->close();
    };
    // 客户端连接发生错误时，断开对应的mysql连接
    $connection->onError = function($connection)use($connection_to_task)
    {
        $connection_to_task->close();
    };
};

Worker::runAll();
    