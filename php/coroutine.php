<?php

/* 
 * 参考资料
 * https://www.cnblogs.com/lynxcat/p/7954456.html
 * https://www.jianshu.com/p/e21ebc18bddc
 * http://www.laruence.com/2015/05/28/3038.html
 * 
 * 并发与并行
 * 并行(parallel)：指在同一时刻，有多条指令在多个处理器上同时执行。
 * 并发(concurrency)：指在同一时刻只能有一条指令执行，但多个进程指令被快速的轮换执行，
 * 使得在宏观上具有多个进程同时执行的效果，但在微观上并不是同时执行的，
 * 只是把时间分成若干段，使多个进程快速交替的执行。多核，分时复用。
 * 
 * 协程
 * 进程是计算机系统进行资源分配和调度的基本单位（调度单位这里别纠结线程进程的），每个CPU下同一时刻只能处理一个进程。
 * 所谓的并行，只不过是看起来并行，CPU事实上在用很快的速度切换不同的进程。
 * 进程的切换需要进行系统调用，CPU要保存当前进程的各个信息，同时还会使CPUCache被废掉。
 * 所以进程切换不到非不得已就不做。
 * 
 * 进程被切换的条件是：
 * 进程执行完毕、分配给进程的CPU时间片结束，系统发生中断需要处理，或者进程等待必要的资源（进程阻塞）等。
 * 但是如果是在阻塞等待，是不是就浪费了。
 * 
 * 阻塞的话我们的程序还有其他可执行的地方可以执行
 * 线程简单理解就是一个 微进程 ，专门跑一个函数（逻辑流）。
 * 
 * 线程有两种类型，一种是由内核来管理和调度。
 * 只要涉及需要内核参与管理调度的，代价都是很大的。这种线程其实也就解决了当一个进程中，某个正在执行的线程遇到阻塞，我们可以调度另外一个可运行的线程来跑，但是还是在同一个进程里，所以没有了进程切换。
 * 另外一种线程，他的调度是由程序员自己写程序来管理的，对内核来说不可见。这种线程叫做 用户空间线程 。
 * 协程可以理解就是一种用户空间线程。
 * 
 * 协程，有几个特点：
 * 协同，因为是由程序员自己写的调度策略，其通过协作而不是抢占来进行切换
 * 在用户态完成创建，切换和销毁
 * 协程的思想本质上就是控制流的主动让出（yield）和恢复（resume）机制
 * 迭代器经常用来实现协程
 * 
 * php里的yield，不是协程语法，而是迭代器语法。 是通过一个可中断的函数完成的。
 * 而协程的一个特点就是执行中断，切换上下文。
 * 通过迭代器函数去实现协程的方案。
 * 这种方案充分利用了迭代器可中断的特点来模拟协程中断，而利用闭包函数的上下文独立性，实现协程的上下文切换。
 * 协程是通过切换运行方法和上下文，来在同一个空间中完成不同的处理任务（注意，不是另外开线程）。
 */

/**
 * 可迭代对象
 * 数组和对象可以被foreach语法遍历，其实除了数组和对象之外PHP内部还提供了一个 Iterator 接口，
 * 实现了Iterator接口的对象，也是可以被foreach语句遍历
 * 
 * Iterator（迭代器）接口
 *   Iterator extends Traversable {
 *       abstract public current ( void ) : mixed 返回当前元素
 *       abstract public key ( void ) : scalar 返回当前元素的键
 *       abstract public next ( void ) : void 向前移动到下一个元素
 *       abstract public rewind ( void ) : void 返回到迭代器的第一个元素 如果迭代已经开始了，这里会抛出一个异常。
 *       abstract public valid ( void ) : bool 检查当前位置是否有效
 *   }
 * 执行顺序 rewind current valid current key 输出 next current valid current key 输出...
 */
//ini_set('memory_limit', '1024M');

class MyIterator implements Iterator
{
    private $var = [];
    public function __construct(array $array = array())
    {
        if(is_array($array)){
            $this->var = $array;
        }
    }

    //重置索引游标的指向第一个元素
    public function rewind()
    {
        echo __METHOD__.": starting <br/>";
        reset($this->var);
    }
    
    //返回当前索引游标指向的元素
    public function current()
    {
        $var = current($this->var);
        echo __METHOD__.": $var<br/>";
        return $var;
    }
    
    //返回当前索引游标指向的元素的键名
    public function key()
    {
        $var = key($this->var);
        echo __METHOD__.": $var<br/>";
        return $var;
    }

    //移动当前索引游标指向下一元素
    public function next()
    {
        $var = next($this->var);
        echo __METHOD__.": $var<br/>";
        return $var;
    }
    
    //如果执行valid返回false，则循环就此结束
    public function valid()
    {
        $var = $this->current() !== false;
        echo __METHOD__.": {$var}<br/>";
        return $var;
    }
}

$arr = ['tianmao' => '天猫', 'taobao' => '淘宝', 'jingdong' => '京东'];
$myIterator = new MyIterator($arr);
foreach ($myIterator as $key => $value) {
    echo "$key: $value<br/>";
}
//MyIterator::rewind: starting 
//MyIterator::current: 天猫
//MyIterator::valid: 1
//MyIterator::current: 天猫
//MyIterator::key: tianmao
//tianmao: 天猫
//MyIterator::next: 
//string(6) "淘宝" MyIterator::current: 淘宝
//MyIterator::valid: 1
//MyIterator::current: 淘宝
//MyIterator::key: taobao
//taobao: 淘宝
//MyIterator::next: 
//string(6) "京东" MyIterator::current: 京东
//MyIterator::valid: 1
//MyIterator::current: 京东
//MyIterator::key: jingdong
//jingdong: 京东
//MyIterator::next: 
//bool(false) MyIterator::current: 
//MyIterator::valid: 


/**
 * 生成器
 * 生成器是一种可中断的函数, 在它里面的yield构成了中断点.
 * 为了拥有一个能够被foreach遍历的对象，你不得不去实现 Iterator，yield关键字就是为了简化这个过程
 * 生成器提供了一种更容易的方法来实现简单的对象迭代
 * 性能开销和复杂性大大降低
 * 
 * 协程的支持是在迭代生成器的基础上, 增加了可以回送数据给生成器的功能(调用者发送数据给被调用的生成器函数). 
 * 这就把生成器到调用者的单向通信转变为两者之间的双向通信.
 * 
 * 生成器允许你在 foreach 代码块中写代码来迭代一组数据而不需要在内存中创建一个数组, 那会使你的内存达到上限，或者会占据可观的处理时间。
 * 相反，你可以写一个生成器函数，就像一个普通的自定义函数一样, 和普通函数只返回一次不同的是, 生成器可以根据需要 yield 多次，以便生成需要迭代的值。
 * 
 * yield 返回 Generator 对象
 * 
    Generator implements Iterator {
        public current ( void ) : mixed 返回当前产生的值
        public key ( void ) : mixed 返回当前产生的键
        public next ( void ) : void 生成器继续执行
        public rewind ( void ) : void 重置迭代器
        public send ( mixed $value ) : mixed 向生成器中传入一个值
        public throw ( Exception $exception ) : void  向生成器中抛入一个异常
        public valid ( void ) : bool 检查迭代器是否被关闭
        public __wakeup ( void ) : void 序列化回调
    }
 */
function xrange($n = 0){
    while($n) {
        yield $n => $n+1;
        echo 't<br/>';
        $n--;
    }
}
//调用 foreach send() current/next
$re = xrange(5);
var_dump($re instanceof Iterator); // bool(true)
var_dump($re);//object(Generator)#3 (0) { }
var_dump($re->current());//int(6)
$re->next();
var_dump($re->current());//int(5)

foreach (xrange(5) as $k => $num){
    echo $k . '=>' . $num."<br/>";
}

function logger() {
    echo 11;
    while (true) {
        //yield作为接受者
        echo  yield;//yield没有作为一个语句来使用, 而是用作一个表达式, 即它能被演化成一个值. 这个值就是调用者传递给send()方法的值.
    }
}

$logger = logger();
$logger->send('Foo');//传递数据的功能是通过迭代器的send()方法实现的
$logger->send('Bar');
echo "<br/>";

function gen() {
    $ret = (yield 'yield1');
    var_dump($ret);echo "<br/>";
    $ret = (yield 'yield2');
    var_dump($ret);echo "<br/>";
}
$gen = gen();
var_dump($gen->current()); // string(6) "yield1"
var_dump($gen->send('ret1')); // string(4) "ret1" string(6) "yield2"
var_dump($gen->send('ret2')); // string(4) "ret2" NULL

echo "<br/>";
function gen2() {
    yield 'foo';
    yield 'bar';
}
$gen = gen2();
//rewind()方法已经被隐式调用 导致第一个yield被执行, 并且忽略了他的返回值
var_dump($gen->send('something'));//string(3) "bar"
echo "<br/>";


/**
 * PHP协程
 * 
 * Task实现
 * Task就是一个任务的抽象，协程就是用户空间协程，线程可以理解就是跑一个函数。
 */
class Task
{
    protected $taskId;
    protected $coroutine;
    protected $beforeFirstYield=true;
    //rewind()方法已经被隐式调用 导致第一个yield被执行, 并且忽略了他的返回值
    //beforeFirstYield 我们可以确定第一个yield的值能被正确返回
    protected $sendValue;
    /**
     * Task constructor.
     * @param $taskId
     * @param Generator $coroutine 协程 Generator implements Iterator{}
     */
    public function __construct($taskId, Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    /**
     * 获取当前的Task的ID
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * 判断Task执行完毕了没有
     * @return bool
     */
    public function isFinished()
    {
        return !$this->coroutine->valid();
    }

    /**
     * 设置下次要传给协程的值，比如 $id = (yield $xxxx)，这个值就给了$id了
     * @param $value
     */
    public function setSendvalue($value)
    {
        $this->sendValue = $value;
    }

    /**
     * 运行任务
     * @return mixed
     */
    public function run()
    {
        // 这里要注意，生成器的开始会reset，所以第一个值要用current获取
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            //调用者发送数据给被调用的生成器函数
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }
}

/**
 * 协程调度器
 * Scheduler实现
 * 接下来就是Scheduler这个重点核心部分，他扮演着调度员的角色
 * 
 * 多任务协作这个术语中的“协作”很好的说明了如何进行这种切换的：
 * 它要求当前正在运行的任务自动把控制传回给调度器,这样就可以运行其他任务了. 
 * 这与“抢占”多任务相反, 抢占多任务是这样的：
 * 调度器可以中断运行了一段时间的任务, 不管它喜欢还是不喜欢. 
 * 协作多任务在Windows的早期版本(windows95)和Mac OS中有使用, 不过它们后来都切换到使用抢先多任务了. 
 * 理由相当明确：如果你依靠程序自动交出控制的话, 那么一些恶意的程序将很容易占用整个CPU, 不与其他任务共享
 * 
 * yield指令提供了任务中断自身的一种方法, 然后把控制交回给任务调度器. 
 * 因此协程可以运行多个其他任务. 更进一步来说, yield还可以用来在任务和调度器之间进行通信
 */
class Scheduler
{
    protected $taskQueue;
    protected $tid=0;
    protected $taskMap = []; // taskId => task
     
    public function __construct()
    {
        /*
         *  原理就是维护了一个队列，
         *  前面说过，从编程角度上看，协程的思想本质上就是控制流的主动让出（yield）和恢复（resume）机制
         *  SplQueue 类通过使用一个双向链表来提供队列的主要功能。
         *  SplQueue extends SplDoublyLinkedList implements Iterator , ArrayAccess , Countable {}
         * */
        $this->taskQueue = new SplQueue();
    }

    /**
     * 增加一个任务
     * @param Generator $task
     * @return int
     */
    public function addTask(Generator $coroutine)
    {
        $tid = ++$this->tid;
        $task = new Task($tid,$coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }
    
    /**
     * 删除一个任务
     * @param type $tid
     * @return boolean
     */
    public function killTask($tid)
    {
        if (!isset($this->taskMap[$tid])) {
            return false;
        }

        unset($this->taskMap[$tid]);

        // This is a bit ugly and could be optimized so it does not have to walk the queue,
        // but assuming that killing tasks is rather rare I won't bother with it now
        foreach ($this->taskQueue as $i => $task) {
            if ($task->getTaskId() === $tid) {
                unset($this->taskQueue[$i]);
                break;
            }
        }

        return true;
    }

    /**
     * 把任务进入队列
     * @param Task $task
     */
    public function schedule(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }

    /**
     * 运行调度器
     */
    public function run()
    {
        while (!$this->taskQueue->isEmpty()) {
            //任务出列
            $task = $this->taskQueue->dequeue();
            $res = $task->run();  // 运行任务直到 yield
            //null
            
            if ($res instanceof SystemCall) {
                $res($task, $this);
                continue;
            }
            
            if(!$task->isFinished()){
                $this->schedule($task);// 任务如果还没完全执行完毕，入队等下次执行
            }else{
                unset($this->taskMap[$task->getTaskId()]);
            }
        }
    }
}

class MyTask
{
    public static function task1() 
    {
        $arr = ['天猫1', '淘宝1', '京东1'];
        for ($i = 0; $i <= 10; $i++) {
            echo "This is task 1 iteration $i - {$arr[$i%3]}.<br/>";
            yield;
        }
    }

    public static function task2() 
    {
        $arr = ['天猫2', '淘宝2', '京东2'];
        for ($i = 0; $i <= 5; $i++) {
            echo "This is task 2 iteration $i - {$arr[$i%3]}.<br/>";
            yield;
        }
    }
    
    public static function task3($max) {
        $tid = (yield MySystemCall::getTaskId()); // <-- here's the syscall!
        for ($i = 1; $i <= $max; ++$i) {
            echo "This is task $tid iteration $i.<br/>";
            yield;
        }
    }
    
    public static function childTask() {
        $tid = (yield MySystemCall::getTaskId());
        while (true) {
            echo "Child task $tid still alive!<br/>";
            yield;
        }
    }
    
    public static function task4() {
        $tid = (yield MySystemCall::getTaskId());
        $childTid = (yield MySystemCall::newTask(MyTask::childTask()));

        for ($i = 1; $i <= 6; ++$i) {
            echo "Parent task $tid iteration $i.<br/>";
            yield;

            if ($i == 3) {
                yield MySystemCall::killTask($childTid);
                
            }
        }
    }
}

$scheduler = new Scheduler();
$scheduler->addTask(MyTask::task1()); //存储不够
$scheduler->addTask(MyTask::task2());
$scheduler->run();



/**
 * 与调度器之间通信
 * 我们将使用进程用来和操作系统会话的同样的方式来通信：系统调用.
 */
class SystemCall
{
    protected $callback;
 
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }
 
    //当尝试以调用函数的方式调用一个对象时，__invoke() 方法会被自动调用。
    //$obj = new SystemCall; $obj(5,6);
    public function __invoke(Task $task, Scheduler $scheduler)
    {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}

class MySystemCall
{

    public static function getTaskId() {
        return new SystemCall(function(Task $task, Scheduler $scheduler) {
            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        });
    }

    public static function newTask(Generator $coroutine) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($coroutine) {
                $task->setSendValue($scheduler->addTask($coroutine));
                $scheduler->schedule($task);
            }
        );
    }
    
    public static function killTask($tid) {
        return new SystemCall(
            function(Task $task, Scheduler $scheduler) use ($tid) {
                $task->setSendValue($scheduler->killTask($tid));
                $scheduler->schedule($task);
            }
        );
    }
}

$scheduler = new Scheduler;
$scheduler->addTask(MyTask::task3(10));
$scheduler->addTask(MyTask::task3(5));
$scheduler->run();



$scheduler = new Scheduler;
$scheduler->addTask(MyTask::task4());
$scheduler->run();

