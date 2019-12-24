<?php

//  php loadImg.php
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

/*
var src = '';

var iObj = document.querySelectorAll('#mod-detail-bd .tab-trigger');
iObj.forEach(function(v, i){
	src += ' '+JSON.parse(v.getAttribute("data-imgs")).original;
});

var iObj = document.querySelectorAll('#desc-lazyload-container img');
iObj.forEach(function(v, i){
	src += ' '+v.getAttribute("src");
});

var video = document.querySelector('#detail-main-video-content video');
if(video) src += ' '+video.getAttribute("src");
console.log(src.substr(1));

*/

$base = 'C:/Users/Administrator/Desktop/12/';
$per = 'sx' . substr(time(), 6) . '_';
$img = '';
$img = explode(' ', $img);
foreach($img as $k => $v){
	$ext = substr($v, strrpos($v, '.'));
	$name = $base . $per . $k . $ext;
	file_put_contents($name, file_get_contents($v));
}
