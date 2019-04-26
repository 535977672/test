<?php
//xcache
/*
    mixed xcache_get(string name)
    bool  xcache_set(string name, mixed value [, int ttl])
    bool  xcache_isset(string name)
    bool  xcache_unset(string name)
    bool  xcache_unset_by_prefix(string prefix)
    int   xcache_inc(string name [, int value [, int ttl]])
    int   xcache_dec(string name [, int value [, int ttl]])
 */


//检查一个扩展是否已经加载。大小写不敏感。
if (!function_exists('xcache_info')) {
    echo '不支持 xcache';
    return ;
}


//1.xcache_set(string name, mixed value [, int ttl]) ttl有效时间（秒）
xcache_set('key1', 1, 60);

//2.mixed xcache_get(string name)
$key1 = xcache_get('key1');

//3.xcache_isset(string name)
$isset = xcache_isset('key1');

//4.xcache_unset(string name)
xcache_set('key2', 2, 60);
xcache_unset('key2');

//5.xcache_inc(string name [, int value [, int ttl]])
xcache_inc('key1', 20);

//6.xcache_dec(string name [, int value [, int ttl]])
xcache_dec('key1', 10);


/**
 * XCache
 *
 * @package XCache
 * @version $Id$
 * @copyright 2007
 * @author Cristian Rodriguez <judas.iscariote@flyspray.org>
 * @license BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

class XCache {
    
    private static $xcobj;
    
    private function __construct()
    {
    }

    public final function __clone()
    {
        throw new BadMethodCallException("Clone is not allowed");
    } 
    
    /**
     * getInstance 
     * 
     * @static
     * @access public
     * @return object XCache instance
     */
    public static function getInstance() 
    {
        if (!(self::$xcobj instanceof XCache)) {
            self::$xcobj = new XCache;
        }
        return self::$xcobj; 
    }

    /**
     * __set 
     * 
     * @param mixed $name 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function __set($name, $value)
    {
        xcache_set($name, $value);
    }

    /**
     * __get 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function __get($name)
    {
        return xcache_get($name);
    }

    /**
     * __isset 
     * 
     * @param mixed $name 
     * @access public
     * @return bool
     */
    public function __isset($name)
    {
        return xcache_isset($name);
    }

    /**
     * __unset 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function __unset($name)
    {
        xcache_unset($name);
    }
}