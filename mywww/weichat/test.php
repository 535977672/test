<?php
date_default_timezone_set("Asia/Shanghai");
require_once "jssdk.php";
$re = '';
$jssdk = new JSSDK();

//menu();
//material();
//mass();
template();

function dumpLog($re, $jssdk, $log = '') {
    if (!$re && $jssdk->getMsg()) {
        var_dump($jssdk->getMsg());
        echo "<br/>";
    } else if($re){
        var_dump($re);
        echo "<br/>";
    }
    if($log) $jssdk->setLog($log);
}

/**
 * 1. 创建菜单
 */
function menu() {
    $re = '';
    $jssdk = new JSSDK();
    $menuData = [
        "button" => [
            [
                "name" => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "跳转网页",
                        "url" => "https://www.baidu.com/index.php"
                    ],
                    [
                        "type" => "click",
                        "name" => "点击",
                        "key" => "click_1001"
                    ],
                    [
                        "name" =>  "发送位置",
                        "type" =>  "location_select",
                        "key" =>  "location_1001"
                    ]
                ]
            ],
            [
                "name" =>  "扫码",
                "sub_button" =>  [
                    [
                        "type" =>  "scancode_waitmsg",
                        "name" =>  "扫码带提示",
                        "key" =>  "scancode_1001",
                        "sub_button" =>  [ ]
                    ],
                    [
                        "type" =>  "scancode_push",
                        "name" =>  "扫码推事件",
                        "key" =>  "scancode_1002",
                        "sub_button" =>  [ ]
                    ]
                ]
            ],
            [
                "name" =>  "发图",
                "sub_button" =>  [
                    [
                        "type" =>  "pic_sysphoto",
                        "name" =>  "系统拍照",
                        "key" =>  "pic_1001",
                        "sub_button" =>  [ ]
                    ],
                    [
                        "type" =>  "pic_photo_or_album",
                        "name" =>  "拍照或相册",
                        "key" =>  "pic_1002",
                        "sub_button" =>  [ ]
                    ],
                    [
                        "type" =>  "pic_weixin",
                        "name" =>  "微信相册",
                        "key" =>  "pic_1003",
                        "sub_button" =>  [ ]
                    ]
                ]
            ]
        ]
    ];
    //$re = $jssdk->createMenu($menuData);
    $re = $jssdk->getMenu();
    //$re = $jssdk->delMenu();
    if (!$re) var_dump($jssdk->getMsg());
    else  var_dump($re);
}

/**
 * 2. 多媒体素材管理
 */
function material() {
    $re = '';
    $jssdk = new JSSDK();
    //新增临时素材
    $file = './img/ten.jpg';
    //$re  = $jssdk->upload($file, 'image');  dumpLog($re, $jssdk, '新增临时素材' . $re);

    //获取临时素材
    $media_id = 'S7gxJj489QpNPytjsrVoBh8KcGbcEzKdpQSibz3WNaJqukMrcjW4_gwuCeResfoq';
    //$re = $jssdk->getUpload($media_id);
    //if(is_object($re) || !$re) dumpLog($re, $jssdk);
    //else file_put_contents('./img/11.jpg', $re);

    /**
     * 新增其他类型永久素材
     * 久视频素材需特别注意
     * 在上传视频素材时需要POST另一个表单，id为description，包含素材的描述信息，内容格式为JSON，格式如下：
     * {
     * "title":VIDEO_TITLE,
     * "introduction":INTRODUCTION
     * }
     */
    $file = './img/ten.jpg';
    //$re = $jssdk->addMaterial($file, 'image');  $jssdk->setLog('新增其他类型永久素材image ' . $re);
    //$re = $jssdk->addMaterial($file, 'thumb');  $jssdk->setLog('新增其他类型永久素材thumb ' . $re);

    //新增永久图文素材
    $data = [
        "articles" => [
            [
                "thumb_media_id" => "u8Mhpio4581bgW9enyp_rGprbHdfIOo44ScHNyteTnE", //图文消息缩略图的media_id，可以在素材管理-新增素材中获得
                "author" => "图文消息的作者",
                "title" => "图文消息的标题",
                "content_source_url" => "https://www.baidu.com/index.php", //阅读原文
                "content" => "<p>图文消息页面的内容，支持HTML标签。具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用，如需插入小程序卡片，可参考下文。</p><p>图文内的图片</p><p><img src='http://mmbiz.qpic.cn/mmbiz_jpg/dWu608mwHzMnOlPbRWIN9roKnjLPrz3Z69OcsGU1vVewqFusY38qpTcxlWVLQs6UEcFJeoNZLKic9BJtmVzgaiaA/0'></p>",
                "digest" => "图文消息的描述，如本字段为空，则默认抓取正文前64个字",
                "show_cover_pic" => 1, //是否显示封面，1为显示，0为不显示
                "need_open_comment" => 1, //Uint32	是否打开评论，0不打开，1打开
                "only_fans_can_comment" => 1 //Uint32	是否粉丝才可评论，0所有人可评论，1粉丝才可评论
            ],
            [
                "thumb_media_id" => "u8Mhpio4581bgW9enyp_rJqJxKp2V4ij3zskvWpeLP0",
                "author" => "菲菲",
                "title" => "Happy Day",
                "content_source_url" => "https://www.baidu.com/index.php",
                "content" => "<p>图文消息页面的内容，支持HTML标签。</p><p>具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用。</p><p>图文内的图片</p><p><img src='http://mmbiz.qpic.cn/mmbiz_jpg/dWu608mwHzMnOlPbRWIN9roKnjLPrz3Z69OcsGU1vVewqFusY38qpTcxlWVLQs6UEcFJeoNZLKic9BJtmVzgaiaA/0'></p>",
                "digest" => "描述",
                "show_cover_pic" => 0,
                "need_open_comment" => 1,
                "only_fans_can_comment" => 1
            ]
        ]
    ];
    //$re = $jssdk->add_news($data);  dumpLog($re, $jssdk, '新增永久图文素材' . $re);

    //修改永久图文素材
    $data = [
        "media_id" => "u8Mhpio4581bgW9enyp_rIVJEElBS61u9C6m-x_Q3Bc",
        "index" => 1,//第二个
        "articles" => [
                "thumb_media_id" => "u8Mhpio4581bgW9enyp_rJqJxKp2V4ij3zskvWpeLP0",
                "author" => "菲菲",
                "title" => "Happy Day",
                "content_source_url" => "https://www.baidu.com/index.php",
                "content" => "<p>修改图文消息页面的内容，支持HTML标签。</p><p>具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用。</p><p>图文内的图片</p><p><img src='http://mmbiz.qpic.cn/mmbiz_jpg/dWu608mwHzMnOlPbRWIN9roKnjLPrz3Z69OcsGU1vVewqFusY38qpTcxlWVLQs6UEcFJeoNZLKic9BJtmVzgaiaA/0'></p>",
                "digest" => "描述",
                "show_cover_pic" => 0,
                "need_open_comment" => 1,
                "only_fans_can_comment" => 1
            ]
    ];
    //$re = $jssdk->updateNews($data);  dumpLog($re, $jssdk);

    //获取永久素材
    $media_id = 'u8Mhpio4581bgW9enyp_rGprbHdfIOo44ScHNyteTnE';
    //$re = $jssdk->getMaterial($media_id);
    //if(is_object($re) || !$re) dumpLog($re, $jssdk);
    //else file_put_contents('./img/22.jpg', $re);

    //// 删除永久素材
    //$re = $jssdk->delMaterial($media_id);  dumpLog($re, $jssdk);

    //获取素材总数
    //$re = $jssdk->getMaterialCount();  dumpLog($re, $jssdk);

    //获取素材列表
    $data = [
        'type' => 'news',
        'offset' => '0',
        'count' => '20',
    ];
    //$re = $jssdk->batchGetMaterial($data);  dumpLog($re, $jssdk);

    //上传图文消息内的图片获取URL
    $file = realpath('./img/ten.jpg');
    //$re = $jssdk->uploadImg($file);
}

/**
 * 3. 群发接口和原创校验
 */
function mass() {
    $re = '';
    $jssdk = new JSSDK();

    //上传图文消息素材
    //一个图文消息支持1到8条图文
    $file = './img/ten.jpg';
    $thumb_media_id = '';
    //$thumb_media_id = $jssdk->upload($file, 'image');  dumpLog($re, $jssdk, '新增临时素材image ' . $thumb_media_id);
    $data = [
        "articles" => [
            [
                "thumb_media_id" => $thumb_media_id, //临时素材媒体ID
                "author" => "图文消息的作者",
                "title" => "图文消息的标题",
                "content_source_url" => "https://www.baidu.com/index.php", //阅读原文
                "content" => "<p>图文消息页面的内容，支持HTML标签。具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用，如需插入小程序卡片，可参考下文。</p><p>图文内的图片</p><p><img src='http://mmbiz.qpic.cn/mmbiz_jpg/dWu608mwHzMnOlPbRWIN9roKnjLPrz3Z69OcsGU1vVewqFusY38qpTcxlWVLQs6UEcFJeoNZLKic9BJtmVzgaiaA/0'></p>",
                "digest" => "图文消息的描述，如本字段为空，则默认抓取正文前64个字",
                "show_cover_pic" => 1, //是否显示封面，1为显示，0为不显示
                "need_open_comment" => 1, //Uint32	是否打开评论，0不打开，1打开
                "only_fans_can_comment" => 1 //Uint32	是否粉丝才可评论，0所有人可评论，1粉丝才可评论
            ],
            [
                "thumb_media_id" => $thumb_media_id,
                "author" => "菲菲",
                "title" => "Happy Day",
                "content_source_url" => "https://www.baidu.com/index.php",
                "content" => "<p>图文消息页面的内容，支持HTML标签。</p><p>具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用。</p><p>图文内的图片</p><p><img src='http://mmbiz.qpic.cn/mmbiz_jpg/dWu608mwHzMnOlPbRWIN9roKnjLPrz3Z69OcsGU1vVewqFusY38qpTcxlWVLQs6UEcFJeoNZLKic9BJtmVzgaiaA/0'></p>",
                "digest" => "描述",
                "show_cover_pic" => 0,
                "need_open_comment" => 1,
                "only_fans_can_comment" => 1
            ]
        ]
    ];
    //$re = $jssdk->uploadNews($data);  dumpLog($re, $jssdk, '上传图文消息素材uploadNews ' . $re);


    $touser = 'oul9P1fQqxD8sW4zyVAcm9W9hyo0';
    //群发图文消息
    $data = [
        "touser" => $touser,
        "mpnews" => [
            "media_id" => 'u8Mhpio4581bgW9enyp_rIVJEElBS61u9C6m-x_Q3Bc'
        ],
        "msgtype" => "mpnews",
        "send_ignore_reprint" => 0,
        //"clientmsgid" => "send_tag_2"
    ];
    //$re = $jssdk->massSendAll($data, [$touser, 'oul9P1f5bx7YSxFDs8pCEbhDJ8wE']); dumpLog($re, $jssdk, '群发图文消息 ' . json_encode($re));

    //群发预览
    //群发图文消息
    $data = [
        "touser" => $touser,
        "mpnews" => [
            "media_id" => 'QNJR64qzApv6C0X96eUTr6SX0EqXqGLyxQUJyeXLxB3E5jpG5dGbJlfdUZGYmvNi'
        ],
        "msgtype" => "mpnews",
        "send_ignore_reprint" => 0,
        "clientmsgid" => "send_tag_2"
    ];
    //$re = $jssdk->massPreview($data); dumpLog($re, $jssdk, '群发预览-群发图文消息 ' . $re);

    //群发文本
    $data = [
        "touser" => $touser,
        "text" => [
            "content" => '群发文本'
        ],
        "msgtype" => "text"
    ];
    //$re = $jssdk->massPreview($data); dumpLog($re, $jssdk, '群发预览-群发文本 ' . $re);

    //群发语音/音频
    $data = [
        "touser" => $touser,
        "voice" => [
            "media_id" => ''
        ],
        "msgtype" => "voice"
    ];
    //$re = $jssdk->massPreview($data); dumpLog($re, $jssdk, '群发预览-群发语音/音频 ' . $re);

    //群发图片
    $data = [
        "touser" => $touser,
        "image" => [
            "media_id" => 'EroocLVXeL2XKSxgqC1u8unlJc-EpIrdZbWDLg7MkScokxjAfCxlKpsAjlUZf9Hz'
        ],
        "msgtype" => "image"
    ];
    //$re = $jssdk->massPreview($data); dumpLog($re, $jssdk, '群发预览-群发图片 ' . $re);

    //群发视频
    $media_id = '';
    $video_media_id = '';
    //$video_media_id = $jssdk->uploadVideo($media_id, '群发视频', '群发视频描述');
    dumpLog($re, $jssdk, '群发视频 ' . $video_media_id);
    $data = [
        "touser" => $touser,
        "mpvideo" => [
            "media_id" => $video_media_id
        ],
        "msgtype" => "mpvideo"
    ];
    //$re = $jssdk->massPreview($data); dumpLog($re, $jssdk, '群发预览-群发视频 ' . $re);
}

/**
 * 4. 模板消息
 */
function template() {
    $re = '';
    $jssdk = new JSSDK();

    $templateId = 'newlTkasMJ39G0mq_sa6V9V3GjgBnZoxAiAp2pJHcIs';
    $time = date('Y-m-d H:i:s', time());
    $name = '您已成功下单';
    $touser = 'oul9P1fQqxD8sW4zyVAcm9W9hyo0';

    //$re = $jssdk->templateSetIndustry(1, 6);

    $re = $jssdk->templateGetIndustry(); dumpLog($re, $jssdk, '获取设置的行业信息 ' . json_encode($re, JSON_UNESCAPED_UNICODE));

    //$re = $jssdk->templateAddTemplate('');
    //$re = $jssdk->templateDelPT('');

    $re = $jssdk->templateGetAllPT(); dumpLog($re, $jssdk, '获取模板列表 ' . json_encode($re, JSON_UNESCAPED_UNICODE));

    $data = [
        "touser" => $touser,
        "template_id" => $templateId,
        "url" => "https://www.baidu.com/index.php",
        //"miniprogram" => [
        //    "appid" => "",
        //   "pagepath" => ""
        //],
        "data" => [
            "time" =>  [
                "value" => $time,
                "color" => "#173177"
           ],
           "name" => [
                "value" => $name,
                "color" => "#173177"
           ],
        ]
    ];
    //$re = $jssdk->templateSend($data); dumpLog($re, $jssdk, '发送模板消息 ' . $re);
}











