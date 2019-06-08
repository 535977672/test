<?php
/**
 * run with command 
 * php start.php start
 */

ini_set('display_errors', 'on');
use Workerman\Worker;
require_once __DIR__ . '/../Workerman/Autoloader.php';

// 检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension.\n");
}

if(!extension_loaded('posix'))
{
    exit("Please install posix extension\n");
}

// 标记是全局启动
define('GLOBAL_START', 1);


// 加载所有Applications/*/start.php，以便启动所有服务
foreach(glob(__DIR__.'/start_*.php') as $start_file)
{
    require_once $start_file;
}
// 运行所有服务
Worker::runAll();