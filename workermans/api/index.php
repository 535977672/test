<?php

/**
 * 入口文件
 */
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';

//Autoloader里
function autoloadClass($classname) {
    $filename = '';
    if(substr($classname, -10) == 'Controller'){
        $filename = __DIR__ . '/controller/'. substr($classname, 0, -10) .".php";
    }
    if (is_file($filename)) {
        require_once($filename);
        if (class_exists($filename, false)) {
            return true;
        }
    }
    return false;
}
spl_autoload_register('autoloadClass');//注册到SPL __autoload函数队列尾


$text_worker = new Worker("websocket://0.0.0.0:2347");  
$text_worker->count = 1;
// 设置实例的名称
$text_worker->name = 'MyWebsocketWorker';

$text_worker->onMessage = function ($connection, $message){
        //onMessage权限验证
    
        $data = json_decode($message, true);
        $con = $data['con'].'Controller';
        $action = $data['action'];
        $params = $data['params'];
        if (!class_exists($con, true)) { //true 调用 __autoload
            $connection->send(json_encode('控制器为空'));
            return;
        }
        if (!method_exists($con, $action)) {
            $connection->send(json_encode('方法为空'));
            return;
        }
        $controller = new $con;
        try {
            $re = call_user_func_array(array($controller, $action), array($params));
            $data['result'] = $re;
            $connection->send(json_encode($data));
        } catch (\Exception $e) {
            $connection->send(json_encode($e->getMessage()));
        }
};

Worker::runAll();