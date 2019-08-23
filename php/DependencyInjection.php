<?php
//依赖注入
class DIComm {
    private $name = 'name';
    private $code = 'code';

    public function __construct($name, $code) {
        if($this->name != $name) throw new Exception('name error');
        if($this->code != $code) throw new Exception('code error');
    }
    
    function getName() {
        return $this->name;
    }
    
    function getCode() {
        return $this->code;
    }
}


class example1 {
    private $_comm;
    function __construct(){
        //如果很多地方用到 DIComm，DIComm的参数变化将很难维护
        $this->_comm = new DIComm("name", 'code');
    }
    
    function echoParam(){
        echo $this->_comm->getName().'<br/>';
        echo $this->_comm->getCode().'<br/>';
    }
}



class Factory {
    private static $_comm;
    
    protected static function getComm(){    
        return new DIComm("name", 'code');
    }
    
    public static function getSharedComm()
    {
        if (self::$_comm===null){
            self::$_comm = self::getComm();
        }
        return self::$_comm;
    }
    
    public static function getNowComm()
    {
        return self::getComm();
    }
    
}

class example2 {
    private $_comm;
    
    //如果有多个setter方法传递 或创建构造函数进行传递 -》容器的依赖注入
    function setComm($comm){
        $this->_comm = $comm;
    }
    
    function echoParam(){
        echo $this->_comm->getName().'<br/>';
        echo $this->_comm->getCode().'<br/>';
    }
}



//使用容器的依赖注入做为一种桥梁来解决依赖可以使我们的代码耦合度更低，很好的降低复杂性
class Container {
    private $bind = [];
    private $bindExec = [];

    public function set($name, $object, $force = false){
        if(!isset($this->bind[$name]) || $force) $this->bind[$name] = $object;
    }
    
    public function get($name, $force = false){
        if(!isset($this->bind[$name])) return null;
        if(!isset($this->bindExec[$name]) || $force) $this->bindExec[$name] = $this->bind[$name]();
        return $this->bindExec[$name];//获取时才执行
    }
}

class example3 {
    private $_di;
    
    public function __construct($di){
        $this->_di = $di;
    }
    
    function echoParam(){
        $comm = $this->_di->get('comm');
        echo $comm->getName().'<br/>';
        echo $comm->getCode().'<br/>';
    }
}

try {
    
    $ex = new example1;
    $ex->echoParam();
    
    
    $ex = new example2;
    $ex->setComm(Factory::getSharedComm());
    $ex->echoParam();
    
    
    
    $container = new Container();
    //$container->set('comm', new DIComm("name", 'code'));
    $container->set('comm', function(){
        //echo 'set comm <br/>';
        return new DIComm("name", 'code');
    });
    //echo '11 <br/>';
    //存在一定倍数的时间消耗
    $ex = new example3($container);
    $ex->echoParam();
    //echo '22 <br/>';

    
} catch (Exception $exc) {
    echo $exc->getMessage();
}