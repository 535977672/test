<?php

/**
 * 入口文件
 */
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';

//TcpConnection::$maxPackageSize = 1024000;
$global_uid = 0;

// 当客户端连上来时分配uid，并保存连接，并通知所有客户端
//$connection 连接对象，即TcpConnection实例，用于操作客户端连接，如发送数据，关闭连接等
function handle_connection($connection)
{
    global $text_worker, $global_uid;
    // 为这个连接分配一个uid
    $connection->uid = ++$global_uid;
    
    $data = [
        'client_id' => $connection->id,
        'type' => 'connect'
    ];
    $connection->send(json_encode($data, JSON_UNESCAPED_UNICODE));
    
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $message)
{
    global $text_worker,$is_send;
    
    if($is_send){
        
    }else{
        
    }
    
    //心跳检测
    if($message == 'ping'){
        $time = time();
        $_SESSION[$time] = $time.'-'.$connection->id;
        $data = [
            'client_id' => $connection->id,
            'type' => 'ping',
            'session' => $_SESSION
        ];
        $connection->send(json_encode($data));
        //exit();//exit结束会清空 $_SESSION
        //浏览器刷新会清空 $_SESSION
    }else{
         $data = [
            'client_id' => $connection->id,
            'type' => 'message',
            'message' => $message
        ];
        $connection->send(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    
    
//    foreach($text_worker->connections as $conn)
//    {
//        $conn->send("user[{$connection->uid}] said: $data");
//    }
//    因为多进程时多个客户端可能连接到不同的进程中，进程间的客户端是隔离的，
//    无法直接通讯，也就是A进程中无法直接操作B进程的客户端connection对象发送数据。
//    (要做到这点，需要进程间通讯，建议用GatewayWorker)。
    
    
}

// 当客户端断开时，广播给所有客户端  
// 心跳检测
function handle_close($connection)
{
    global $text_worker;
//    foreach($text_worker->connections as $conn)
//    {
//        $conn->send("user[{$connection->uid}] logout");
//    }
    
    $data = [
             'client_id' => $connection->id,
             'type' => 'close'
         ];
    $connection->send(json_encode($data, JSON_UNESCAPED_UNICODE));
    
    echo "connection closed\n";
}

//websocket text frame tcp  udp unix

// 创建一个文本协议的Worker监听2347接口
$text_worker = new Worker("websocket://0.0.0.0:2347");  //客户端即能通过ipv4地址访问，也能通过ipv6地址访问 监听地址写[::] new Worker('http://[::]:8080')


//Worker属性
//int Worker::$id 当前worker进程的id编号，范围为0到$worker->count-1  1个worker实例有多个进程
//        windows系统由于不支持进程数count的设置，只有id只有一个0号。windows系统下不支持同一个文件初始化两个Worker监听
//int Worker::$count Worker实例启动多少个进程 不默认为1 CPU核数的1-3倍
//string Worker::$name  Worker实例的名称，方便运行status命令时识别进程。不设置时默认为none
//string Worker::$protocol 设置当前Worker实例的协议类
//string Worker::$transport worker传输层协议，目前只支持3种(tcp、udp、ssl)。不设置默认为tcp
//bool Worker::$reusePort worker是否开启监听端口复用(socket的SO_REUSEPORT选项)，默认为false，不开启 PHP>=7.0
//
//array Worker::$connections 当前进程 的所有的客户端连接对象 不要对这个属性做修改操作
//
//static string Worker::$stdoutFile linux此属性为全局静态属性，如果以守护进程方式(-d启动)运行，则所有向终端的输出(echo var_dump等)都会被重定向到stdoutFile指定的文件中。
//static string Worker::$pidFile 全局静态属性，用来设置WorkerMan进程的pid文件路径
//static string Worker::$logFile  workerman自身相关启动停止等日志 不含业务日志类
//
//string Worker::$user Worker实例以哪个用户运行 root、www-data、apache、nobody
//bool Worker::$reloadable 设置当前Worker实例是否可以reload，即收到reload信号后是否退出重启。默认为true Gateway/Worker模型中的gateway进程false
//static bool Worker::$daemonize 否以daemon(守护进程)方式运行 启动命令使用了 -d参数，自动设置为true
//static Event Worker::$globalEvent 全局静态属性，为全局的eventloop实例，可以向其注册文件描述符的读写事件或者信号事件



//Worker回调属性
//callback Worker::$onWorkerStart 设置Worker子进程启动时的回调函数，每个子进程启动时都会执行。
//callback Worker::$onWorkerReload  设置Worker收到reload信号后执行的回调，会退出进程 Worker::$reloadable->false
//callback Worker::$onConnect 当客户端与Workerman建立连接时(TCP三次握手完成后)触发的回调函数 
//                            每个连接只会触发一次onConnect
//                            此时只能做$connection->getRemoteIp()获得对方ip
//callback Worker::$onMessage Workerman收到数据时
//callback Worker::$onClose 如果对端是由于断网或者断电等极端情况断开的连接，
//                            这时由于无法及时发送tcp的fin包给workerman，workerman就无法得知连接已经断开，
//                            也就无法及时触发onClose =>通过应用层心跳来解决    
//    
//callback Worker::$onBufferFull  每个连接都有一个单独的应用层发送缓冲区，如果客户端接收速度小于服务端发送速度，
//                                数据会在应用层缓冲区暂存，如果缓冲区满则会触发 
//                                当前连接动态设置缓冲区大为TcpConnection::$maxSendBufferSize，默认值为1MB
//                                TcpConnection::$defaultMaxSendBufferSize 设置所有连接默认缓冲区的大小
//                                onBufferFull时要停止向对端发送数据，等待发送缓冲区的数据被发送完毕(onBufferDrain事件)等。
//                                onBufferFull时继续对端发送数据---》》》onError
//callback Worker::$onBufferDrain 应用层发送缓冲区数据全部发送完毕后触发 onBufferDrain恢复写入数据   
//callback Worker::$onError 当客户端的连接上发生错误时触发

//Worker接口
//void Worker::runAll(void) 运行所有Worker实例。执行后将永久阻塞，后面的代码不会被执行
//                           运行多个实例$ws_worker = new Worker('websocket://0.0.0.0:4567');
//void Worker::stopAll(void) 停止当前进程（子进程）的所有Worker实例并退出。 》》相当于exit/die 
//                            主进程会立刻重新启动一个进程
//void Worker::listen(void) 用于实例化Worker后执行监听




//设置启动4数量
// 只启动1个进程，这样方便客户端之间传输数据
$text_worker->count = 1;
// 设置实例的名称
$text_worker->name = 'MyWebsocketWorker';

//$worker = new Worker('tcp://0.0.0.0:8686');
//$worker->protocol = 'Workerman\\Protocols\\Http'; 
//等价于 $worker = new Worker("http://0.0.0.0:2347")

// 所有的打印输出全部保存在/tmp/stdout.log文件中
//Worker::$stdoutFile = '/tmp/stdout.log';// /dev/null

Worker::$logFile = __DIR__ . '/tmp/workerman.log';
//daemon方式运行时也能看到echo、var_dump、var_export等函数打印
Worker::$stdoutFile = '/tmp/stdout.log';
    
    
    
$text_worker->onWorkerStart = function($worker){
    echo 'Worker Starting \n';
};

$is_send = true;//是否发送数据

$text_worker->onConnect = 'handle_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose = 'handle_close';


$text_worker->onBufferFull = function($connection){
    $is_send = false;
    echo 'Buffer Full \n';
};

$text_worker->onBufferDrain = function($connection){
    $is_send = true;
    echo 'Buffer Drain \n';
};

$text_worker->onError = function($connection, $code, $msg){
    echo 'Error code:'.$code.' msg:'.$msg.' \n';
};

//Worker::stopAll();
//Worker::listen();

Worker::runAll();


//Connection类提供的接口
//每个客户端连接对应一个Connection对象，onMessage onClose send close
//Worker是一个监听容器，负责接受客户端连接，并把连接包装成connection对象式
//
//属性
//int Connection::$id 连接的id。这是一个自增的整数。 workerman是多进程的，每个进程内部会维护一个自增的connection id，多个进程之间会有重复
//string Connection::$protocol 当前连接的协议类    
//Worker Connection::$worker 此属性为只读属性，即当前connection对象所属的worker实例  $connection->worker->connections   
//int Connection::$maxSendBufferSize 当前连接应用层发送缓冲区    
//static int Connection::$defaultMaxSendBufferSize 全局静态属性 设置所有连接的默认应用层发送缓冲区大小
//static int Connection::$maxPackageSize 全局静态属性，用来设置每个连接能够接收的最大包包长。不设置默认为10MB 大于-》非法数据，连接会断开
//
//回调
//callback Connection::$onMessage 只针对当前连接有效
//callback Connection::$onClose
//callback Connection::$onBufferFull
//callback Connection::$onBufferDrain
//callback Connection::$onError
//
//接口
//mixed Connection::send(mixed $data [,$raw = false]) 向客户端发送数据 
//        应用层发送缓冲区 -》 系统层socket发送缓冲区 -》 对端socket接收缓冲区
//        异步发送 无法直接确认成功 -》》数据库自行实现确认
//        true 表示数据已经成功写入到该连接的操作系统层的socket发送缓冲区
//        null 表示数据已经写入到该连接的应用层发送缓冲区，等待向系统层socket发送缓冲区写入
//        false 表示发送失败，失败原因可能是客户端连接已经关闭，或者该连接的应用层发送缓冲区已满
//        send返回true，仅仅代表数据已经成功写入到该连接的操作系统socket发送缓冲区，
//        并不意味着数据已经成功的发送给对端socket接收缓冲区，
//        更不意味着对端应用程序已经从本地socket接收缓冲区读取了数据。
//        
//string Connection::getRemoteIp() 客户端ip Remote远程
//int Connection::getRemotePort() 客户端端口
//void Connection::close(mixed $data = '') 安全的关闭连接 调用close会等待发送缓冲区的数据发送完毕后才关闭连接，并触发连接的onClose回调。
//void Connection::destroy() 立刻关闭连接。立刻触发该连接的onClose
//void Connection::pauseRecv(void) 当前连接停止接收数据 上传流量控制非常有用
//void Connection::resumeRecv(void) 使当前连接继续接收数据
//void Connection::pipe(TcpConnection $target_connection) pipe管道 
//      将当前连接的数据流导入到目标连接。内置了流量控制。此方法做TCP代理非常有用
//
//
//AsyncTcpConnection类 
//TcpConnection的子类，用于异步创建一个TcpConnection连接
//void AsyncTcpConnection::__construct(string $remote_address, $context_option = null)
//        $remote_address ws://www.baidu.com:80 ...
//        可以用new AsyncTcpConnection('ws://...')像浏览器一样在workerman里发起websocket连接远程websocket服务器，
//        但是不能以 new AsyncTcpConnection('websocket://...')的形式在workerman里发起websocket连接。
//    
//void AsyncTcpConnection::connect()  执行异步连接操作。此方法会立刻返回
//
//void AsyncUdpConnection::send(string $data) $data 向服务端发送的数据    
//void AsyncTcpConnection::reConnect(float $delay = 0) 一般在onClose回调中调用，实现断线重连
//transport 设置传输属性，可选值为 tcp 和 ssl，默认是tcp    
    






//php start.php start  start -d  stop  restart  平滑重启reload status 查看连接状态connections
//php -l yourfile.php检查下是否有语法错误
//php -m会列出所有php cli已安装的扩展
//lscpu   # 可以查看最大线程数，CPU个数，CPU核心数，CPU线程数
//ulimit -u 查看系统中的进程上限 软限制可改 ulimit -u 5120
//ulimit -s 可以查看默认的线程栈大小


//在WorkerMan中如果想在内存中永久保存某些数据资源，可以将资源放到全局变量中或者类的静态成员中。
//global $connection_count;

//并发概念太模糊，这里以两种可以量化的指标并发连接数和并发请求数来说明。

//并发连接数是指服务器当前时刻一共维持了多少TCP连接，而这些连接上是否有数据通讯并不关注，
//例如一台消息推送服务器上可能维持了百万的设备连接，由于连接上很少有数据通讯，
//所以这台服务器上负载可能几乎为0，只要内存足够，还可以继续接受连接。

//并发请求数一般用QPS（服务器每秒处理多少请求）来衡量，
//而当前时刻服务器上有多少个tcp连接并不十分关注。
//例如一台服务器只有10个客户端连接，每个客户端连接上每秒有1W个请求，
//那么要求服务端需要至少能支撑10*1W=10W每秒的吞吐量（QPS）。
//假设10W吞吐量每秒是这台服务器的极限，
//如果每个客户端每秒发送1个请求给服务端，那么这台服务器能够支撑10W个客户端。

//并发连接数受限于服务器内存，一般24G内存workerman服务器可以支持大概120W并发连接。

//并发请求数受限于服务器cpu处理能力，一台24核workerman服务器可以达到45W每秒的吞吐量(QPS)，
//实际值根据业务复杂度以及代码质量有所变化。