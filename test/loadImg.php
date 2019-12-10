<?php

//  php loadImg.php
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

/*
var src = '';
var iObj = document.querySelectorAll('#desc-lazyload-container img');
iObj.forEach(function(v, i){
	src += ' '+v.getAttribute("src");
});

var video = document.querySelector('#detail-main-video-content video');
if(video) src += ' '+video.getAttribute("src");
console.log(src.substr(1));

<p style="background-color: #fff0f5;text-align: center;"><b style="margin: 0.0px;padding: 0.0px;font-weight: 700;line-height: 40.0px;color: #7f1911;font-size: 12.0px;font-style: normal;letter-spacing: normal;orphans: 2;text-align: start;text-indent: 0.0px;text-transform: none;white-space: nowrap;widows: 2;word-spacing: 0.0px;background-color: #fff8f5;"><span style="background-color: #fff0f5;">消费提醒：</span></b><span style="color: #7f1911;font-size: 12.0px;font-style: normal;font-weight: normal;letter-spacing: normal;orphans: 2;text-align: start;text-indent: 0.0px;text-transform: none;white-space: nowrap;widows: 2;word-spacing: 0.0px;float: none;display: inline;"><span><span style="background-color: #fff0f5;">&nbsp;</span></span></span><span style="margin: 0.0px;padding: 0.0px;line-height: 40.0px;color: #7f1911;font-size: 12.0px;font-style: normal;font-weight: normal;letter-spacing: normal;orphans: 2;text-align: start;text-indent: 0.0px;text-transform: none;white-space: nowrap;widows: 2;word-spacing: 0.0px;"><span style="background-color: #fff0f5;">国家药监局提示您：请正确认识化妆品功效，化妆品不能替代药品，不能治疗皮肤病等疾病。</span></span></p>
*/

$base = 'C:/Users/Administrator/Desktop/xx/';
$per = 'sx' . substr(time(), 6) . '_';
$img = 'https://cbu01.alicdn.com/img/ibank/2019/861/259/12605952168_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/291/326/12681623192_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/520/169/12605961025_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/559/995/12681599955_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/254/420/12643024452_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/218/600/12643006812_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/615/720/12643027516_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/680/330/12643033086_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/146/810/12643018641_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/408/900/12643009804_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/083/949/12605949380_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/661/536/12681635166_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/754/416/12681614457_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/201/836/12681638102_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/975/806/12681608579_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/332/236/12681632233_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/710/146/12681641017_815999829.jpg https://cbu01.alicdn.com/img/ibank/2019/783/259/12605952387_815999829.jpg';
$img = explode(' ', $img);
foreach($img as $k => $v){
	$ext = substr($v, strrpos($v, '.'));
	$name = $base . $per . $k . $ext;
	file_put_contents($name, file_get_contents($v));
}
