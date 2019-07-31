<?php
$price = [
    ['name'=>232,'code'=>'234rtfdsw'],
    ['name'=>2345,'code'=>'wer'],
    ['name'=>23456,'code'=>'fggbvc'],
    ['name'=>4567,'code'=>'345tgfvc'],
];
header('Content-Type:application/json; charset=utf-8');
exit(json_encode(['status' => 200, 'msg' => 'success', 'data' => $price]));