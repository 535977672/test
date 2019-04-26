<?php
//memcached
/*
    Memcached {
        __construct ([ string $persistent_id ] )
        public bool add ( string $key , mixed $value [, int $expiration ] )
        public bool addByKey ( string $server_key , string $key , mixed $value [, int $expiration ] )
        public bool addServer ( string $host , int $port [, int $weight = 0 ] )
        public bool addServers ( array $servers )
        public bool append ( string $key , string $value )
        public bool appendByKey ( string $server_key , string $key , string $value )
        public bool cas ( float $cas_token , string $key , mixed $value [, int $expiration ] )
        public bool casByKey ( float $cas_token , string $server_key , string $key , mixed $value [, int $expiration ] )
        public int decrement ( string $key [, int $offset = 1 ] )
        public int decrementByKey ( string $server_key , string $key [, int $offset = 1 [, int $initial_value = 0 [, int $expiry = 0 ]]] )
        public bool delete ( string $key [, int $time = 0 ] )
        public bool deleteByKey ( string $server_key , string $key [, int $time = 0 ] )
        public array deleteMulti ( array $keys [, int $time = 0 ] )
        public bool deleteMultiByKey ( string $server_key , array $keys [, int $time = 0 ] )
        public array fetch ( void )
        public array fetchAll ( void )
        public bool flush ([ int $delay = 0 ] )
        public mixed get ( string $key [, callback $cache_cb [, float &$cas_token ]] )
        public array getAllKeys ( void )
        public mixed getByKey ( string $server_key , string $key [, callback $cache_cb [, float &$cas_token ]] )
        public bool getDelayed ( array $keys [, bool $with_cas [, callback $value_cb ]] )
        public bool getDelayedByKey ( string $server_key , array $keys [, bool $with_cas [, callback $value_cb ]] )
        public mixed getMulti ( array $keys [, array &$cas_tokens [, int $flags ]] )
        public array getMultiByKey ( string $server_key , array $keys [, string &$cas_tokens [, int $flags ]] )
        public mixed getOption ( int $option )
        public int getResultCode ( void )
        public string getResultMessage ( void )
        public array getServerByKey ( string $server_key )
        public array getServerList ( void )
        public array getStats ( void )
        public array getVersion ( void )
        public int increment ( string $key [, int $offset = 1 ] )
        public int incrementByKey ( string $server_key , string $key [, int $offset = 1 [, int $initial_value = 0 [, int $expiry = 0 ]]] )
        public bool isPersistent ( void )
        public bool isPristine ( void )
        public bool prepend ( string $key , string $value )
        public bool prependByKey ( string $server_key , string $key , string $value )
        public bool quit ( void )
        public bool replace ( string $key , mixed $value [, int $expiration ] )
        public bool replaceByKey ( string $server_key , string $key , mixed $value [, int $expiration ] )
        public bool resetServerList ( void )
        public bool set ( string $key , mixed $value [, int $expiration ] )
        public bool setByKey ( string $server_key , string $key , mixed $value [, int $expiration ] )
        public bool setMulti ( array $items [, int $expiration ] )
        public bool setMultiByKey ( string $server_key , array $items [, int $expiration ] )
        public bool setOption ( int $option , mixed $value )
        public bool setOptions ( array $options )
        public void setSaslAuthData ( string $username , string $password )
        public bool touch ( string $key , int $expiration )
        public bool touchByKey ( string $server_key , string $key , int $expiration )
    }

        Memcached::add — 向一个新的key下面增加一个元素
        Memcached::addByKey — 在指定服务器上的一个新的key下增加一个元素
        Memcached::addServer — 向服务器池中增加一个服务器
        Memcached::addServers — 向服务器池中增加多台服务器
        Memcached::append — 向已存在元素后追加数据
        Memcached::appendByKey — 向指定服务器上已存在元素后追加数据
        Memcached::cas — 比较并交换值
        Memcached::casByKey — 在指定服务器上比较并交换值
        Memcached::__construct — 创建一个Memcached实例
        Memcached::decrement — 减小数值元素的值
        Memcached::decrementByKey — Decrement numeric item's value, stored on a specific server
        Memcached::delete — 删除一个元素
        Memcached::deleteByKey — 从指定的服务器删除一个元素
        Memcached::deleteMulti — Delete multiple items
        Memcached::deleteMultiByKey — Delete multiple items from a specific server
        Memcached::fetch — 抓取下一个结果
        Memcached::fetchAll — 抓取所有剩余的结果
        Memcached::flush — 作废缓存中的所有元素
        Memcached::get — 检索一个元素
        Memcached::getAllKeys — Gets the keys stored on all the servers
        Memcached::getByKey — 从特定的服务器检索元素
        Memcached::getDelayed — 请求多个元素
        Memcached::getDelayedByKey — 从指定的服务器上请求多个元素
        Memcached::getMulti — 检索多个元素
        Memcached::getMultiByKey — 从特定服务器检索多个元素
        Memcached::getOption — 获取Memcached的选项值
        Memcached::getResultCode — 返回最后一次操作的结果代码
        Memcached::getResultMessage — 返回最后一次操作的结果描述消息
        Memcached::getServerByKey — 获取一个key所映射的服务器信息
        Memcached::getServerList — 获取服务器池中的服务器列表
        Memcached::getStats — 获取服务器池的统计信息
        Memcached::getVersion — 获取服务器池中所有服务器的版本信息
        Memcached::increment — 增加数值元素的值
        Memcached::incrementByKey — Increment numeric item's value, stored on a specific server
        Memcached::isPersistent — Check if a persitent connection to memcache is being used
        Memcached::isPristine — Check if the instance was recently created
        Memcached::prepend — 向一个已存在的元素前面追加数据
        Memcached::prependByKey — Prepend data to an existing item on a specific server
        Memcached::quit — 关闭所有打开的链接。
        Memcached::replace — 替换已存在key下的元素
        Memcached::replaceByKey — Replace the item under an existing key on a specific server
        Memcached::resetServerList — Clears all servers from the server list
        Memcached::set — 存储一个元素
        Memcached::setByKey — Store an item on a specific server
        Memcached::setMulti — 存储多个元素
        Memcached::setMultiByKey — Store multiple items on a specific server
        Memcached::setOption — 设置一个memcached选项
        Memcached::setOptions — Set Memcached options
        Memcached::setSaslAuthData — Set the credentials to use for authentication
        Memcached::touch — Set a new expiration on an item
        Memcached::touchByKey — Set a new expiration on an item on a specific server
 */


//检查一个扩展是否已经加载。大小写不敏感。
if (!extension_loaded('memcached')) {
    echo 'no suport memcached';
    return ;
}

$handler = new Memcached();

