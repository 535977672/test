<?php
error_reporting(E_ALL&~E_NOTICE);
require 'Msql.php';

set_time_limit(0);
ini_set('memory_limit', '-1');

pa3d();
echo 'ok';

function parseData($id = 10715){
    $obj = new Msql();
    $sql = 'select * from o3d where id > ' . $id;
    $data = $obj->select($sql);
    foreach($data as $d){
        $d = parsed($d);
        $sql = "update `o3d` set `snum` = '".$d['snum']."',`dated` = '".$d['dated']."',`sum` = ".$d['sum'].",`od` = ".$d['od'].",`td` = ".$d['td'].",`sd` = ".$d['sd'].",`g` = ".$d['g'].",`s` = ".$d['s'].",`b` = ".$d['b'].",`timed` = ".$d['timed'].",`type` = ".$d['type']." where `o3d`.`id` = ".$d['id'];
        $obj->update($sql);
    }
}

function allData($table = 'o3d', $where = ''){
    $obj = new Msql();
    $sql = 'select * from ' . $table . '';
    if($where) $sql .= ' where '.$where;
    return $obj->select($sql);
}

function counts($table, $where = ''){
    $obj = new Msql();
    $sql = 'select count(*) as count from ' . $table . '';
    if($where) $sql .= ' where '.$where;
    return $obj->count($sql);
}

function sum($table, $field, $where = ''){
    $obj = new Msql();
    $sql = 'select sum('.$field.') as sum from ' . $table . '';
    if($where) $sql .= ' where '.$where;
    return $obj->sum($sql);
}

function parsed($data){
    $data['timed'] = strtotime(str_replace(' 星', '', str_replace('/', '-', $data['dated'])));
    $data['dated'] = date('Y-m-d', $data['timed']);
    $num = str_split($data['num']);
    $data['od'] = abs($num[0] - $num[1]);
    $data['td'] = abs($num[0] - $num[2]);
    $data['sd'] = abs($num[1] - $num[2]);
    $data['g'] = $num[2];
    $data['s'] = $num[1];
    $data['b'] = $num[0];
    if($num[0] != $num[1] && $num[1] != $num[2] && $num[0] != $num[2]){
        $data['type'] = 6;
    }else if($num[0] == $num[1] && $num[1] == $num[2]){
        $data['type'] = 1;
    }else{
        $data['type'] = 3;
    }
    sort($num, SORT_NUMERIC);
    $data['snum'] = implode('', $num);
    $data['sum'] = array_sum($num);
    return $data;
}

//基础数据创建
function base(){
    exit;
    $all = [];
    for($i = 0; $i < 1000; $i++){
        $data = [];
        $num = $i<10 ? '00'.$i : ($i<100 ? '0'.$i : $i);
        $num = str_split($num);
        sort($num, SORT_NUMERIC);
        $data['num'] = implode('', $num);
        if(array_key_exists($data['num'], $all)) continue;
        if($num[0] != $num[1] && $num[1] != $num[2] && $num[0] != $num[2]){
            $data['type'] = 6;
        }else if($num[0] == $num[1] && $num[1] == $num[2]){
            $data['type'] = 1;
        }else{
            $data['type'] = 3;
        }
        $all[$data['num']] = $data;
    }
    $obj = new Msql();
    $sql = "INSERT INTO `base` (`num`, `type`) VALUES ";
    foreach ($all as $v) {
       $sql .= "('".$v['num']."', '".$v['type']."'), ";
    }
    $sql = substr($sql, 0, -2);
    $obj->add($sql);
}

//每组号的总概率计算
function probability(){
    //var_dump(sum('base', 'pa'));exit;
    
    $obj = new Msql();
    $data = allData('base');
    $total = counts('o3d');
    foreach($data as $d){
        //总概率
        $curcounts = counts('o3d', 'snum=\''.$d['num'].'\'');
        $p = bcmul(bcdiv($curcounts, $total, 11), 100, 9);
        $sql = "update `base` set `pa` = '".$p."' where `num` = ".$d['num'];
        $obj->update($sql);
    }
}

//周率，月率
function pa3d(){
    $obj = new Msql();
    $t = $obj->select('select * from `total` where `id`=1');
    if(!empty($t)){
        $wc = json_decode($t[0]['wc'], true);
        $mc = json_decode($t[0]['mc'], true);
        $yc = json_decode($t[0]['yc'], true);
        $total = $t[0]['total'];
        $timed = $t[0]['timed'];
    }else{
        $wc = $mc = $yc = [];
        $total = 0;
        $timed = 0;
    }
    $sql = 'select `id`,`snum`,`dated` from `o3d` where `timed` > '.$timed.' order by `timed` asc';
    $data = $obj->select($sql);
    $sqls = "INSERT INTO `o3d_pa` (`id`"
            . ", `wp1`, `wp2`, `wp3`, `wp4`, `wp5`, `wp6`, `wp7`, `wp8`, `wp9`, `wp10`, `wp11`, `wp12`"
            . ", `mp1`, `mp2`, `mp3`, `mp4`, `mp5`, `mp6`, `mp7`, `mp8`, `mp9`, `mp10`, `mp11`, `mp12`"
            . ", `yp1`, `yp2`, `yp3`, `yp4`, `yp5`, `yp6`, `yp7`, `yp8`, `yp9`, `yp10`, `yp11`, `yp12`"
            . ") VALUES ";
    foreach($data as $k=>$d){
        $total++;
        $wp = $mp = $yp = [];
        //总概率
        for($i = 1; $i < 13; $i++){
            $wc[$i] += counts('o3d', 'snum=\''.$d['snum'].'\' and timed='. strtotime($d['dated'] . " -$i week"));
            $mc[$i] += counts('o3d', 'snum=\''.$d['snum'].'\' and timed='. strtotime($d['dated'] . " -$i month"));
            $yc[$i] += counts('o3d', 'snum=\''.$d['snum'].'\' and timed='. strtotime($d['dated'] . " -$i year"));
            $wp[$i] = bcmul(bcdiv($wc[$i], $total, 11), 100, 9);
            $mp[$i] = bcmul(bcdiv($mc[$i], $total, 11), 100, 9);
            $yp[$i] = bcmul(bcdiv($yc[$i], $total, 11), 100, 9);
        }
        $sql = $sqls ."(" . $d['id'] .", ". "'".implode("', '", $wp)."', '".implode("', '", $mp)."', '".implode("', '", $yp)."')";
        $obj->conn->beginTransaction();
        $obj->add($sql);
        $obj->add("update `total` set `total`=$total, `wc`='". json_encode($wc)."', `mc`='". json_encode($mc)."', `yc`='". json_encode($yc)."', `timed`=".strtotime($d['dated'])." where `id`=1");
        $obj->conn->commit();
    }
}














