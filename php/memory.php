<?php
/**
 * PHP偶尔会爆一下如下 错误Allowed memory size of  xxx bytes exhausted at xxx:xxx (tried to allocate xxx bytes)
 */

ini_set('memory_limit','2M');//本机最低2M

test2();

function test1() {
    $str = 'Hello word!';
    for($i = 0; $i<50000000; $i++){
        $sv = 'str'.$i;
        $$sv = $str;
        unset($$sv);
    }
}

function test2($k = 5) {
    $str = [['name'=>'Hello word!']];
    $arr = array_pad($str, 10, $str[0]);
    foreach ($arr as $item){
        if($k > 0) test2($k-1);
        else return $item;
    }
    //unset($str,$arr);
}

function test3() {
    $str = [['name'=>'Hello word!']];
    $arr = array_pad($str, 100, $str[0]);
    $arr = json_decode(json_encode($arr));
    foreach ($arr as $key=>$item){
        $item->name = 1;
        unset($arr[$key]);
    }
    var_dump($key,$item);
}
