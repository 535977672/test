<?php

//sleep() - 延缓执行
//usleep() - 以指定的微秒数延迟执行 windows下无效，至少1000
//time_nanosleep() - 延缓执行若干秒和纳秒 windows
//time_sleep_until() — 使脚本睡眠到指定的时间为止。 windows



//t_sleep();
//t_usleep(10);
//t_time_nanosleep(0, 10000);
//t_time_sleep_until(0.01);

t();

function t($n = 10){
    echo microtime().'<br/>'.PHP_EOL;
    for($i=0; $i< $n; $i++){

        usleep(1000);
        //time_nanosleep(0, 10);
    }
    echo microtime().'<br/>'.PHP_EOL;
}

function t_sleep($n = 1){
    echo microtime().'<br/>'.PHP_EOL;
    sleep($n);
    echo microtime().'<br/>'.PHP_EOL;
}


function t_usleep($n = 1){
    echo microtime().'<br/>'.PHP_EOL;
    usleep($n);
    echo microtime().'<br/>'.PHP_EOL;
    
}


function t_time_nanosleep($s = 0, $n = 1){
    echo microtime().'<br/>'.PHP_EOL;
    time_nanosleep($s, $n);
    echo microtime().'<br/>'.PHP_EOL;
    
}


function t_time_sleep_until($n = 0.001){
    echo microtime().'<br/>'.PHP_EOL;
    time_sleep_until(microtime(true)+$n);
    echo microtime().'<br/>'.PHP_EOL;
}