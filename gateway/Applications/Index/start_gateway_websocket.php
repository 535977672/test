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

// gateway 进程，这里使用Text协议，可以用telnet测试
//$gateway = new Gateway("tcp://0.0.0.0:8282");

//应用层协议，目前支持的协议有
//1、websocket协议
//2、text协议
//3、Frame协议
//4、自定义通讯协议
//5、tcp，直接裸tcp，不推荐，见通讯协议作用。
//注意:GatewayWorker不支持监听Http协议。但是可以在业务中以客户端的形式通过http协议(比如curl)访问远程服务器。
$gateway = new Gateway("websocket://0.0.0.0:7272");//workman -> $worker = new Worker();

//路由
//$worker_connections 是一个包含了所有到BusinessWorker进程的连接对象数组 item-> ip:port:worker_name:worker_id
//$client_connections 客户端连接对象 可以通过此对象获得客户端ip端口等信息
//$cmd 当前什么类型的消息 CMD_ON_CONNECTION，即连接事件 CMD_ON_MESSAGE，即消息事件 CMD_ON_CLOSE，即客户端关闭事件
//$buffer CMD_ON_MESSAGE时 客户端发来的数据 
//  ===随机路由开始===
//$gateway->router = function($worker_connections, $client_connection, $cmd, $buffer)
//{
//    return $worker_connections[array_rand($worker_connections)];//返回 $worker_connnections 中的一个连接对象
//};
// ==随机绑定==
//$gateway->router = function($worker_connections, $client_connection, $cmd, $buffer)
//{
//    // 临时给客户端连接设置一个businessworker_address属性，用来存储该连接被绑定的worker进程下标
//    if (!isset($client_connection->businessworker_address) || !isset($worker_connections[$client_connection->businessworker_address])) {
//            $client_connection->businessworker_address = array_rand($worker_connections);
//        }
//        return $worker_connections[$client_connection->businessworker_address];
//};


/**
 * 分布式时只写一台服务器，其他可注释掉
 * 
 * 如何分布式GatewayWorker
 * GatewayWorker通过Register服务来建立划分集群。同一集群使用相同的Register服务ip和端口，
 * 即Gateway 和 businessWorker的注册服务地址
 * ($gateway->registerAddress $businessworker->registerAddress)指向同一台Register服务。
 * 
 * GatewayWorker有三种进程，
 * Gateway进程负责网络IO，
 * BusinessWorker进程负责业务处理，
 * Register进程负责协调Gateway与BusinessWorker之间建立TCP长连接通讯
 * 
 * 
 * gateway worker 分离部署
 * Gateway BusinessWorker Register分开部署在不同的服务器上，
 * 当业务进程BusinessWorker出现瓶颈时，单独增加BusinessWorker服务器提升系统负载能力。
 * 同理，如果Gateway进程出现瓶颈，则增加Gateway服务器。
 * 而Register服务一个集群只需要部署一台服务器，
 * Register服务只有在进程启动的时候协调Gateway与BusinessWorker建立TCP连接，集群运行起来后通讯量极低，不会成为系统瓶颈
 * 
 * 以debug（调试）方式启动
 * php start.php start
 * 以daemon（守护进程）方式启动
 * php start.php start -d
 * 停止  php start.php stop
 * 重启  php start.php restart  BusinessWorker会感知到有Gateway服务器下线
 * 平滑重启  php start.php reload
 * 查看状态  php start.php status
 * 
 */


// gateway名称，status方便查看 实例的名称
$gateway->name = 'IndexGateway';
// gateway进程数
$gateway->count = 4;   //cpu核数
// lanIp是Gateway所在服务器的内网IP 
// 本机ip，分布式部署时使用内网ip
$gateway->lanIp = '127.0.0.1';
// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
// 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
//Gateway进程启动后会监听一个本机端口，用来给BusinessWorker提供链接服务，
//然后Gateway与BusinessWorker之间就通过这个连接通讯。
//这里设置的是Gateway监听本机端口的起始端口。比如启动了4个Gateway进程，
//startPort为2000，则每个Gateway进程分别启动的本地端口一般为2000、2001、2002、2003。
//
//多个Gateway/BusinessWorker项目时，需要把每个项目的startPort设置成不同的段
$gateway->startPort = 4000;
// 服务注册地址
$gateway->registerAddress = '127.0.0.1:1238';

//客户端定时发送心跳(推荐)
// 心跳间隔 建议小于60秒
$gateway->pingInterval = 50;
// 心跳数据
//$gateway->pingData = '{"type":"ping"}';
$gateway->pingData = '';

$gateway->pingNotResponseLimit = 1;
//客户端连接 pingInterval*pingNotResponseLimit=100 秒内没有任何请求则服务端认为对应客户端已经掉线，
//服务端关闭连接并触发onClose回调

//其中pingNotResponseLimit = 0代表服务端允许客户端不发送心跳，服务端不会因为客户端长时间没发送数据而断开连接。
//如果pingNotResponseLimit = 1，则代表客户端必须定时发送心跳给服务端，
//否则pingNotResponseLimit*pingInterval秒内没有任何数据发来则关闭对应连接，并触发onClose。


//onWorkerStart  和Worker一样，可以设置Gateway进程启动后的回调函数，一般在这个回调里面初始化一些全局数据
//onWorkerStop 和Worker一样，可以设置Gateway进程关闭的回调函数，一般在这个回调里面做数据清理或者保存数据工作
//onConnect（比较少用到，开发者一般不用关注） 和Worker一样，可以设置onConnect回调，当有客户端连接上来时触发。与Events::onConnect的区别是Events::onConnect运行在BusinessWorker进程上。Gateway::onConnect是运行在Gateway进程上，无法使用\GatewayWorker\Lib\Gateway类提供的接口
//onClose（比较少用到，开发者一般不用关注） 和Worker一样，可以设置onClose回调，当有客户端连接关闭时触发。同样与Events::onClose的区别是Gateway::onClose是运行在Gateway进程上，无法使用\GatewayWorker\Lib\Gateway类提供的接口

/* 
// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
$gateway->onConnect = function($connection)
{
    $connection->onWebSocketConnect = function($connection , $http_header)
    {
        // 可以在这里判断连接来源是否合法，不合法就关掉连接
        // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
        if($_SERVER['HTTP_ORIGIN'] != 'http://kedou.workerman.net')
        {
            $connection->close();
        }
        // onWebSocketConnect 里面$_GET $_SERVER是可用的
        // var_dump($_GET, $_SERVER);
    };
}; 
*/

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

