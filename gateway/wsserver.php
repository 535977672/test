<?php
require_once 'GatewayClient.php';
use GatewayClient\Gateway;

$client_id = $_GET['client_id'];
$message = $_GET['message'];

$json = json_encode([
    'client_id' => $client_id,
    'message' => $message,
    'type' => 'message-get',
    'data'=>[
        ['id'=>10, 'code'=>1001],
        ['id'=>11, 'code'=>1002]
    ]
], JSON_UNESCAPED_UNICODE);


////做一次
Gateway::$registerAddress = '127.0.0.1:1238';
// 假设用户已经登录，用户uid和群组id在session中
$uid      = $client_id;//$_SESSION['uid'];
$group_id = 10;//$_SESSION['group'];
// client_id与uid绑定
Gateway::bindUid($client_id, $uid);
// 加入某个群组（可调用多次加入多个群组）
Gateway::joinGroup($client_id, $group_id);

// 向任意uid的网站页面发送数据
Gateway::sendToUid($uid, $json);
// 向任意群组的网站页面发送数据
Gateway::sendToGroup($group_id, $json);

exit($json);