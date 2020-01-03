<?php

//  php loadImg.php
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

/*
var src = '';

var iObj = document.querySelectorAll('#mod-detail-bd .tab-trigger:not(.tab-gallery-main-video)');
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


var str = '';
var skip = 0;
var iObj = document.querySelectorAll('#mod-detail-attributes table td');
iObj.forEach(function(v, i){
	if(skip == 1) { skip = 0; return true;}
	var ss = v.innerText;
	if(ss == '建议零售价' || ss == '是否进口' || ss == '货号' 
	|| ss == '上市时间' || ss == '生产许可证号' || ss == '是否跨境货源' || 
	ss == '主要下游平台' || ss == '主要销售地区' || ss == '有可授权的自有品牌'
	||  ss == '箱规' ||  ss == '条码'){
		skip = 1; return true;
	}else{
		
		if(i%2 != 0) str += ': '+ss;
		else str += '; '+ss;
	}
});
console.log(str.substr(2));

*/

$base = 'C:/Users/Administrator/Desktop/xx/';
$per = 'sx' . substr(time(), 6) . '_';
$img = 'https://cbu01.alicdn.com/img/ibank/2018/345/279/8826972543_243417765.jpg https://cbu01.alicdn.com/img/ibank/2019/532/323/10712323235_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/585/989/8846989585_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/512/489/8826984215_243417765.jpg https://cbu01.alicdn.com/img/ibank/2019/767/532/10964235767_243417765.jpg https://cbu01.alicdn.com/img/ibank/2019/947/555/10858555749_243417765.jpg https://cbu01.alicdn.com/img/ibank/2019/279/243/10886342972_243417765.jpg https://cbu01.alicdn.com/img/ibank/2019/532/323/10712323235_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/715/189/8826981517_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/168/580/8863085861_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/363/910/8847019363_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/183/310/8847013381_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/386/899/8846998683_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/355/100/8847001553_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/021/699/8826996120_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/109/669/8826966901_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/288/779/8846977882_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/803/220/8847022308_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/004/121/8863121400_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/914/099/8826990419_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/257/601/8863106752_243417765.jpg https://cbu01.alicdn.com/img/ibank/2018/173/867/8826768371_243417765.jpg http://cloud.video.taobao.com/play/u/3928370308/p/2/e/6/t/1/225193877659.mp4';
$img = explode(' ', $img);
foreach($img as $k => $v){
	$ext = substr($v, strrpos($v, '.'));
	$name = $base . $per . $k . $ext;
	file_put_contents($name, file_get_contents($v));
}
