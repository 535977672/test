<?php

/**
 * 入口文件
 * webservice样例
 */
use \Workerman\Worker;
use \Workerman\WebServer;
require_once __DIR__ . '/../Workerman/Autoloader.php';

// 0.0.0.0 代表监听本机所有网卡，不需要把0.0.0.0替换成其它IP或者域名
// 这里监听8080端口，如果要监听80端口，需要root权限，并且端口没有被其它程序占用
$webserver = new WebServer('http://0.0.0.0:8080');

$webserver->addRoot('www.webhttp.com', 'D:/mf/test');
//host 127.0.0.1 www.webhttp.com
//http://www.webhttp.com:8080/workermans/web/index.php?wwe=232131

$webserver->count = 1;

Worker::runAll();