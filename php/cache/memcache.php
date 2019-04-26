<?php
//memcache
/*
    Memcache {
        bool add ( string $key , mixed $var [, int $flag [, int $expire ]] )
        bool addServer ( string $host [, int $port = 11211 [, bool $persistent [, int $weight [, int $timeout [, int $retry_interval [, bool $status [, callback $failure_callback [, int $timeoutms ]]]]]]]] )
        bool close ( void )
        bool connect ( string $host [, int $port [, int $timeout ]] )
        int decrement ( string $key [, int $value = 1 ] )
        bool delete ( string $key [, int $timeout = 0 ] )
        bool flush ( void )
        string get ( string $key [, int &$flags ] )
        array getExtendedStats ([ string $type [, int $slabid [, int $limit = 100 ]]] )
        int getServerStatus ( string $host [, int $port = 11211 ] )
        array getStats ([ string $type [, int $slabid [, int $limit = 100 ]]] )
        string getVersion ( void )
        int increment ( string $key [, int $value = 1 ] )
        mixed pconnect ( string $host [, int $port [, int $timeout ]] )
        bool replace ( string $key , mixed $var [, int $flag [, int $expire ]] )
        bool set ( string $key , mixed $var [, int $flag [, int $expire ]] )
        bool setCompressThreshold ( int $threshold [, float $min_savings ] )
        bool setServerParams ( string $host [, int $port = 11211 [, int $timeout [, int $retry_interval = false [, bool $status [, callback $failure_callback ]]]]] )
    }
    Memcache::add — 增加一个条目到缓存服务器
    Memcache::addServer — 向连接池中添加一个memcache服务器
    Memcache::close — 关闭memcache连接
    Memcache::connect — 打开一个memcached服务端连接
    Memcache::decrement — 减小元素的值
    Memcache::delete — 从服务端删除一个元素
    Memcache::flush — 清洗（删除）已经存储的所有的元素
    Memcache::get — 从服务端检回一个元素
    Memcache::getExtendedStats — 缓存服务器池中所有服务器统计信息
    Memcache::getServerStatus — 用于获取一个服务器的在线/离线状态
    Memcache::getStats — 获取服务器统计信息
    Memcache::getVersion — 返回服务器版本信息
    Memcache::increment — 增加一个元素的值
    Memcache::pconnect — 打开一个到服务器的持久化连接
    Memcache::replace — 替换已经存在的元素的值
    Memcache::set — Store data at the server
    Memcache::setCompressThreshold — 开启大值自动压缩
    Memcache::setServerParams — 运行时修改服务器参数和状态
 * 
 * 
 * redis/memcache：如果要做分布式存储可以使用，否则不推荐使用，
 * 因为redis/memcache需要tcp通信，即使本地也需要unix domain socket通信，其效率远不如共享内存的apcu
 */


//检查一个扩展是否已经加载。大小写不敏感。
if (!extension_loaded('memcache')) {
    return ;
}

$handler = new Memcache();

//1.Memcache::addServer — 向连接池中添加一个memcache服务器
$handler->addserver('127.0.0.1', 11211, false, 1, 1);
//$handler->flush();

//2.Memcache::add — 增加一个条目到缓存服务器
//如果这个key已经存在返回FALSE。
$handler->add('key1', '1', false, 500);

//3.Memcache::close — 关闭memcache连接
$handler->close();
$handler->set('key2', '2', false, 500);

//4.Memcache::connect — 打开一个memcached服务端连接 
//Memcache::pconnect — 打开一个到服务器的持久化连接
$handler->connect('127.0.0.1', 11211, 1);

//5.Memcache::decrement — 减小元素的值
//6.Memcache::increment — 增加一个元素的值
$handler->set('key3', 3, false, 500);
$handler->decrement('key3', 2);
$handler->set('key4', 4, false, 500);
$handler->increment('key4', 2);

//7.Memcache::delete — 从服务端删除一个元素
$handler->set('key5', 5, false, 500);
$handler->delete('key5');

//8.Memcache::get — 从服务端检回一个元素
$key1 = $handler->get('key1');
$key5 = $handler->get('key5');
var_dump($key1, $key5);//1, false

//9.Memcache::getExtendedStats — 缓存服务器池中所有服务器统计信息
$extendedstats = $handler->getextendedstats();
var_dump($extendedstats);

//10.Memcache::getServerStatus — 用于获取一个服务器的在线/离线状态
$serverstatus = $handler->getserverstatus('127.0.0.1', 11211);
var_dump($serverstatus);//1

//11.Memcache::getStats — 获取服务器统计信息
$stats = $handler->getstats();
var_dump($stats);

//12.Memcache::getVersion — 返回服务器版本信息
$version = $handler->getversion();
var_dump($version);

//13.Memcache::replace — 替换已经存在的元素的值
$handler->set('key6', 6, false, 500);
$handler->replace('key6', 10);

//14.Memcache::set — Store data at the server
$handler->set('key7', 7, false, 500);

//15.Memcache::setCompressThreshold — 开启大值自动压缩
$handler->setcompressthreshold(20000, 0.2);

//16.Memcache::setServerParams — 运行时修改服务器参数和状态
// 增加一台离线服务器
$handler->addServer('127.0.0.1', 11211, false, 1, 1, -1, false);
$handler->setServerParams('127.0.0.1', 11211, 1, 15, true);
$serverstatus = $handler->getserverstatus('127.0.0.1', 11211);
var_dump($serverstatus);//1
$extendedstats = $handler->getextendedstats();
var_dump($extendedstats);


//17.Memcache::flush — 清洗（删除）已经存储的所有的元素
//必须等待至少一秒后刷新
//$handler->flush();


$handler->close();
