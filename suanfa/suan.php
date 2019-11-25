<?php

//$re = seriesStr(5);

$re = product(986457656);

var_dump($re);

/**
 * $re = seriesStr(5);
 * 产生无连续重复部分的字符串
 * 描述：编写程序，产生由1，2，3这3个数字符号所构成、长度为n的字符串，并且在字符串中对于任何一个子串而言，都不会有相邻的、完全相同的子串
 * 输入：字符串长度
 * 输出：无相邻重复子串的所有字符串，每个字符串换行输出
 */
function seriesStr($n, $str = ''){
    $base = [1,2,3];
    $s = [];
    $base = array_unique($base);
    $len = strlen($str);
    if($len == $n) {
        foreach ($base as $v) {
            if(strpos($str, (string)$v) === false) return [];
        }
        return [$str];
    }
    $end = substr($str, -1);
    foreach ($base as $v) {
        if($end != $v) $s = array_merge($s, seriesStr($n, $str . $v));
    }
    return $s;
}


/**
 * 求某正整数插入乘号后乘积的最大值
 * 描述：编程实现在一个9位数的正整数n中插入4个乘号，使分得的5个整数的乘积最大
 * 输入：正整数n
 * 输出：被分得的5个整数、得到的最大乘积值
 */

function product($n){
    //array_product()
    $s = fen($n);
    $n2 = '';
    $arr = [];
    foreach ($s as $v) {
        $tmp = explode('*', $v);
        $tmp2 = array_product($tmp);
        if(!$arr) {
            $tmp['p'] = $tmp2;
            $arr = $tmp;
        } else if($arr['p']<$tmp2){
            $tmp['p'] = $tmp2;
            $arr = $tmp;
        }
    }
    return $arr;
}

function fen($n, $g = []){
    if(strlen($n) < 9) return [];
    if(!$g) $g = [1,2,3,4];
    $str = $n;
    foreach ($g as $k => $v)  {
        $str = substr_replace($str,'*', $v+$k, 0);
    }
    if( $g[3] == 8) {
        if($g[3]-$g[2]>1) {$g[2] = $g[2]+1; $g[3] = $g[2]+1;}
        else if($g[2]-$g[1]>1) {$g[1] = $g[1]+1; $g[2] = $g[1]+1; $g[3] = $g[2]+1;}
        else if($g[1]-$g[0]>1) {$g[0] = $g[0]+1; $g[1] = $g[0]+1; $g[2] = $g[1]+1; $g[3] = $g[2]+1;}
        else return [$str];
    }else{
        $g[3] = $g[3]+1;
    }
    $re = fen($n, $g);
    return array_merge([$str], $re);
}