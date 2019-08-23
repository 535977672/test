<?php
/**
 * php写守护进程（Daemon）
 * https://www.cnblogs.com/onephp/p/9203029.html
 * 
 * Linux 信号说明列表
 * https://blog.csdn.net/tengzhaorong/article/details/9744869
 * 
 * 守护进程是脱离于终端并且在后台运行的进程。守护进程脱离于终端是为了避免进程在执行过程中的信息在任何终端上显示并且进程也不会被任何终端所产生的终端信息所打断。
 */
class DaemonCommand{
 
    private $info_dir="/tmp";
    private $pid_file="";
    private $terminate=false; //是否中断
    private $workers_count=0;
    private $gc_enabled=null;
    private $workers_max=8; //最多运行8个进程
 
    public function __construct($is_sington=false,$user='nobody',$output="/dev/null"){ 
        $this->is_sington=$is_sington; //是否单例运行，单例运行会在tmp目录下建立一个唯一的PID
        $this->user=$user; //设置运行的用户 默认情况下nobody
        $this->output=$output; //设置输出的地方
        $this->checkPcntl();
    }
    
    //检查环境是否支持pcntl支持
    public function checkPcntl(){
        //函数pcntl_signal_dispatch()调用每个等待信号通过pcntl_signal() 安装的处理器。
        if ( ! function_exists('pcntl_signal_dispatch')) {
            // PHP < 5.3 uses ticks to handle signals instead of pcntl_signal_dispatch
            // call sighandler only every 10 ticks
            declare(ticks = 10);
            //Zend引擎每执行10条低级语句就去执行一次 register_tick_function() 注册的函数。
            //可以粗略的理解为每执行10句php代码就去执行下已经注册的tick函数。
            //每执行10次低级语句会检查一次该进程是否有未处理过的信号
        }
 
        // 函数pcntl_signal()为signo指定的信号安装一个新 的信号处理器。
        if ( ! function_exists('pcntl_signal')) {
            $message = 'PHP does not appear to be compiled with the PCNTL extension.  This is neccesary for daemonization';
            $this->_log($message);
            throw new Exception($message);
        }
        
        //信号处理
        //SIGTERM 程序结束(terminate)信号, 与SIGKILL不同的是该信号可以被阻塞和处理。
        //通常用来要求程序自己正常退出，shell命令kill缺省产生这个信号。如果进程终止不了，我们才会尝试SIGKILL。
        pcntl_signal(SIGTERM, array(__CLASS__, "signalHandler"),false);
        //SIGINT 程序终止(interrupt)信号, 在用户键入INTR字符(通常是Ctrl-C)时发出，用于通知前台进程组终止进程。
        pcntl_signal(SIGINT, array(__CLASS__, "signalHandler"),false);
        //SIGQUIT 和SIGINT类似, 但由QUIT字符(通常是Ctrl-/)来控制. 进程在因收到SIGQUIT退出时会产生core文件, 在这个意义上类似于一个程序错误信号。
        pcntl_signal(SIGQUIT, array(__CLASS__, "signalHandler"),false);
 
        // Enable PHP 5.3 garbage collection
        // 垃圾回收机制
        if (function_exists('gc_enable'))
        {
            //gc_enable — 设置 zend.enable_gc 为 1， 激活循环引用收集器。
            gc_enable();
            $this->gc_enabled = gc_enabled();//gc_enabled — 返回循环引用计数器的状态 如果垃圾收集器已启用则返回 TRUE，否则返回 FALSE。
        }
    }
 
    // daemon化程序
    public function daemonize(){
 
        global $stdin, $stdout, $stderr;
        global $argv;
 
        set_time_limit(0);
 
        // 只允许在cli下面运行 
        //php_sapi_name 返回 web 服务器和 PHP 之间的接口类型
        if (php_sapi_name() != "cli"){
            die("only run in command line mode\n");
        }
 
        // 只能单例运行
        if ($this->is_sington==true){
 
            $this->pid_file = $this->info_dir . "/" .__CLASS__ . "_" . substr(basename($argv[0]), 0, -4) . ".pid";
            $this->checkPidfile();
        }
 
        //umask() 将 PHP 的 umask 设定为 mask & 0777 并返回原来的 umask。当 PHP 被作为服务器模块使用时，在每个请求结束后 umask 会被恢复。
        umask(0); //把文件掩码清0
 
        if (pcntl_fork() != 0){ //是父进程，父进程退出
            exit();
        }
 
        //设置当前进程为session leader(会话领导者)
        posix_setsid();//设置新会话组长，脱离终端
 
        if (pcntl_fork() != 0){ //是第一子进程，结束第一子进程   
            exit();
        }
 
        chdir("/"); //改变工作目录
 
        $this->setUser($this->user) or die("cannot change owner");
 
        //关闭打开的文件描述符
        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);
        ////PHP CLI中，有三个系统常量，分别是STDIN、STDOUT、STDERR，代表文件句柄。 
        //标准输入php://stdin & STDIN  //STDIN是一个文件句柄，等同于fopen("php://stdin", 'r')
        //标准输出php://stdout & STDOUT  fopen("php://stdout", 'w') 
        //标准错误，默认情况下会发送至用户终端  php://stderr & STDERR  等同于fopen("php://stderr", 'w') 
        
        $stdin  = fopen($this->output, 'r');
        $stdout = fopen($this->output, 'a');
        $stderr = fopen($this->output, 'a');
 
        if ($this->is_sington==true){
            $this->createPidfile();
        }
 
    }
    
    //--检测pid是否已经存在
    public function checkPidfile(){
 
        if (!file_exists($this->pid_file)){
            return true;
        }
        $pid = file_get_contents($this->pid_file);
        $pid = intval($pid);
        if ($pid > 0 && posix_kill($pid, 0)){
            $this->_log("the daemon process is already started");
        }
        else {
            $this->_log("the daemon proces end abnormally, please check pidfile " . $this->pid_file);
        }
        exit(1);
 
    }
    
    //----创建pid
    public function createPidfile(){
 
        if (!is_dir($this->info_dir)){
            mkdir($this->info_dir);
        }
        $fp = fopen($this->pid_file, 'w') or die("cannot create pid file");
        fwrite($fp, posix_getpid());
        fclose($fp);
        $this->_log("create pid file " . $this->pid_file);
    }
 
    //设置运行的用户
    public function setUser($name){
 
        $result = false;
        if (empty($name)){
            return true;
        }
        //通过用户名，获取给定用户的信息
        $user = posix_getpwnam($name);
        if ($user) {
            $uid = $user['uid'];
            $gid = $user['gid'];
            //设置当前进程的真实用户ID。这是个特权函数，需要操作系统上具有特殊权限(通常是root权限)，才能执行该函数。
            $result = posix_setuid($uid);
            //设置当前进程的真实用户组ID。这是个特权函数，需要操作系统上具有特殊权限(通常是root权限)，才能执行该函数。
            //函数调用的适当的顺序是：首先调用 posix_setgid()，最后调用 posix_setuid()。
            posix_setgid($gid);
        }
        return $result;
 
    }
    //信号处理函数
    public function signalHandler($signo){
 
        switch($signo){
 
            //用户自定义信号
            case SIGUSR1: //busy
            if ($this->workers_count < $this->workers_max){
                $pid = pcntl_fork();
                if ($pid > 0){
                    $this->workers_count ++;
                }
            }
            
            break;
            //子进程结束信号
            case SIGCHLD:
                while(($pid=pcntl_waitpid(-1, $status, WNOHANG)) > 0){
                    $this->workers_count --;
                }
            break;
            //中断进程
            case SIGTERM:
            case SIGHUP:
            case SIGQUIT:
 
                $this->terminate = true;
            break;
            default:
            return false;
        }
 
    }
    
    /**
    *开始开启进程
    *$count 准备开启的进程数
    */
    public function start($count=1){
 
        $this->_log("daemon process is running now");
        //SIGCHLD 子进程结束时, 父进程会收到这个信号。
        //如果父进程没有处理这个信号，也没有等待(wait)子进程，子进程虽然终止，
        //但是还会在内核进程表中占有表项，这时的子进程称为僵尸进程。
        //这种情 况我们应该避免(父进程或者忽略SIGCHILD信号，或者捕捉它，或者wait它派生的子进程，
        //或者父进程先终止，这时子进程的终止自动由init进程来接管)。
        pcntl_signal(SIGCHLD, array(__CLASS__, "signalHandler"),false); // if worker die, minus children num
        while (true) {
            if (function_exists('pcntl_signal_dispatch')){
 
                pcntl_signal_dispatch();
            }
 
            if ($this->terminate){
                break;
            }
            $pid=-1;
            if($this->workers_count<$count){
 
                $pid=pcntl_fork();
            }
 
            if($pid>0){
 
                $this->workers_count++;
 
            }elseif($pid==0){
 
                // 这个符号表示恢复系统对信号的默认处理
                pcntl_signal(SIGTERM, SIG_DFL);
                pcntl_signal(SIGCHLD, SIG_DFL);
                if(!empty($this->jobs)){
                    while($this->jobs['runtime']){
                        if(empty($this->jobs['argv'])){
                            call_user_func($this->jobs['function'],$this->jobs['argv']);
                        }else{
                            call_user_func($this->jobs['function']);
                        }
                        $this->jobs['runtime']--;
                        sleep(2);
                    }
                    exit();
 
                }
                return;
 
            }else{
 
                sleep(2);
            }
 
 
        }
 
        $this->mainQuit();
        exit(0);
 
    }
 
    //整个进程退出
    public function mainQuit(){
 
        if (file_exists($this->pid_file)){
            unlink($this->pid_file);
            $this->_log("delete pid file " . $this->pid_file);
        }
        $this->_log("daemon process exit now");
        //用来立即结束程序的运行. 本信号不能被阻塞、处理和忽略。如果管理员发现某个进程终止不了，可尝试发送这个信号。
        posix_kill(0, SIGKILL);
        exit(0);
    }
 
    // 添加工作实例，目前只支持单个job工作
    public function setJobs($jobs=array()){
 
        if(!isset($jobs['argv'])||empty($jobs['argv'])){
 
            $jobs['argv']="";
 
        }
        if(!isset($jobs['runtime'])||empty($jobs['runtime'])){
 
            $jobs['runtime']=1;
 
        }
 
        if(!isset($jobs['function'])||empty($jobs['function'])){
 
            $this->log("你必须添加运行的函数！");
        }
 
        $this->jobs=$jobs;
 
    }
    //日志处理
    private  function _log($message){
        printf("%s\t%d\t%d\t%s\n", date("c"), posix_getpid(), posix_getppid(), $message);
    }
 
}
 
//调用方法1
$daemon=new DaemonCommand(true);
$daemon->daemonize();
$daemon->start(2);//开启2个子进程工作
work();
 
 
 
 
//调用方法2
$daemon=new DaemonCommand(true);
$daemon->daemonize();
$daemon->setJobs(array('function'=>'work','argv'=>'','runtime'=>1000));//function 要运行的函数,argv运行函数的参数，runtime运行的次数
$daemon->start(2);//开启2个子进程工作
 
//具体功能的实现
function work(){
      echo "测试1";
}
?>