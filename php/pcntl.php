<?php

/**
 * linux php ./pctnl.php
 * PHP的进程控制支持实现了Unix方式的进程创建, 程序执行, 信号处理以及进程的中断。 
 * 
 * 进程控制 不能 被 应用 在 Web服务器 环境，
 * 当其被用于Web服务环境时可能会带来意外的结果。
 * 
 * PCNTL现在使用了ticks作为信号处理的回调机制，ticks在速度上远远超过了之前的处理机制。 
 * 这个变化与“用户ticks”遵循了相同的语义。
 * 您可以使用declare() 语句在程序中指定允许发生回调的位置。
 * 这使得我们对异步事件处理的开销最小化。
 * 
    pcntl_alarm — 为进程设置一个alarm闹钟信号
    pcntl_async_signals — 启用/禁用异步信号处理或返回旧设置
    pcntl_errno — 别名 pcntl_get_last_error
    pcntl_exec — 在当前进程空间执行指定程序
    pcntl_fork — 在当前进程当前位置产生分支（子进程）。译注：fork是创建了一个子进程，父进程和子进程 都从fork的位置开始向下继续执行，不同的是父进程执行过程中，得到的fork返回值为子进程 号，而子进程得到的是0。
    pcntl_get_last_error — 检索上次失败的pcntl函数设置的错误号
    pcntl_getpriority — 获取任意进程的优先级
    pcntl_setpriority — 修改任意进程的优先级
    pcntl_signal_dispatch — 调用等待信号的处理器
    pcntl_signal_get_handler — 获取指定信号的当前处理程序
    pcntl_signal — 安装一个信号处理器
    pcntl_sigprocmask — 设置或检索阻塞信号
    pcntl_sigtimedwait — 带超时机制的信号等待
    pcntl_sigwaitinfo — 等待信号
    pcntl_strerror — 检索与给定errno关联的系统错误消息
    pcntl_wait — 等待或返回fork的子进程状态
    pcntl_waitpid — 等待或返回fork的子进程状态
    pcntl_wexitstatus — 返回一个中断的子进程的返回代码
    pcntl_wifexited — 检查状态代码是否代表一个正常的退出
    pcntl_wifsignaled — 检查子进程状态码是否代表由于某个信号而中断
    pcntl_wifstopped — 检查子进程当前是否已经停止
    pcntl_wstopsig — 返回导致子进程停止的信号
    pcntl_wtermsig — 返回导致子进程中断的信号
 */

//libevent是一个事件触发的网络库，适用于windows、linux、freebsd等多种平台，
//内部使用select、poll、epoll、kqueue等系统调用管理事件机制。
//Libevent是跨平台的，而且具有非凡的性能。与nodejs一样是事件驱动的


declare(ticks = 1);

$r = new Test;

//$r->pcntl();

//$r->signal();

$r->fork();

//$r->wait();

class Test{
    
    public function pcntl() {
        echo 'pcntl' . PHP_EOL;
        
        //pcntl_async_signals ([ bool $on = NULL ] ) : bool
        //启用/禁用异步信号处理或返回旧设置
        //如果省略on参数，pcntl_async_signals()返回是否启用异步信号处理。否则，将启用或禁用异步信号处理。
        
        //pcntl_get_last_error/pcntl_errno ( void ) : int
        //检索上次失败的pcntl函数设置的错误号
        
        //pcntl_strerror ( int $errno ) : string
        //检索与给定errno关联的系统错误消息
        
        //pcntl_exec ( string $path [, array $args [, array $envs ]] ) : void
        //在当前进程空间执行指定程序
        //path必须是可执行二进制文件路径或一个在文件第一行指定了一个可执行文件路径标头的脚本
        //（比如文件第一行是#!/usr/local/bin/perl的perl脚本）。
        //args是一个要传递给程序的参数的字符串数组
        //envs是一个要传递给程序作为环境变量的字符串数组。这个数组是 key => value格式的，key代表要传递的环境变量的名称，value代表该环境变量值。
        
        //pcntl_getpriority ([ int $pid = getmypid() [, int $process_identifier = PRIO_PROCESS ]] ) : int
        //获取进程号为 pid的进程的优先级
        //pcntl_setpriority ( int $priority [, int $pid = getmypid() [, int $process_identifier = PRIO_PROCESS ]] ) : bool
        //设置进程号为 pid的进程的优先级
        
        //getmypid ( void ) : int
        //获取当前 PHP 进程 ID。
        
        //pcntl_wexitstatus ( int $status ) : int
        //返回一个中断的子进程的返回代码。这个函数仅在函数pcntl_wifexited()返回 TRUE.时有效。
        //status 是提供给成功调用 pcntl_waitpid() 时的状态参数
        
        //pcntl_wifexited ( int $status ) : bool
        //检查子进程状态代码是否代表正常退出
        //当子进程状态代码代表正常退出时返回 TRUE ，其他情况返回 FALSE。
        
        //pcntl_wtermsig ( int $status ) : int
        //返回导致子进程中断的信号编号。这个函数仅在pcntl_wifsignaled() 返回 TRUE 时有效。
        
        //pcntl_wifsignaled ( int $status ) : bool
        //检查子进程是否是由于某个未捕获的信号退出的
        
        //pcntl_wifstopped ( int $status ) : bool
        //仅查子进程当前是否停止; 此函数只有作用于使用了WUNTRACED作为 option的pcntl_waitpid()函数调用产生的status时才有效。
        
        //pcntl_wstopsig ( int $status ) : int
        //返回导致子进程停止的信号编号。这个函数仅在pcntl_wifstopped()返回 TRUE 时有效。
        
    }
    
    /**
     * pcntl_signal ( int $signo , callback $handler) : bool
     * pcntl_alarm ( int $seconds ) : int
     * pcntl_signal_dispatch ( void ) : bool
     * pcntl_signal_get_handler ( int $signo ) 
     */
    public function signal() {
        
        //pcntl_signal ( int $signo , callback $handler) : bool
        //安装一个信号处理器
        //函数pcntl_signal()为signo指定的信号安装一个新 的信号处理器。
        pcntl_signal(SIGALRM, array($this, "pcntlSignal"));
        
        pcntl_signal(SIGHUP, array($this, "pcntlSignal"));
        pcntl_signal(SIGUSR1, array($this, "pcntlSignal"));
        
        //向当前进程发送SIGUSR1信号
        posix_kill(posix_getpid(), SIGUSR1);
        posix_kill(posix_getpid(), SIGHUP);
        posix_kill(posix_getpid(), SIGALRM);
        
        echo 'posix_getpid ' . posix_getpid() . PHP_EOL;
        echo 'getmypid ' . getmypid() . PHP_EOL;
        
        //pcntl_signal_get_handler ( int $signo ) 
        //获取指定signo的当前处理程序
        //$h = pcntl_signal_get_handler(SIGALRM);//PHP 7 >= 7.1.0
        //echo "$h ..." . PHP_EOL;
        
        //pcntl_alarm ( int $seconds ) : int
        //创建一个计时器，在指定的秒数后向进程发送一个SIGALRM信号。每次对 pcntl_alarm()的调用都会取消之前设置的alarm信号。
        //seconds 等待的秒数。如果seconds设置为0,将不会创建alarm信号。
        //返回上次alarm调度（离alarm信号发送）剩余的秒数，或者之前没有alarm调度（译注：或者之前调度已完成） 时返回0。
        pcntl_alarm(3);
        
        
        //pcntl_signal_dispatch ( void ) : bool 分发,调度
        //函数pcntl_signal_dispatch()调用每个等待信号通过pcntl_signal() 安装的处理器。
        $i = 4;
        echo '1-' . time() . PHP_EOL;
        while($i){
            pcntl_signal_dispatch();
            echo 'while-' . $i . PHP_EOL;
            $i--;
            sleep(1);
        }
        echo '2-' . time() . PHP_EOL;
        //SIGUSR1 running...
        //SIGHUP running...
        //SIGALRM running...
        //posix_getpid 9582
        //getmypid 9582
        //1-1558331906
        //while-4
        //while-3
        //while-2
        //SIGALRM running...
        //while-1
        //2-1558331910
      
    }
    
    /**
     * pcntl_fork ( void ) : int
     * pcntl_wait ( int &$status [, int $options = 0 ] ) : int
     * pcntl_waitpid($pid, $status)
     */
    public function fork() {

        pcntl_signal(SIGCHLD, function($signal) {
            $id = pcntl_waitpid(-1, $status, WNOHANG);
            if (pcntl_wifexited($status)) 
            {
                printf("Removed Chlid id: %d \n",$id);
                printf("Chlid status: %d \n",pcntl_wexitstatus($status));
                printf("status: %s \n",$status);
            }
        });
        //pcntl_signal_dispatch();
        echo '0-posix_getpid ' . posix_getpid() . PHP_EOL;
        $i = 2;
        echo '1-' . time() . PHP_EOL;
        while($i){
            //pcntl_fork ( void ) : int
            //建一个子进程，这个子进程仅PID（进程号） 和PPID（父进程号）与其父进程不同。
            //成功时，在父进程执行线程内返回产生的子进程的PID，在子进程执行线程内返回0。
            //失败时，在 父进程上下文返回-1，不会创建子进程，并且会引发一个PHP错误。
            //子进程会复制当前进程，也就是父进程的所有：数据，代码，还有状态。
            $pid = pcntl_fork();
            //父进程和子进程都会执行下面代码
            if ($pid == -1) {
                //错误处理：创建子进程失败时返回-1.
                die('could not fork');
            } else if ($pid) {
                //父进程会得到子进程号，所以这里是父进程执行的逻辑
                echo $pid . '-p ' . time() . PHP_EOL;
                echo '1-posix_getpid ' . posix_getpid() . PHP_EOL;
                
                $wait = pcntl_wait($status); //等待子进程中断，防止子进程成为僵尸进程。
                //$wait = pcntl_wait($status, WNOHANG); //立即返回
                //pcntl_waitpid($pid, $status);

                //pcntl_wait ( int &$status [, int $options = 0 ] ) : int
                //wait函数挂起当前进程的执行直到一个子进程退出或接收到一个信号要求中断当前进程或调用一个信号处理函数。
                //如果一个子进程在调用此函数时已经退出（俗称僵尸进程），此函数立刻返回。
                //子进程使用的所有系统资源将被释放。
                //pcntl_wait()返回退出的子进程进程号，发生错误时返回-1,如果提供了 WNOHANG作为option（wait3可用的系统）并且没有可用子进程时返回0。
                //$options 
                //WNOHANG	如果没有子进程退出立刻返回。
                //WUNTRACED	子进程已经退出并且其状态未报告时返回。
                echo $wait . PHP_EOL;
                echo 'status ' . $status . PHP_EOL;

            } else {
                //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
                echo $pid . '-s ' . time() . PHP_EOL;
                echo '2-posix_getpid ' . posix_getpid() . PHP_EOL;
                
                
                $pids = pcntl_fork();
                if($pids){
                    echo $pids . '-sp ' . time() . PHP_EOL;
                    echo '3-posix_getpid ' . posix_getpid() . PHP_EOL;
                }else{
                    echo $pids . '-ss ' . time() . PHP_EOL;
                    echo '4-posix_getpid ' . posix_getpid() . PHP_EOL;
                }
                
                exit; 
            }
            
            $i--;
        }
        echo '2-' . time() . PHP_EOL;
        
        
    }
    
    public function pcntlSignal($signo) {
        switch ($signo) {
            case SIGTERM:
                // 处理SIGTERM信号
                echo 'SIGTERM running...' . PHP_EOL;
                exit;
                break;
            case SIGHUP:
                //处理SIGHUP信号
                echo 'SIGHUP running...' . PHP_EOL;
                break;
            
            case SIGALRM:
                //处理SIGALRM信号
                echo 'SIGALRM running...' . PHP_EOL;
                break;

            case SIGUSR1:
                echo "SIGUSR1 running..." . PHP_EOL;
                break;
            default:
                // 处理所有其他信号
                echo "default $signo running..." . PHP_EOL;
        }
    }
    
    /**
     * pcntl_sigprocmask ( int $how , array $set [, array &$oldset ] ) : bool
     * pcntl_sigtimedwait ( array $set [, array &$siginfo [, int $seconds = 0 [, int $nanoseconds = 0 ]]] ) : int
     * pcntl_sigwaitinfo ( array $set [, array &$siginfo ] ) : int
     */
    public function wait() {
        
        //pcntl_sigprocmask ( int $how , array $set [, array &$oldset ] ) : bool
        //函数pcntl_sigprocmask()用来增加，删除或设置阻塞信号，具体行为 依赖于参数how。
        //how
        //SIG_BLOCK: 把信号加入到当前阻塞信号中。
        //SIG_UNBLOCK: 从当前阻塞信号中移出信号。
        //SIG_SETMASK: 用给定的信号列表替换当前阻塞信号列表。
        //set 信号列表。
        //oldset oldset是一个输出参数，用来返回之前的阻塞信号列表数组。
        //pcntl_sigprocmask(SIG_BLOCK, array(SIGHUP));
        //pcntl_sigprocmask(SIG_UNBLOCK, array(SIGHUP));
        
        //将SIGHUP信号加入到阻塞信号中
        //pcntl_sigprocmask(SIG_BLOCK, array(SIGHUP,SIGALRM));
        //$oldset = array();
        //将SIGHUP从阻塞信号列表中移除并返回之前的阻塞信号列表。
        //pcntl_sigprocmask(SIG_UNBLOCK, array(SIGHUP), $oldset);
        //var_dump($oldset);
        //pcntl_sigprocmask(SIG_UNBLOCK, array(SIGHUP), $oldset);
        //var_dump($oldset);
        //array(2) {
        //  [0]=>
        //  int(1)
        //  [1]=>
        //  int(14)
        //}
        //array(1) {
        //  [0]=>
        //  int(14)
        //}
        echo "Blocking SIGHUP signal\n";
        pcntl_sigprocmask(SIG_BLOCK, array(SIGHUP));
        
        echo "Sending SIGHUP to self\n";
        posix_kill(posix_getpid(), SIGHUP);
        
        //pcntl_sigtimedwait ( array $set [, array &$siginfo [, int $seconds = 0 [, int $nanoseconds = 0 ]]] ) : int
        //函数pcntl_sigtimedwait()实际上与pcntl_sigwaitinfo() 的行为一致，
        //不同在于它多了两个增强参数seconds和 nanoseconds，这使得脚本等待的事件有了一个时间的上限。
        //set 要等待的信号列表数组。
        //siginfo 是一个输出参数，用来返回信号的信息。更详细情况参见 pcntl_sigwaitinfo()。
        //seconds 超时秒数。
        //nanoseconds 超时纳秒数。        
        //成功时，函数pcntl_sigtimedwait()返回信号编号
        
        //pcntl_sigwaitinfo ( array $set [, array &$siginfo ] ) : int
        //暂停调用脚本的执行直到接收到set 参数中列出的某个信号。
        //只要其中的一个信号已经在等待状态(比如： 通过 pcntl_sigprocmask()函数阻塞)， 
        //函数pcntl_sigwaitinfo()就会立刻返回。
        echo "Waiting for signals\n";
        $info = array();
        pcntl_sigwaitinfo(array(SIGHUP), $info);//阻塞到SIGHUP等待状态
        var_dump($info);
        //array(3) {
        //  ["signo"]=>
        //  int(1)
        //  ["errno"]=>
        //  int(0)
        //  ["code"]=>
        //  int(0)
        //}
        
    }
}