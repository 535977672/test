<?php
/**
 * 生成器
 * 
 * yield 返回 Generator 对象
 * 
    Generator implements Iterator {
        public current ( void ) : mixed 返回当前产生的值
        public key ( void ) : mixed 返回当前产生的键
        public next ( void ) : void 生成器继续执行
        public rewind ( void ) : void 重置迭代器 如果迭代已经开始了，这里会抛出一个异常。
        public send ( mixed $value ) : mixed 向生成器中传入一个值 返回下一个yield的值
        public throw ( Exception $exception ) : void  向生成器中抛入一个异常
        public valid ( void ) : bool 检查迭代器是否被关闭
        public __wakeup ( void ) : void 序列化回调
    }
 */

//yield关键字 
//生成器函数的核心是yield关键字。它最简单的调用形式看起来像一个return申明，
//不同之处在于普通return会返回值并终止函数的执行，
//而yield会返回一个值给循环调用此生成器的代码并且只是暂停执行生成器函数。

//如果在一个表达式上下文(例如在一个赋值表达式的右侧)中使用yield，你必须使用圆括号把yield申明包围起来
//$data = (yield $value);
//这个语法可以和生成器对象的Generator::send()方法配合使用


//1.生成简单的值
$generator = MyYield::gen();
MyYield::e($generator);

$generator = MyYield::gen();
//iterator_to_array — 将迭代器中的元素拷贝到数组
echo iterator_to_array($generator)[0].'<br/>';

$generator = MyYield::gen_send();
$generator->send('send string1 ...');
$generator->send('send string2 ...');


//2.指定键名来生成值
//PHP的数组支持关联键值对数组，生成器也一样支持。所以除了生成简单的值，你也可以在生成值的时候指定键名
$generator = MyYield::gen_k_v();
MyYield::e($generator);

$generator = MyYield::gen_send_k_v();
$generator->send('send string1 ...');
$generator->send('send string2 ...');
$generator->send('stop');
$generator->send('send string3 ...');

//3.Yield可以在没有参数传入的情况下被调用来生成一个 NULL值并配对一个自动的键名
$generator = MyYield::gen_nulls();
MyYield::e($generator);

//4.使用引用来生成值
$generator = MyYield::gen_reference();
foreach ($generator as &$number) {
    echo (--$number).'... <br/>';
}

//5.Generator delegation via yield from
$generator = MyYield::gen_from();
MyYield::e($generator);
$generator = MyYield::gen_from_return();
MyYield::e($generator);


//6.Generator
$generator = MyYield::g();
//void 默认已经调用 重置迭代器 如果迭代已经开始了，这里会抛出一个异常。
$generator->rewind();
var_dump($generator->valid());//检查迭代器是否被关闭
echo '<br/>';
echo $generator->key() . '=>' . $generator->current() . '<br/>';
$generator->next(); //void 生成器继续执行
echo $generator->key() . '=>' . $generator->current() . '<br/>';
//$generator->rewind(); //Cannot rewind a generator that was already
$generator->next();
var_dump($generator->valid());


$generator = MyYield::g2();
//向生成器中传入一个值，并且当做 yield 表达式的结果，然后继续执行生成器。
//如果当这个方法被调用时，生成器不在 yield 表达式，那么在传入值之前，它会先运行到第一个 yield 表达式
$str = $generator->send('str');//返回下一个yield的值

$generator1 = MyYield::g3();
$generator2 = MyYield::g4();
$generator1->send('ee');
$generator2->send('ee');
$generator1->send('ee');
$generator2->send('ee');
$generator1->send('ee');
$generator2->send('ee');
MyYield::e(MyYield::$taskQueue);


class MyYield {
    private static $arr = ['timmao', 'jngdong', 'taobao'];
    public static $taskQueue = '';
    
    public static function splQueue() {
        if(!self::$taskQueue) self::$taskQueue = new SplQueue();
    }

    public static function g() {
        yield 1;
        yield 2;
        yield 3;
    }
    
    public static function g2() {
        echo (yield 2).'<br/>';
        echo (yield 5).'<br/>';
        echo (yield 7).'<br/>';
    }
    
    public static function g3() {
        while (true) {
            $str = (yield).'g3';
            self::splQueue();
            self::$taskQueue->enqueue($str);
        }
    }
    
    public static function g4() {
        while (true) {
            $str = (yield).'g4';
            self::splQueue();
            self::$taskQueue->enqueue($str);
        }
    }

    public static function gen() {
        for ($i = 1; $i <= 3; $i++) {
            //注意变量$i的值在不同的yield之间是保持传递的。
            yield $i;
        }
    }
    
    public static function gen_send() {
        while (true) {
            $string = (yield);
            echo $string.'<br/>';
        }
    }
    
    public static function gen_k_v() {
        for ($i = 1; $i <= 3; $i++) {
            yield $i => self::$arr[$i-1];
        }
    }
    
    public static function gen_send_k_v() {
        while (true) {
            $string = (yield);
            echo $string.'<br/>';
            if($string == 'stop'){
                echo 'stoped ...<br/>';
                return;
            }
        }
    }
    
    public static function gen_nulls() {
        for ($i = 1; $i <= 3; $i++) {
            yield;
        }
    }
    
    public static function &gen_reference() {
        $value = 3;

        while ($value > 0) {
            yield $value;
        }
    }
    
    public static function gen_from() {
        yield 1;
        yield 2;
        yield from self::gen_from2();
    }
    
    public static function gen_from2() {
        yield 3;
        yield 4;
    }
    
    public static function gen_from_return() {
        yield 1;
        yield 2;
        return yield from self::gen_from_return2();
        yield 5;
    }
    
    public static function gen_from_return2() {
        yield 3;
        yield 4;
    }
    
    public static function e($generator) {
        foreach ($generator as $k=>$value) {
            echo "$k => $value<br/>";
        }
    }
    
}
