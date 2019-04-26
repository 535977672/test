<?php 
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;

// 自动加载类
require_once __DIR__ . '/../../vendor/autoload.php';

//BusinessWorker类其实也是基于基础的Worker开发的。BusinessWorker是运行业务逻辑的进程，
//BusinessWorker收到Gateway转发来的事件及请求时会默认调用Events.php中的
//onConnect onMessage onClose方法处理事件及数据，开发者正是通过实现这些回调控制业务及流程。

// bussinessWorker 进程
$worker = new BusinessWorker();
// worker名称  BusinessWorker进程的名称，方便status命令中查看统计
$worker->name = 'IndexBusinessWorker';
// bussinessWorker进程数量
$worker->count = 4;   //cpu核数的1-3倍
// 服务注册地址
$worker->registerAddress = '127.0.0.1:1238';

//$worker->eventHandler='\my\namespace\MyEvent';

//onWorkerStart
//onWorkerStop
//eventHandler 设置使用哪个类来处理业务，默认值是Events
//即默认使用Events.php中的Events类来处理业务。业务类至少要实现onMessage静态方法，onConnect和onClose静态方法可以不用实现。

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

