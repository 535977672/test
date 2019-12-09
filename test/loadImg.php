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
$img = 'https://cbu01.alicdn.com/img/ibank/2019/296/696/12527696692_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/055/839/12451938550_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/398/096/12527690893_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/207/054/12490450702_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/966/749/12451947669_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/257/744/12490447752_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/844/327/12527723448_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/807/659/12451956708_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/239/807/12527708932_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/440/818/12534818044_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/467/197/12534791764_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/834/746/12497647438_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2019/255/836/12497638552_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2017/271/021/4612120172_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2017/620/621/4612126026_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2017/187/620/4615026781_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2017/587/650/4611056785_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2017/804/021/4612120408_1818798715.jpg https://cbu01.alicdn.com/img/ibank/2017/262/621/4612126262_1818798715.jpg http://cloud.video.taobao.com/play/u/990025459/p/2/e/6/t/1/240905497576.mp4';
$img = explode(' ', $img);
foreach($img as $k => $v){
	$ext = substr($v, strrpos($v, '.'));
	$name = $base . $per . $k . $ext;
	file_put_contents($name, file_get_contents($v));
}
