<?php

/* 
 * 垃圾回收机制
 * https://www.php.net/manual/zh/features.gc.refcounting-basics.php
 */

//php5和php7不同点
//1、PHP5标量数据类型会计数，PHP7标量数据类型不再计数，不需要单独分配内存
//2、PHP7的zval 需要的内存不再是单独从堆上分配，不再自己存储引用计数。
//3、PHP7的复杂数据类型（比如数组和对象）的引用计数由其自身来存储。

//引用计数基本知识
gc_count();

//回收周期(Collecting Cycles)
gc_cycles();


//性能方面考虑的因素
gc_property();

function gc_count(){
    //每个php变量存在一个叫"zval"的变量容器中.
    //包括两个字节的额外信息。
    //第一个是"is_ref"，是个bool值，用来标识这个变量是否是属于引用集合(reference set)
    //第二个额外字节是"refcount", zval变量容器的变量(也称符号即symbol)个数  把一个变量赋值给另一变量将增加引用次数(refcount).

    //1. 标量(scalar)类型的值
    $a = "new string";
    $c = $b = $a;
    //Xdebug的输出显示两个值为a b的 zval 变量容器，其实是同一个
    xdebug_debug_zval( 'a' );//(refcount=3, is_ref=0),string 'new string' (length=10)
    xdebug_debug_zval( 'b' );//(refcount=3, is_ref=0),string 'new string' (length=10)
    unset($c, $b);
    xdebug_debug_zval( 'a' );//(refcount=1, is_ref=0),string 'new string' (length=10)
    unset($a);
    xdebug_debug_zval( 'a' );//a: no such symbol



    //2. 复合类型(Compound Types)
    //当考虑像 array和object这样的复合类型时，事情就稍微有点复杂. 与 标量(scalar)类型的值不同，
    //array和 object类型的变量把它们的成员或属性存在自己的符号表中。
    $a = ['meaning' => 'life', 'number' => 42];
    xdebug_debug_zval( 'a' );//三个zval变量容器是: a，meaning和 number
    //a:
    //(refcount=1, is_ref=0),
    //array (size=2)
    //  'meaning' => (refcount=1, is_ref=0),string 'life' (length=4)
    //  'number' => (refcount=1, is_ref=0),int 42

    $a['life'] = $a['meaning'];
    xdebug_debug_zval( 'a' );
    //a:
    //(refcount=1, is_ref=0),
    //array (size=3)
    //  'meaning' => (refcount=2, is_ref=0),string 'life' (length=4)
    //  'number' => (refcount=1, is_ref=0),int 42
    //  'life' => (refcount=2, is_ref=0),string 'life' (length=4)

    $a = array( 'one' );
    $a[] =& $a;
    xdebug_debug_zval( 'a' );//发生了递归操作, 这种情况下意味着"&array<"指向原始数组
    //a:
    //(refcount=2, is_ref=1),
    //array (size=2)
    //  0 => (refcount=1, is_ref=0),string 'one' (length=3)
    //  1 => (refcount=2, is_ref=1),
    //    &array<

    unset($a);
    //数组元素“1”仍然指向数组本身，所以这个容器不能被清除 。
    //因为没有另外的符号指向它，用户没有办法清除这个结构，结果就会导致内存泄漏。
    //unset取消了变量与值的关联
    //php将在脚本执行结束时清除这个数据结构，但是在php清除之前，将耗费不少内存。
    //内存泄漏--请求基本上不会结束的守护进程(deamons)或者单元测试中的大的套件(sets)中
    //(refcount=1, is_ref=1)=array (
    //   0 => (refcount=1, is_ref=0)='one',
    //   1 => (refcount=1, is_ref=1)=...
    //)
}


function gc_cycles(){
    //传统上，像以前的 php 用到的引用计数内存机制，无法处理循环的引用内存泄漏。
    //然而 5.3.0 PHP 使用文章» 
    //引用计数系统中的同步周期回收(Concurrent Cycle Collection in Reference Counted Systems)
    //中的同步算法，来处理这个内存泄漏问题。
    
    //如果一个引用计数增加，它将继续被使用，当然就不再在垃圾中。
    //如果引用计数减少到零，所在变量容器将被清除(free)。
    //就是说，仅仅在引用计数减少到非零值时，才会产生垃圾周期(garbage cycle)。
    //其次，在一个垃圾周期中，通过检查引用计数是否减1，并且检查哪些变量容器的引用次数是零，来发现哪部分是垃圾。
    
    //除了修改配置zend.enable_gc ，也能通过分别调用gc_enable() 和 gc_disable()函数来打开和关闭垃圾回收机制。
    //调用这些函数，与修改配置项来打开或关闭垃圾回收机制的效果是一样的。
    //即使在可能根缓冲区还没满时，也能强制执行周期回收。
    //调用  gc_collect_cycles()  函数达到这个目的。这个函数将返回使用这个算法回收的周期数。
    //就在你调用gc_disable()函数释放内存之前，先调用gc_collect_cycles()函数可能比较明智。
}

function gc_property(){
    //主要有两个领域对性能有影响。
    //第一个是内存占用空间的节省，
    //另一个是垃圾回收机制执行内存清理时的执行时间增加(run-time delay)。
    
    //内存占用空间的节省
    //首先，实现垃圾回收机制的整个原因是为了，一旦先决条件满足，通过清理循环引用的变量来节省内存占用。
    //在PHP执行中，一旦根缓冲区满了或者调用gc_collect_cycles() 函数时，就会执行垃圾回收。
    
    //执行时间增加(Run-Time Slowdowns)
    //垃圾回收影响性能的第二个领域是它释放已泄漏的内存耗费的时间。
}

