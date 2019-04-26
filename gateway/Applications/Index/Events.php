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

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
//Lib\Gateway类是Gateway/BusinessWorker模型中给客户端发送数据的类。


/**
 * 如果你的项目是长连接并且需要客户端与客户端之间通讯，建议使用GatewayWorker。
 * 短连接或者不需要客户端与客户端之间通讯的项目建议使用Workerman。
 * GatewayWorker不支持UDP监听，所以UDP服务请选择Workerman。
 * 如果你是一个有多进程socket编程经验的人，喜欢定制自己的进程模型，可以选择Workerman
 * 
 * 心跳
 * 
 * 正常的情况客户端断开连接会向服务端发送一个fin包，服务端收到fin包后得知客户端连接断开，
 * 则立刻触发onClose事件回调。
 * 
 * 但是有些极端情况如客户端掉电、网络关闭、拔网线、路由故障等，
 * 这些极端情况客户端无法发送fin包给服务端，服务端便无法知道连接已经断开。
 * 
 * 如果客户端与服务端定时有心跳数据传输，则会比较及时的发现连接断开，触发onClose事件回调。
 * 
 * 另外路由节点防火墙会关闭长时间不通讯的socket连接，导致socket长连接断开。
 * 所以需要客户端与服务端定时发送心跳数据保持连接不被断开。
 * 
 * 客户端定时每X秒(推荐小于60秒)向服务端发送特定数据，服务端设定为X秒没有收到客户端心跳则认为客户端掉线，
 * 并关闭连接触发onClose回调。
 * 这样即通过心跳检测请求维持了连接(避免连接因长时间不活跃而被网关防火墙关闭)，
 * 也能让服务端比较及时的知道客户端是否异常掉线。
 * 
 * 目前GatewayWorker支持两种心跳检测，服务端设定多少秒内没收到心跳关闭连接(推荐)，
 * 同时也支持服务端定时向客户端发送心跳数据(不推荐)。
 * 
 * 客户端定时发送心跳(推荐)
 * 客户端定时(间隔最好小于60秒)向服务端发送心跳 start_gateway_websocket.php
 */


//1、每个BusinessWorker进程启动时，都会触发Events::onWorkerStart($businessworker)回调（此特性Gateway版本>=2.0.4才支持）。
//
//2、当客户端连接到Gateway时，会触发Events::onConnect($client_id)回调。
//
//3、当客户端发来数据时，会触发Events::onMessage($client_id, $data)回调。
//
//4、当客户端关闭时，会触发Events::onClose($client_id)回调。
//
//5、每个BusinessWorker进程退出时，都会触发Events::onWorkerStop($businessworker)回调（此特性Gateway版本>=2.0.4才支持）。注意如果进程是非正常退出，例如被kill可能无法触发onWorkerStop。

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 * 
 * onWorkerStart进程启动事件(进程事件)、
 * onConnect连接事件(客户端事件)、
 * onMessage消息事件（客户端事件）、
 * onClose连接关闭事件（客户端事件）、
 * onWorkerStop进程退出事件（进程事件）
 * 
 * 无返回值，任何返回值都会被视为无效的
 */
class Events
{
    /**
     * 当客户端连接上gateway进程时(TCP三次握手完毕时)触发的回调函数。
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 只能通过$_SERVER['REMOTE_ADDR']获得对方ip  
     * 客户端发送鉴权数据，例如某个token或者用户名密码之类，在onMesssge里做鉴权。
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        $data = [
            'client_id' => $client_id,
            'type' => 'connect'
        ];
        // 向当前client_id发送数据 
        Gateway::sendToClient($client_id, json_encode($data, JSON_UNESCAPED_UNICODE));
        // 向所有人发送
        //Gateway::sendToAll("$client_id login\r\n");
    }
    
    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     * 
     * 业务消息通过前端ajax-》API处理逻辑级消息发送
     * 考虑速度和性能
     */
    public static function onMessage($client_id, $message)
    {
        //$message = json_decode($data, true);

        //心跳检测
        if($message == 'ping'){
            $time = time();
            $_SESSION[$time] = $time.'-'.$client_id;
            $data = [
                'client_id' => $client_id,
                'type' => 'ping',
                'session' => $_SESSION
            ];
            Gateway::sendToClient($client_id, json_encode($data));
            //exit();//exit结束会清空 $_SESSION
            //浏览器刷新会清空 $_SESSION
        }else{
       
            //$_SESSION
            //超全局数组$_SESSION和PHP自身的$_SESSION功能基本相同。
            //每个client_id对应一个$_SESSION数组，
            //$_SESSION数组中可以保存对应客户端的会话数据，
            //对应的client_id的后续请求可以直接使用这个数组中的数据，而不用去反复读取存储。
            //
            //WebServer(PHP-FPM/Apache等)中的$_SESSION无法互通 无需调用session_start等
            //数据保存在Gateway进程内存中，无磁盘IO  内存中  内存中  内存中  内存中  内存中 
            //客户端连接断开后，对应的客户端$_SESSION将会清除
            //定时器中不要直接使用$_SESSION变量  使用Gateway::getSession($client_id)

            //$_SERVER
            //每个客户端都会连接在gateway进程上，当gateway进程收到客户端的数据时，
            //会将客户端的ip端口及client_id连通消息传递给worker进程，
            //worker进程初始化$_SERVER数组便可以使用了。
            //REMOTE_ADDR // 客户端ip（如果客户端处于局域网，则是客户端所在局域网的出口ip）
            //REMOTE_PORT // 客户端端口（如果客户端处于局域网，则是客户端所在局域网的出口端口）
            //GATEWAY_ADDR // gateway所在服务器的ip
            //GATEWAY_PORT // geteway监听的端口，这对于多端口应用中在Event.php里区分客户端连的是哪个端口非常有用
            //GATEWAY_CLIENT_ID // 全局唯一的客户端id       

            $data = [
                'client_id' => $client_id,
                'type' => 'message',
                'message' => $message,
                'session' => $_SESSION
            ];
            Gateway::sendToClient($client_id, json_encode($data, JSON_UNESCAPED_UNICODE));
        
        
//            //GatewayWorker\Lib\Gateway类
//            Gateway::sendToAll();//异步消息 向所有客户端连接(或者 client_id_array 指定的客户端连接)广播消息     
//            Gateway::sendToClient();//向某个client_id对应的连接发消息    
//            Gateway::closeClient();//踢掉某个客户端，并以$message通知被踢掉客户端  
//            Gateway::isOnline();//判断$client_id是否还在线 是否在线取决于对应client_id是否触发过onClose回调。断电类无效-》心跳
//
//            Gateway::bindUid(); //client_id与uid绑定，以便通过Gateway::sendToUid($uid)发送数据
//                                //uid与client_id是一对多的关系，系统允许一个uid下有多个client_id
//                                //uid和client_id映射关系存储在Gateway进程内存中
//            Gateway::unbindUid();//client_id下线（连接断开）时会自动执行解绑
//            Gateway::isUidOnline();//判断$uid是否在线 uid绑定的client_id有至少有一个在线=>1 onClose回调。断电类无效-》心跳
//            Gateway::getClientIdByUid();//array 
//            Gateway::getUidByClientId();//字符串或者数字  onClose回调中无法使用此接口
//            Gateway::sendToUid();//向uid绑定的所有在线client_id发送数据
//
//            Gateway::joinGroup();//将client_id加入某个组，以便通过Gateway::sendToGroup发送数据。 房间广播
//            Gateway::leaveGroup();//将client_id从某个组中删除   client_id下线自动删除    
//            Gateway::ungroup();//取消分组，或者说解散分组            
//            Gateway::sendToGroup();//向某个分组的所有在线client_id发送数据            
//            Gateway::getClientIdCountByGroup();//获取某个组的在线client_id数量   
//            Gateway::getClientSessionsByGroup();//获取某个分组所有在线client_id信息。  
//
//            Gateway::getAllClientIdCount();//获取当前在线连接总数（多少client_id在线）。
//
//            Gateway::getAllClientSessions();//获取当前所有在线client_id信息。      
//            Gateway::setSession($client_id, $session);//是整体赋值,覆盖  设置某个client_id对应的session 
//                    //$_SESSION赋值与Gateway::setSession同时操作同一个$client_id，可能会造成session值与预期效果不符
//                    //操作当前用户用$_SESSION['xx']=xxx方式赋值即可，操作其他用户session可以使用Gateway::setSession接口
//            Gateway::updateSession();//部分更新  更新某个client_id对应的session
//            Gateway::getSession($client_id);//获取某个client_id对应的session
//            
//            Gateway::getClientIdListByGroup();//获取某个分组所有在线client_id列表    
//            Gateway::getAllClientIdList();//获取全局所有在线client_id列表
//            Gateway::getUidListByGroup();    
//            Gateway::getUidCountByGroup();
//            Gateway::getAllUidList();
//            Gateway::getAllUidCount();
//            Gateway::getAllGroupIdList();//获取全局所有在线group id列表
        }
    }

    /**
     * 当用户断开连接时触发
     * 无法使用Gateway::getSession()来获得当前用户的session数据，但是仍然可以使用$_SESSION变量获得
     * 
     * 断网断电等极端情况可能无法及时触发onClose回调
     * 检测这种极端情况需要心跳检测
     * 
     * @param int $client_id 连接id
     * 
     */
    public static function onClose($client_id)
    {
        $data = [
             'client_id' => $client_id,
             'type' => 'close'
         ];
        Gateway::sendToClient($client_id, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
    
    public static function onWorkerStart($businessWorker){
        //BusinessWorker $businessWorker进程实例
        //当businessWorker进程启动时触发。每个进程生命周期内都只会触发一次。
        //局初始化工作，例如设置定时器，初始化redis等连接等
    }
    
        
    public static function onWorkerStop($businessWorker){
        //当businessWorker进程退出时触发。每个进程生命周期内都只会触发一次。
        //可以在这里为每一个businessWorker进程做一些清理工作，例如保存一些重要数据等。
        //注意：某些情况将不会触发onWorkerStop，例如业务出现致命错误FatalError，或者进程被强行杀死等情况。
    }
}
