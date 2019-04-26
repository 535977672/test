<?php

/**
 * qqmt消息队列遥感传输协议 发布/订阅
 * 
 * https://www.cnblogs.com/lexiaofei/p/8251917.html
 * http://www.runoob.com/w3cnote/mqtt-intro.html
 * 
 * 是一种基于轻量级代理的发布/订阅模式的消息传输协议
 * 运行在TCP协议栈之上，为其提供有序、可靠、双向连接的网络连接保证。
 * 
 * MQTT采用代理的发布/订阅模式实现了发布者和订阅者的解耦(decouple)，
 * 因此，在MQTT协议中有三种角色：
 * 代理服务器
 * 发布者客户端
 * 订阅者客户端
 * 其中发布者和订阅者互不干扰，也就是说发布者和订阅者互不知道对方的存在，它们只知道代理服务器，
 * 代理服务器负责将来自发布者的消息进行存储处理并将这些消息发送到正确的订阅者中去
 * 
 * MQTT通过“主题”实现将消息从发布者客户端送达至接收者客户端。
 * “主题”是附加在应用消息上的一个标签，发布者客户端将“主题”和“消息”发送至代理服务器，
 * 代理服务器将该消息转发至每一个订阅了该“主题”的订阅者客户端
 * 
 * 一个主题名可以由多个主题层级组成，每一层通过“/”斜杠分隔开 baidu/F1 baidu/F2\
 * 
 * PUBLISH报文中的QoS标志位
 * QoS=0，协议对此等级应用信息不要求回应确认，也没有重发机制，这类信息可能会发生消息丢失或重复，取决于TCP/IP提供的尽最大努力交互的数据包服务。
 * 最少一次(At least once delivery)：QoS=1，确保信息到达，但消息重复可能发生，发送者如果在指定时间内没有收到PUBACK控制报文，应用信息会被重新发送。
 * 仅仅一次(Exactlyonce delivery)：QoS=2，最高级别的服务质量，消息丢失和重复都是不可接受的。
 */

//php qqmt_publish.php start
//发布publish
require_once __DIR__ . '/../Workerman/Autoloader.php';
require_once __DIR__ . '/../Plugins/mqtt-master/src/Client.php';
require_once __DIR__ . '/../Plugins/mqtt-master/src/Protocols/Mqtt.php';
use Workerman\Worker;

$worker = new Worker('websocket://0.0.0.0:2348');

$mqtt = null;

$worker->count = 1;

$worker->name = 'MyqqmtpublishWorker';

$worker->onWorkerStart = function(){
    global $mqtt;
    $mqtt = new Workerman\Mqtt\Client('mqtt://test.mosquitto.org:1883');
    $mqtt->onConnect = function($mqtts) {
       $mqtts->publish('test', 'hello workerman mqtt');
    };
    
    $mqtt->onConnect = function($mqtts) {
       $mqtts->publish('test', 'hello workerman mqtt');
    };
    $mqtt->connect();
};


$worker->onMessage = function($connection, $data){
    global $mqtt;
    for($i = 0; $i < intval($data); $i++){
        $mqtt->publish('test', json_encode(['content'=>'hello workerman mqtt', 'time'=>time(), 'c_id'=>$connection->id]), '', function (\Exception $exception)use($connection){
            //No connection to broker
            $connection->send(json_encode($exception->getMessage() . time()));
        });
    }
    $connection->send(json_encode('publish success' . time()));
};
Worker::runAll();