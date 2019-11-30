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
$method = '';
//if ( $msgType == 'event') { //事件推送
//    switch (strtolower($data->Event)) {
//        //点击菜单拉取消息时的事件推送
//        case 'click':
//            $method = 'pushClick';
//            break;
//        //点击菜单跳转链接时的事件推送
//        case 'view':
//            $method = 'pushView';
//            break;
//        //扫码推事件的事件推送
//        case 'scancode_push':
//            $method = 'pushView';
//            break;
//        //扫码推事件且弹出“消息接收中”提示框的事件推送
//        case 'scancode_waitmsg':
//            $method = 'pushScancodeWaitmsg';
//            break;
//        //弹出系统拍照发图的事件推送
//        case 'pic_sysphoto':
//            $method = 'pushPicSysphoto';
//            break;
//        //弹出拍照或者相册发图的事件推送
//        case 'pic_photo_or_album':
//            $method = 'pushPicPhotoOrAlbum';
//            break;
//        //弹出微信相册发图器的事件推送
//        case 'pic_weixin':
//            $method = 'pushPicWeixin';
//            break;
//        //弹出地理位置选择器的事件推送
//        case 'location_select':
//            $method = 'pushLocationSelect';
//            break;
//        //点击菜单跳转小程序
//        case 'view_miniprogram':
//            $method = 'pushViewMiniprogram';
//            break;
//
//
//        //关注/取消关注事件
//        case 'subscribe':
//            //扫描带参数二维码事件 未关注
//            //二维码事件有EventKey qrscene_1001
//            $method = 'pushSubscribe';
//            break;
//        case 'unsubscribe':
//            $method = 'pushUnsubscribe';
//            break;
//        //扫描带参数二维码事件 已关注
//        case 'scan':
//            $method = 'pushScan';
//            break;
//        //上报地理位置事件
//        //用户同意上报地理位置后，每次进入公众号会话时，都会在进入时上报地理位置，或在进入会话后每5秒上报一次地理位置
//        case 'location':
//            $method = 'pushLocation';
//            break;
//
//        case 'masssendjobfinish'://MASSSENDJOBFINISH 事件推送群发结果
//            $method = 'pushMasssendjobfinish';
//            break;
//
//        case 'templatesendjobfinish'://TEMPLATESENDJOBFINISH 模版消息事件推送
//            $method = 'pushTemplatesendjobfinish';
//            break;
//
//        //微信认证事件推送
//        //资质认证成功（此时立即获得接口权限）
//        case 'qualification_verify_success':
//            $method = 'pushQualificationVerifySuccess';
//            break;
//        //资质认证失败
//        case 'qualification_verify_fail':
//            $method = 'pushQualificationVerifyFail';
//            break;
//        //名称认证成功（即命名成功）
//        case 'naming_verify_success':
//            $method = 'pushNamingVerifySuccess';
//            break;
//        //名称认证失败（这时虽然客户端不打勾，但仍有接口权限）
//        case 'naming_verify_fail':
//            $method = 'pushNamingVerifyFail';
//            break;
//        //年审通知
//        case 'annual_renew':
//            $method = 'pushAnnualRenew';
//            break;
//        //认证过期失效通知审通知
//        case 'verify_expired':
//            $method = 'pushVerifyExpired';
//            break;
//
//
//        default:
//            break;
//    }
//} else if ( $msgType = 'text') { //文本消息
//    $method = 'normalText';
//} else if ( $msgType = 'image') { //图片消息
//    $method = 'normalImage';
//} else if ( $msgType = 'voice') { //语音消息
//    $method = 'normalVoice';
//} else if ( $msgType = 'video') { //视频消息
//    $method = 'normalVideo';
//} else if ( $msgType = 'shortvideo') { //小视频消息
//    $method = 'normalShortvideo';
//} else if ( $msgType = 'location') { //地理位置消息
//    $method = 'normalLocation';
//} else if ( $msgType = 'link') { //链接消息
//    $method = 'normalLink';
//}
if ( $msgType == 'event') { //事件推送
    $method = 'push' . str_replace(' ', '',ucwords(str_replace('_', ' ', strtolower($data->Event))));
} else {
    $method = 'normal' . ucwords($msgType);
}
if($method && method_exists($push,$method)){
    echo $push->$method();
}
exit('');