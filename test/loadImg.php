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
$img = 'https://cbu01.alicdn.com/img/ibank/2020/444/977/13470779444_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/975/058/13425850579_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/315/958/13425859513_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/298/767/13470767892_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/957/353/13387353759_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/677/308/13403803776_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/821/038/13403830128_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/718/459/13365954817_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/080/521/13449125080_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/409/401/13449104904_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/329/849/13365948923_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/142/128/13403821241_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/891/189/13365981198_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/758/759/13365957857_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/894/369/13365963498_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/669/249/13365942966_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/847/069/13365960748_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/550/099/13365990055_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/399/375/13435573993_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/733/006/13435600337_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/655/337/13480733556_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/220/157/13480751022_117625341.jpg https://cbu01.alicdn.com/img/ibank/2020/687/585/13435585786_117625341.jpg';
$img = explode(' ', $img);
foreach($img as $k => $v){
	$ext = substr($v, strrpos($v, '.'));
	$name = $base . $per . $k . $ext;
	file_put_contents($name, file_get_contents($v));
}
