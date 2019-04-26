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
 * 分布式时只写一台服务器，其他可注释掉
 * 
 * 如何分布式GatewayWorker
 * GatewayWorker通过Register服务来建立划分集群。同一集群使用相同的Register服务ip和端口，
 * 即Gateway 和 businessWorker的注册服务地址
 * ($gateway->registerAddress $businessworker->registerAddress)指向同一台Register服务。
 */
use \Workerman\Worker;
use \GatewayWorker\Register;

// 自动加载类
require_once __DIR__ . '/../../vendor/autoload.php';

// register 必须是text协议
$register = new Register('text://0.0.0.0:1238');//WorkerMan中自定义的一个协议，格式为文本+换行

// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

