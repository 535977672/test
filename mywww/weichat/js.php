<?php
require_once __DIR__ . "/jssdk.php";
$jssdk = new JSSDK();

//通过code换取网页授权access_token
//5分钟未被使用自动过期
$code = isset($_GET['code'])?$_GET['code']:'';
$state = isset($_GET['state'])?$_GET['state']:'';
$userInfo = '';
if ($code) {
    $userInfo = $jssdk->getUserInfoByCode($code);
}
if ($userInfo['userInfo']) {
    $userInfo = json_encode($userInfo['userInfo'], JSON_UNESCAPED_UNICODE);
} else {
    var_dump($jssdk->getMsg());
}

//获取微信配置信息
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
</head>
<body>
<div>用户信息</div>
<div><?php echo $userInfo;?></div>
</body>
<script src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    var code = '<?php echo $code;?>';
    var userInfo = '<?php echo $userInfo;?>';
    if (!code) {
        //第一步：用户同意授权，获取code
        window.document.location.href = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='+'<?php echo $signPackage["appId"];?>'+'&redirect_uri='+encodeURIComponent(window.location.protocol+"//"+window.location.host+"/weichat/js.php")+'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    } else {
        console.log(userInfo);
        wx.config({
            //debug: true,
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <?php echo $signPackage["timestamp"];?>,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareWeibo', 'onMenuShareQZone', 'startRecord',
                'stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice',
                'downloadVoice', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'translateVoice',
                'getNetworkType', 'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems',
                'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow', 'scanQRCode',
                'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard'
            ]
        });

        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，
        // 也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
        wx.error(function (res) {
            console.log('000', res);
        });

        // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，
        // 所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。
        // 对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
        wx.ready(function () {
            //判断当前客户端版本是否支持指定JS接口
            wx.checkJsApi({
                // 1. 需要检测的JS接口列表
                jsApiList: [
                    'updateAppMessageShareData',
                    'updateTimelineShareData',
                    'onMenuShareWeibo',
                    'onMenuShareQZone',
                    'startRecord',
                    'stopRecord',
                    'onVoiceRecordEnd',
                    'playVoice',
                    'pauseVoice',
                    'stopVoice',
                    'onVoicePlayEnd',
                    'uploadVoice',
                    'downloadVoice',
                    'chooseImage',
                    'previewImage',
                    'uploadImage',
                    'downloadImage',
                    'translateVoice',
                    'getNetworkType',
                    'openLocation',
                    'getLocation',
                    'hideOptionMenu',
                    'showOptionMenu',
                    'hideMenuItems',
                    'showMenuItems',
                    'hideAllNonBaseMenuItem',
                    'showAllNonBaseMenuItem',
                    'closeWindow',
                    'scanQRCode',
                    'chooseWXPay',
                    'openProductSpecificView',
                    'addCard',
                    'chooseCard',
                    'openCard'
                ],
                success: function (res) {
                    // 以键值对的形式返回，可用的api值true，不可用为false
                    // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                    console.log(111, res);
                }
            });


            // 2. 自定义“分享给朋友”及“分享到QQ”按钮的分享内容（1.4.0）
            // 需在用户可能点击分享按钮前就先调用
            wx.updateAppMessageShareData({
                title: '哎呦不错哦！', // 分享标题
                desc: '你是真的真的真的很不错。', // 分享描述
                link: window.location.protocol + "//" + window.location.host + "/weichat/js.php", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://img.alicdn.com/imgextra/i2/4114133420/O1CN01PLGSSG1b8P1VLGkI5_!!4114133420.jpg_430x430q90.jpg', // 分享图标
                success: function () {
                    console.log(222, '分享给朋友 分享到QQ');
                },
                fail: function () {
                    console.log(222, '失败');
                },
                cancel: function () {
                    console.log(222, '取消');
                }
            });

            // 3. 自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容（1.4.0）
            wx.updateTimelineShareData({
                title: '哎呦不错哦！', // 分享标题
                link: window.location.protocol + "//" + window.location.host + "/weichat/js.php", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://img.alicdn.com/imgextra/i2/4114133420/O1CN01PLGSSG1b8P1VLGkI5_!!4114133420.jpg_430x430q90.jpg', // 分享图标
                success: function () {
                    console.log(333, '分享到朋友圈 分享到QQ空间');
                },
                fail: function () {
                    console.log(333, '失败');
                },
                cancel: function () {
                    console.log(333, '取消');
                }
            });

            // 4. 获取“分享到腾讯微博”按钮点击状态及自定义分享内容接口
            wx.onMenuShareWeibo({
                title: '哎呦不错哦！', // 分享标题
                desc: '我不要你觉得，我要我觉得。', // 分享描述
                link: window.location.protocol + "//" + window.location.host + "/weichat/js.php", // 分享链接
                imgUrl: 'http://img.alicdn.com/imgextra/i2/4114133420/O1CN01PLGSSG1b8P1VLGkI5_!!4114133420.jpg_430x430q90.jpg', // 分享图标
                success: function () {
                    console.log(444, '分享到腾讯微博');
                },
                fail: function () {
                    console.log(444, '失败');
                },
                cancel: function () {
                    console.log(444, '取消');
                }
            });

            // 5. 共享收货地址接口
            // wx.openAddress({
            //     success: function (res) {
            //         var userName = res.userName; // 收货人姓名
            //         var postalCode = res.postalCode; // 邮编
            //         var provinceName = res.provinceName; // 国标收货地址第一级地址（省）
            //         var cityName = res.cityName; // 国标收货地址第二级地址（市）
            //         var countryName = res.countryName; // 国标收货地址第三级地址（国家）
            //         var detailInfo = res.detailInfo; // 详细收货地址信息
            //         var nationalCode = res.nationalCode; // 收货地址国家码
            //         var telNumber = res.telNumber; // 收货人手机号码
            //
            //         console.log(555, '共享收货地址', res);
            //     }
            // });

            // 6. 批量隐藏功能按钮接口
            //wx.hideMenuItems({
            //    menuList: []
            //);

            // 7. 批量显示功能按钮接口
            wx.showMenuItems({
                menuList: ["menuItem:exposeArticle", "menuItem:setFont", "menuItem:dayMode", "menuItem:nightMode", "menuItem:refresh",
                    "menuItem:share:appMessage", "menuItem:share:timeline", "menuItem:share:qq", "menuItem:share:weiboApp",
                    "menuItem:favorite", "menuItem:share:QZone", "menuItem:copyUrl", "menuItem:openWithQQBrowser"]
            });
        });
    }
</script>
</html>
