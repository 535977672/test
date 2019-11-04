<?php

require 'm/Ct.php';
//var q = ''; $('.list>tr').each(function(i,v){q += ' ' + $(v).attr('class').substr(5);});console.log(q.substr(1));
$data = '';


$obj = new Ct();
$user = $obj->insertData($data);