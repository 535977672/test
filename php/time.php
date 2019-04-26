<?php

date_default_timezone_set('Asia/Shanghai');

//星期一：Monday（Mon.）
//星期二：Tuesday（Tues.）
//星期三：Wednesday（Wed.）
//星期四：Thursday（Thur./Thurs.）
//星期五：Friday（Fri.）
//星期六：Saturday（Sat.）
//星期日：Sunday（Sun.）
//last next +n -n  year month day hours seconds

//时间
echo '<br/>时间';
$time = time();
timetoecho($time);

timetoecho(strtotime("now"), 'strtotime("now")');
timetoecho(strtotime("10 September 2000"), 'strtotime("10 September 2000")');
timetoecho(strtotime("+1 day"), 'strtotime("+1 day")');
timetoecho(strtotime("+1 week"), 'strtotime("+1 week")');
timetoecho(strtotime("-1 week"), 'strtotime("-1 week")');
timetoecho(strtotime("+1 week 2 days 4 hours 2 seconds"), 'strtotime("+1 week 2 days 4 hours 2 seconds")');
timetoecho(strtotime("-1 week -2 days -4 hours -2 seconds"), 'strtotime("-1 week -2 days -4 hours -2 seconds")');
timetoecho(strtotime("next Thursday"), 'strtotime("next Thursday")');
timetoecho(strtotime("last Monday"), 'strtotime("last Monday")');
timetoecho(strtotime("+1 month"), 'strtotime("+1 month")');
timetoecho(strtotime("next month"), 'strtotime("next month")');
timetoecho(strtotime("last month"), 'strtotime("last month")');
timetoecho(strtotime("+1 year"), 'strtotime("+1 year")');
timetoecho(strtotime("last year +1 month"), 'strtotime("last year +1 month")');
timetoecho(strtotime("2022-12-30 21:21:58 last year +1 month"), 'strtotime("2022-12-30 21:21:58 last year +1 month")');
timetoecho(strtotime(date("Y-m-d H:i:s") . "-1 week last Sunday -2 day"), 'strtotime(上上周五date("Y-m-d H:i:s") . "-1 week last Sunday -2 day")');



//日期
echo '<br/>日期';
datetoecho(strtotime('2030-11-09 12:12:09'));

dmonth(strtotime('2030-11-09 12:12:09'), strtotime('2019-01-19 12:12:09'));

dday(strtotime('2030-11-09 12:12:09'), strtotime('2030-11-19 22:32:09'));

function timetoecho($time, $s = ''){
    
    echo '<br/>curtime: ' . date('Y-m-d H:i:s', time());
    
    echo '<br/>'.$s.' time: ' . $time;

    $date = date('Y-m-d H:i:s', $time);
    echo '<br/>'.$s.' date: ' . $date;

    $strtotime = strtotime($date);
    echo '<br/>'.$s.' strtotime: ' . $strtotime;
    
    echo '<br/><br/>';
}

function datetoecho($time, $s = ''){
    
    echo '<br/>date: ' . date('Y-m-d H:i:s', $time);

    echo '<br/>'.$s.' date("d", $time): ' . date("d", $time);
    echo '<br/>'.$s.' date("Y-n-j G:i:s", $time): ' . date('Y-n-j G:i:s', $time);
    echo '<br/>当月最后一天';
    echo '<br/>'.$s.' date("t", $time): ' . date('t', $time);
    echo '<br/>'.$s.' date("Y-m-t", $time): ' . date("Y-m-t", $time);
    echo '<br/>'.$s.' date("Y-m-d H:i:s", strtotime(date("Y-m-01 H:i:s", $time) . "+1 month -1 day"))： ' . date("Y-m-d H:i:s", strtotime(date("Y-m-01 H:i:s", $time) . '+1 month -1 day'));
    
    echo '<br/><br/>';
}

function dmonth($time1, $time2, $s = ''){
    
    echo '<br/>date: ' . date('Y-m-d H:i:s', $time1);
    echo '<br/>date: ' . date('Y-m-d H:i:s', $time2);
    
    $y = abs((int)date("Y", $time2)-(int)date("Y", $time1));
    $m = abs((int)date("n", $time2)-(int)date("n", $time1));

    echo '<br/>'.$s.' 相差月份 (abs((int)date("Y", $time2)-(int)date("Y", $time1))*10)+abs((int)date("n", $time2)-(int)date("n", $time1)): ' . (($y*12)+$m);
    
    echo '<br/><br/>';
}

function dday($time1, $time2, $s = ''){
    
    echo '<br/>date: ' . date('Y-m-d H:i:s', $time1);
    echo '<br/>date: ' . date('Y-m-d H:i:s', $time2);
    
    $d = intval(abs($time1-$time2)/(60*60*24));

    echo '<br/>'.$s.' 相差天数 intval(abs($time1-$time2)/(60*60*24)): ' . $d;
    
    echo '<br/><br/>';
}