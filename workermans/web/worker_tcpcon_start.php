<?php

/**
 * 入口文件
 * connection样例
 */
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';

$global_uid = 0;

function handle_connection($connection)
{
    global $global_uid;
    // 为这个连接分配一个uid
    $connection->uid = ++$global_uid;
    
    $data = [
        'client_id' => $connection->id,
        'type' => 'connect'
    ];
    $connection->send(json_encode($data, JSON_UNESCAPED_UNICODE));
    
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
    
    
    $connection->onMessage = function($con, $message){
        //心跳检测
        if($message == 'ping'){
            $time = time();
            $_SESSION[$time] = $time.'-'.$con->id;
            $data = [
                'client_id' => $con->id,
                'type' => 'ping',
                'session' => $_SESSION
            ];
            $con->send(json_encode($data));
            //exit();//exit结束会清空 $_SESSION
            //浏览器刷新会清空 $_SESSION
        }else{
             $data = [
                'client_id' => $con->id,
                'type' => 'message',
                'message' => $message
            ];
            $con->send(json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    };
    
    $connection->onClose = function($con){
        $data = [
            'client_id' => $con->id,
            'type' => 'close'
        ];
        $con->send(json_encode($data, JSON_UNESCAPED_UNICODE));

        echo "connection closed1\n";
    };
    
}

function handle_close($connection)
{
    $data = [
             'client_id' => $connection->id,
             'type' => 'close'
         ];
    $connection->send(json_encode($data, JSON_UNESCAPED_UNICODE));
    
    echo "connection closed2\n";
}

$text_worker = new Worker("websocket://0.0.0.0:2347");
$text_worker->count = 1;

$text_worker->name = 'MyWebsocketWorker';

Worker::$logFile = __DIR__ . '/tmp/workerman.log';

$text_worker->onWorkerStart = function($worker){
    echo 'Worker Starting';
};

$text_worker->onConnect = 'handle_connection';
$text_worker->onClose = 'handle_close';//无效

Worker::runAll();
    