<?php
/**
 * 事件推送
 */
require_once "jssdk.php";
require_once "push.php";

$jssdk = new JSSDK();
//验证消息的确来自微信服务器
//验证一次后即可注释掉
//$check = $jssdk->checkSignature();
//if ($check) {
//    exit($_GET["echostr"]);
//}else{
//    exit();
//}

$post_str = $GLOBALS["HTTP_RAW_POST_DATA"];
if(empty($post_str)) {
    $post_str = file_get_contents("php://input");
}
if(empty($post_str)) {
    echo "";
    exit;
}
//$jssdk->setLog($post_str);
//xml字符串转数组
$data = $jssdk->xmlToObjOrArr($post_str);



//微信服务器在五秒内收不到响应会断掉连接，并且重新发起请求，总共重试三次。
//无法保证在五秒内处理并回复，可以直接回复空串。
$msgType = $data->MsgType;
$push = new Push($data);
//if ( $msgType == 'event') { //事件推送
//    $str = '';
//    switch (strtolower($data->Event)) {
//        //点击菜单拉取消息时的事件推送
//        case 'click':
//            $str = $push->pushClick();
//            break;
//        //点击菜单跳转链接时的事件推送
//        case 'view':
//            $str = $push->pushView();
//            break;
//        //扫码推事件的事件推送
//        case 'scancode_push':
//            $str = $push->pushView();
//            break;
//        //扫码推事件且弹出“消息接收中”提示框的事件推送
//        case 'scancode_waitmsg':
//            $str = $push->pushScancodeWaitmsg();
//            break;
//        //弹出系统拍照发图的事件推送
//        case 'pic_sysphoto':
//            $str = $push->pushPicSysphoto();
//            break;
//        //弹出拍照或者相册发图的事件推送
//        case 'pic_photo_or_album':
//            $str = $push->pushPicPhotoOrAlbum();
//            break;
//        //弹出微信相册发图器的事件推送
//        case 'pic_weixin':
//            $str = $push->pushPicWeixin();
//            break;
//        //弹出地理位置选择器的事件推送
//        case 'location_select':
//            $str = $push->pushLocationSelect();
//            break;
//        //点击菜单跳转小程序
//        case 'view_miniprogram':
//            $str = $push->pushViewMiniprogram();
//            break;
//
//
//        //关注/取消关注事件
//        case 'subscribe':
//            //扫描带参数二维码事件 未关注
//            //二维码事件有EventKey qrscene_1001
//            $str = $push->pushSubscribe();
//            break;
//        case 'unsubscribe':
//            $str = $push->pushUnsubscribe();
//            break;
//        //扫描带参数二维码事件 已关注
//        case 'scan':
//            $str = $push->pushScan();
//            break;
//        //上报地理位置事件
//        //用户同意上报地理位置后，每次进入公众号会话时，都会在进入时上报地理位置，或在进入会话后每5秒上报一次地理位置
//        case 'location':
//            $str = $push->pushLocation();
//            break;
//        case 'masssendjobfinish'://MASSSENDJOBFINISH 事件推送群发结果
//            $str = $push->pushMasssendjobfinish();
//            break;
//        case 'templatesendjobfinish'://TEMPLATESENDJOBFINISH 模版消息事件推送
//            $str = $push->pushTemplatesendjobfinish();
//            break;
//        default:
//            break;
//    }
//    return $str;
//} else if ( $msgType = 'text') { //文本消息
//
//} else if ( $msgType = 'image') { //图片消息
//
//} else if ( $msgType = 'voice') { //语音消息
//
//} else if ( $msgType = 'video') { //视频消息
//
//} else if ( $msgType = 'shortvideo') { //小视频消息
//
//} else if ( $msgType = 'location') { //地理位置消息
//
//} else if ( $msgType = 'link') { //链接消息
//
//}
if ( $msgType == 'event') { //事件推送
    $event = 'push' . str_replace(' ', '',ucwords(str_replace('_', ' ', strtolower($data->Event))));
    echo $push->$event();
} else {
    $normal = 'normal' . ucwords($msgType);
    echo $push->$normal();
}
exit('');