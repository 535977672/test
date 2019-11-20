<?php
class JSSDK {
    private $appId;
    private $appSecret;
    private $config;
    private $errMsg = [];
    private $config_path = __DIR__ . '/config/config.php';
    private $jsapi_ticket_path = __DIR__ . '/config/jsapi_ticket.php';
    private $access_token_path = __DIR__ . '/config/access_token.php';

    public function __construct($appId = '', $appSecret = '') {
        if ($appId) {
            $this->appId = $appId;
            $this->appSecret = $appSecret;
        } else {
            $this->config = json_decode($this->get_php_file($this->config_path));
            $this->appId = $this->config->appId;
            $this->appSecret = $this->config->appSecret;
        }
    }

    private function setMsg($code, $msg) {
        $this->errMsg[] = ['code' => $code, 'msg' => $msg];
    }

    public function getMsg() {
        return $this->errMsg;
    }

    /**
     * 验证消息的确来自微信服务器
     * @return bool
     */
    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = $this->config->TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("jsapi_ticket.php"));
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $this->set_php_file($this->jsapi_ticket_path, json_encode($data));
            } else {
                return false;
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    public function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("access_token.php"));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url)); //{"access_token":"ACCESS_TOKEN","expires_in":7200}
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file($this->access_token, json_encode($data));
            } else {
                $this->setMsg($res->errcode, $res->errmsg);
                return false;
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    private function genUrl($url, $params = '') {
        if (strpos($url, '/') === 0) {
            $url = substr($url, 1);
        }
        if (is_array($params)) {
            $params = http_build_query($params);
        }
        return "https://api.weixin.qq.com/" . $url . "?access_token=" . $this->getAccessToken() . ($params ? '&' . $params : '');
    }

    /**
     * get
     * @param $url
     * @return mixed
     */
    public function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        //只有在cURL低于7.28.1时CURLOPT_SSL_VERIFYHOST才支持使用1表示true，高于这个版本就需要使用2表示了（true也不行）
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转

        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * post
     * @param $url
     * @param $data
     * @return mixed
     */
    public function httpPost($url, $data, $file = '') {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($file) {
            $post_data['media'] = new CURLFile(realpath($file));
        } else {
            //设置post数据
            //$post_data = (is_object($data) || is_array($data)) ? http_build_query($data) : $data;
            $post_data = (is_object($data) || is_array($data)) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * http
     * @param $url
     * @param $data
     * @return mixed
     */
    public function http($url, $data = '', $bool = 0, $post = true,  $file = '') {
        if ($post) {
            $res = $this->httpPost($url, $data, $file);
        } else {
            $res = $this->httpGet($url);
        }
        $res = json_decode($res);
        if($res && $res->errcode){
            $this->setMsg($res->errcode, $res->errmsg);
            return false;
        } else if(!$res){
            return false;
        }
        if($bool === 1) {
            return true;
        } else if ($bool) {
            return $res->$bool;
        } else {
            return $res;
        }
    }

    /**
     * @param $filename
     * @return string
     */
    private function get_php_file($filename) {
        return trim(substr(file_get_contents($filename), 15));
    }

    /**
     * @param $filename
     * @param $content
     */
    private function set_php_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }

    /**
     * @param $filename
     * @param $content
     */
    public function setLog($text) {
        if($text) {
            file_put_contents('log.txt', $text.PHP_EOL, FILE_APPEND);
        }
    }

    /**
     * xml字符串转数组
     * @param $xml
     * @return array|object
     */
    public function xmlToObjOrArr($xml, $obj = true){
        $objectxml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);//将文件转换成 对象
        return json_decode(json_encode($objectxml), !$obj);
    }

    /**
     * 数组转xml字符串
     * @param $data
     * @return xml
     */
    public function arrToXml($data) {
        if(!is_array($data) || count($data) <= 0) {
            return '';
        }
        return $this->genXml($data);
    }

    public function genXml($data, $out = true) {
        $xml = $out?"<xml>":"";
        foreach ($data as $key=>$val) {
            if (is_numeric($val)) {
                $xml .= "<".$key.">".$val."</".$key.">";
            } else if (is_array($val)) {
                $xml .= "<".$key.">".$this->genXml($val, false)."</".$key.">";
            } else {
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= $out?"</xml>":"";
        return $xml;
    }

    /**自定义菜单***********************************************************/
    /**
     * 创建菜单
     * @param $data
     * @return bool
     */
    public function createMenu($data) {
        $data = (is_object($data) || is_array($data)) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;//不能包含 \uxxxx 格式的字符
        $url = $this->genUrl("cgi-bin/menu/create");
        return $this->http($url, $data, 1);
    }

    /**
     * 查询菜单
     * @return mixed
     */
    public function getMenu() {
        $url = $this->genUrl("cgi-bin/get_current_selfmenu_info");
        return $this->http($url, '', 0, false);
    }

    /**
     * 删除菜单
     * @return bool
     */
    public function delMenu() {
        $url = $this->genUrl("cgi-bin/menu/delete");
        return $this->http($url, '', 1, false);
    }

    /**被动回复消息***********************************************************/
    /**
     * 回复文本消息
     * @param $from
     * @param $to
     * @param $content 回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
     * @return xml
     */
    public function sendText($from, $to, $content) {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'text',
            'Content' => $content
        ];
        $xml = $this->arrToXml($data);
        return $xml;
    }

    /**
     * 将消息转发到客服
     * 如果公众号处于开发模式，普通微信用户向公众号发消息时，微信服务器会先将消息POST到开发者填写的url上，
     * 如果希望将消息转发到客服系统，则需要开发者在响应包中返回MsgType为transfer_customer_service的消息，
     * 微信服务器收到响应后会把当次发送的消息转发至客服系统。
     * 您也可以在返回transfer_customer_service消息时，在XML中附上TransInfo信息指定分配给某个客服帐号。
     * <MsgType><![CDATA[transfer_customer_service]]></MsgType>
     * <TransInfo>
     *      <KfAccount><![CDATA[test1@test]]></KfAccount>
     * </TransInfo>
     * @param $from
     * @param $to
     * @param $kf_account 如果指定的客服没有接入能力(不在线、没有开启自动接入或者自动接入已满)，该用户会被直接接入到指定客服，
     *                      不再通知其它客服，不会被其他客服接待。建议在指定客服时，先查询客服的接入能力
     * @return xml
     */
    public function sendKfText($from, $to, $kf_account = '') {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'transfer_customer_service'
        ];
        if ($kf_account) {
            $data['TransInfo'] = [
                'KfAccount' => $kf_account
            ];
        }
        $xml = $this->arrToXml($data);
        return $xml;
    }

    /**
     * 回复图片消息
     * @param $from
     * @param $to
     * @param $media_id 通过素材管理中的接口上传多媒体文件，得到的id
     * @return xml
     */
    public function sendImage($from, $to, $media_id) {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'image',
            'Image' => [
                'MediaId' => $media_id
            ]
        ];
        $xml = $this->arrToXml($data);
        return $xml;
    }

    /**
     * 回复语音消息
     * @param $from
     * @param $to
     * @param $media_id 通过素材管理中的接口上传多媒体文件，得到的id
     * @return xml
     */
    public function sendVoice($from, $to, $media_id) {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'voice',
            'Voice' => [
                'MediaId' => $media_id
            ]
        ];
        $xml = $this->arrToXml($data);
        return $xml;
    }

    /**
     * 回复视频消息
     * @param $from
     * @param $to
     * @param $media_id 通过素材管理中的接口上传多媒体文件，得到的id
     * @param string $title
     * @param string $description
     * @return xml
     */
    public function sendVideo($from, $to, $media_id, $title = '视频', $description = '视频') {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'video',
            'Video' => [
                'MediaId' => $media_id,
                'Title' => $title,
                'Description' => $description
            ]
        ];
        $xml = $this->arrToXml($data);
        return $xml;
    }

    /**
     * 回复音乐消息
     * @param $from
     * @param $to
     * @param $thumb_media_id 缩略图的媒体id，通过素材管理中的接口上传多媒体文件，得到的id
     * @param string $title
     * @param string $description
     * @param string $music_url 音乐链接
     * @param string $hq_music_url 高质量音乐链接，WIFI环境优先使用该链接播放音乐
     * @return xml
     */
    public function sendMusic($from, $to, $thumb_media_id, $title = '', $description = '', $music_url = '', $hq_music_url = '') {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'music',
            'Music' => [
                'Title' => $title,
                'Description' => $description,
                'MusicUrl' => $music_url,
                'HQMusicUrl' => $hq_music_url,
                'ThumbMediaId' => $thumb_media_id
            ]
        ];
        $xml = $this->arrToXml($data);
        return $xml;
    }

    /**
     * 回复图文消息
     * @param $from
     * @param $to
     * @param string $title
     * @param string $description
     * @param $pic_url 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
     * @param $url 点击图文消息跳转链接
     * @return xml
     */
    public function sendNews($from, $to, $title, $description, $pic_url, $url) {
        $data = [
            'ToUserName' => $to,
            'FromUserName' => $from,
            'CreateTime' => time(),
            'MsgType' => 'news',
            //图文消息个数；当用户发送文本、图片、视频、图文、地理位置这五种消息时，开发者只能回复1条图文消息；其余场景最多可回复8条图文消息
            'ArticleCount' => 1,
            'Articles' => [
                'item' => [
                    'Title' => $title,
                    'Description' => $description,
                    'PicUrl' => $pic_url,
                    'Url' => $url
                ]
            ]
        ];
        $xml = $this->arrToXml($data);
        return $xml;
    }


    /**多媒体素材管理***********************************************************/
    /**
     * 新增临时素材
     * 注意点：
     * 临时素材media_id是可复用的。
     * 媒体文件在微信后台保存时间为3天，即3天后media_id失效。
     * 上传临时素材的格式、大小限制与公众平台官网一致。
     * 图片（image）: 2M，支持PNG\JPEG\JPG\GIF格式
     * 语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
     * 视频（video）：10MB，支持MP4格式
     * 缩略图（thumb）：64KB，支持JPG格式
     * @param $file
     * @param $type 媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @return bool
     */
    public function upload($file, $type = 'image') {
        $url = $this->genUrl("cgi-bin/media/upload", "type=" . $type);
        return $this->http($url, '', 'media_id', true, $file);
    }

    /**
     * 获取临时素材
     * @param $media_id
     * @return bool|mixed
     */
    public function getUpload($media_id) {
        $url = $this->genUrl("cgi-bin/media/get", "media_id=" . $media_id);
        $re = $this->httpGet($url);
        $res = json_decode($re);
        if($res && $res->errcode){
            $this->setMsg($res->errcode, $res->errmsg);
            return false;
        }
        if($res){
            return $res;
        }
        return $re;
    }

    /**永久素材***************/
    /**
     * 新增永久图文素材
     * @param $data
     * @return bool|string
     */
    public function add_news($data) {
        $url = $this->genUrl("cgi-bin/material/add_news");
        return $this->http($url, $data, 'media_id');
    }

    /**
     * 修改永久图文素材
     * media_id	是	要修改的图文消息的id
     * index	是	要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0
     * @param $data
     * @return bool
     */
    public function updateNews($data) {
        $url = $this->genUrl("cgi-bin/material/update_news");
        return $this->http($url, $data, 1);
    }

    /**
     * 上传图文消息内的图片获取URL【订阅号与服务号认证后均可用】
     * 本接口所上传的图片不占用公众号的素材库中图片数量的100000个的限制。图片仅支持jpg/png格式，大小必须在1MB以下。
     * @param $file
     * @return bool|string
     */
    public function uploadImg($file) {
        $url = $this->genUrl("cgi-bin/media/uploadimg");
        return $this->http($url, '', 'url', true, $file);
    }

    /**
     * 新增其他类型永久素材
     * 久视频素材需特别注意
     * 在上传视频素材时需要POST另一个表单，id为description，包含素材的描述信息，内容格式为JSON，格式如下：
     * {
     * "title":VIDEO_TITLE,
     * "introduction":INTRODUCTION
     * }
     * @param $file
     * @param $type 媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @param $vodeo_title vodeo_title	视频素材的标题
     * @param $introduction introduction	视频素材的描述
     * @return bool|string
     */
    public function addMaterial($file, $type, $vodeo_title = '', $introduction = '') {
        $url = $this->genUrl("cgi-bin/material/add_material", "type=" . $type);
        if($type == 'video') {
            $data['media'] = new CURLFile(realpath($file));
            $data['description'] = json_encode(["title" => $vodeo_title, "introduction" => $introduction], JSON_UNESCAPED_UNICODE);
            return $this->http($url, $data, 'media_id');
        }else{
            return $this->http($url, '', 'media_id', true, $file);
        }
    }

    /**永久素材***************/

    /**
     * 获取永久素材
     * @param $media_id
     * @return bool|mixed
     */
    public function getMaterial($media_id) {
        $url = $this->genUrl("cgi-bin/material/get_material");
        $re = $this->httpPost($url, json_encode(['media_id' => $media_id]));
        $res = json_decode($re);
        if($res && $res->errcode){
            $this->setMsg($res->errcode, $res->errmsg);
            return false;
        }
        if($res){
            return $res;
        }
        return $re;
    }

    /**
     * 删除永久素材
     * 可以删除公众号在公众平台官网素材管理模块中新建的图文消息、语音、视频等素材
     * @param $media_id
     * @return bool
     */
    public function delMaterial($media_id) {
        $url = $this->genUrl("cgi-bin/material/del_material");
        return $this->http($url, json_encode(['media_id' => $media_id]), 1);
    }

    /**
     * 获取素材总数
     * {
     * "voice_count":COUNT,
     * "video_count":COUNT,
     * "image_count":COUNT,
     * "news_count":COUNT
     * }
     * @return bool|mixed
     */
    public function getMaterialCount() {
        $url = $this->genUrl("cgi-bin/material/get_materialcount");
        return $this->http($url, '', 0, false);
    }

    /**
     * 获取素材列表
     * $data
     * type	    是	素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
     * offset	是	从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
     * count	是	返回素材的数量，取值在1到20之间
     * @param $data
     * @return bool|mixed
     */
    public function batchGetMaterial($data) {
        $url = $this->genUrl("cgi-bin/material/batchget_material");
        return $this->http($url, $data, 0);
    }

    /**
     * 群发视频media_id获取
     * @param $media_id 素材media_id
     * @param $title
     * @param $description
     * @return string media_id 群发视频media_id
     */
    public function uploadVideo($media_id, $title, $description) {
        $data = [
            "media_id" => $media_id,
            "title" => $title,
            "description" => $description
        ];
        $url = $this->genUrl("cgi-bin/media/uploadvideo");
        return $this->http($url, $data, 'media_id');
    }

    /**群发接口和原创校验***********************************************************/
    /**
     * 上传图文消息素材
     * thumb_media_id 临时素材媒体ID
     * @param $data
     * @return mixed
     */
    public function uploadNews($data) {
        $url = $this->genUrl("cgi-bin/media/uploadnews");
        return $this->http($url, $data, 'media_id');
    }

    /**
     * 根据标签进行群发【订阅号与服务号认证后均可用】
     * 参数	          是否必须	说明
     * filter	            是	用于设定图文消息的接收者
     * is_to_all	        否	用于设定是否向全部用户发送，值为true或false，选择true该消息群发给所有用户，选择false可根据tag_id发送给指定群组的用户
     * tag_id	            否	群发到的标签的tag_id，参见用户管理中用户分组接口，若is_to_all值为true，可不填写tag_id
     * mpnews	            是	用于设定即将发送的图文消息
     * media_id	            是	用于群发的消息的media_id
     * msgtype	            是	群发的消息类型，图文消息为mpnews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video，卡券为wxcard
     * title	            否	消息的标题
     * description	        否	消息的描述
     * thumb_media_id	    是	视频缩略图的媒体ID
     * send_ignore_reprint	是	图文消息被判定为转载时，是否继续群发。 1为继续群发（转载），0为停止群发。 该参数默认为0。
     * @param $data
     * @param $touser 根据OpenID列表群发【订阅号不可用，服务号认证后可用】
     * @return json
     * msg_id	消息发送任务的ID
     * msg_data_id	消息的数据ID，该字段只有在群发图文消息时，才会出现。可以用于在图文分析数据接口中，
     * 获取到对应的图文消息的数据，是图文分析数据接口中的msgid字段中的前半部分，详见图文分析数据接口中的msgid字段的介绍。
     */
    public function massSendAll($data, $toUser) {
        $url = $this->genUrl("cgi-bin/message/mass/sendall");
        if (!empty($toUser)) { //根据OpenID列表群发【订阅号不可用，服务号认证后可用】
            $data['touser'] = $toUser;
            if(isset($data['filter'])) unset($data['filter']);
            $url = $this->genUrl("cgi-bin/message/mass/send");
        } else if($data['filter']['is_to_all'] && isset($data['filter']['tag_id'])) { //根据标签进行群发【订阅号与服务号认证后均可用】
            unset($data['filter']['tag_id']);
        }
        return $this->http($url, $data);
    }

    /**
     * 根据OpenID列表群发【订阅号不可用，服务号认证后可用】
     * @param $data
     * @return mixed
     */
    public function massSend($data) {
        $url = $this->genUrl("cgi-bin/message/mass/send");
        return $this->http($url, $data);
    }

    /**
     * 删除群发【订阅号与服务号认证后均可用】 删除图文消息和视频消息
     * 1、只有已经发送成功的消息才能删除
     * 2、删除消息是将消息的图文详情页失效，已经收到的用户，还是能在其本地看到消息卡片。
     * 3、删除群发消息只能删除图文消息和视频消息，其他类型的消息一经发送，无法删除。
     * 4、如果多次群发发送的是一个图文消息，那么删除其中一次群发，就会删除掉这个图文消息也，导致所有群发都失效
     * @param $msg_id 发送出去的消息ID
     * @param $article_idx 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     * @return boolean
     */
    public function massDelete($msg_id, $article_idx = 0) {
        $url = $this->genUrl("cgi-bin/message/mass/delete");
        return $this->http($url, ['msg_id' => $msg_id, 'article_idx' => $article_idx], 1);
    }

    /**
     * 预览接口【订阅号与服务号认证后均可用】
     * 开发者可通过该接口发送消息给指定用户，在手机端查看消息的样式和排版。为了满足第三方平台开发者的需求，
     * 在保留对openID预览能力的同时，增加了对指定微信号发送预览的能力，但该能力每日调用次数有限制（100次），请勿滥用。
     * @param $data
     * @return string
     */
    public function massPreview($data) {
        $url = $this->genUrl("cgi-bin/message/mass/preview");
        return $this->http($url, $data, 'msg_id');
    }

    /**
     * 查询群发消息发送状态【订阅号与服务号认证后均可用】
     * @param $msg_id 群发消息后返回的消息id
     * @return string 消息发送后的状态，SEND_SUCCESS表示发送成功，SENDING表示发送中，SEND_FAIL表示发送失败，DELETE表示已删除
     */
    public function massGet($msg_id) {
        $url = $this->genUrl("cgi-bin/message/mass/get");
        return $this->http($url, ['msg_id' => $msg_id], 'msg_status');
    }

    /**
     * 事件推送群发结果
     * <MsgType><![CDATA[event]]></MsgType>
     * <Event><![CDATA[MASSSENDJOBFINISH]]></Event>
     */

    /**
     * 控制群发速度
     * 获取群发速度
     * @return mixed
     * speed	 群发速度的级别
     * realspeed 群发速度的真实值 单位：万/分钟
     */
    public function massSpeedGet() {
        $url = $this->genUrl("cgi-bin/message/mass/speed/get");
        return $this->http($url);
    }

    /**
     * 设置群发速度
     * speed	realspeed
     * 0	80w/分钟
     * 1	60w/分钟
     * 2	45w/分钟
     * 3	30w/分钟
     * 4	10w/分钟
     * @param $speed 群发速度的级别0-4 群发速度的级别，是一个0到4的整数，数字越大表示群发速度越慢。
     * @return mixed
     */
    public function massSpeedSet($speed) {
        $url = $this->genUrl("cgi-bin/message/mass/speed/set");
        return $this->http($url, ['speed' => $speed]);
    }

    /**
     * 群发图文消息（注意图文消息的media_id需要通过上述方法来得到）
     * 上传图文消息素材 uploadnews
     * @param $media_id
     * @param bool $is_to_all
     * @param int $tag_id
     * @param int $ignore
     * @param array $touser
     * @return json
     */
    public function massSendMpNews($media_id, $is_to_all = true, $tag_id = 0, $ignore = 0, $touser = []) {
        $data = [
            "filter" => [
                "is_to_all" => $is_to_all,
                "tag_id" => $tag_id
            ],
            "mpnews" => [
                "media_id" => $media_id
            ],
            "msgtype" => "mpnews",
            "send_ignore_reprint" => $ignore,
            //"clientmsgid" => "send_tag_2" //使用 clientmsgid 参数，避免重复推送
        ];
        return $this->massSendAll($data, $touser);
    }

    /**
     * 群发文本
     * @param $content
     * @param bool $is_to_all
     * @param int $tag_id
     * @param array $touser
     * @return json
     */
    public function massSendText($content, $is_to_all = true, $tag_id = 0, $touser = []) {
        $data = [
            "filter" => [
                "is_to_all" => $is_to_all,
                "tag_id" => $tag_id
            ],
            "text" => [
                "content" => $content
            ],
            "msgtype" => "text"
        ];
        return $this->massSendAll($data, $touser);
    }

    /**
     * 群发语音/音频（注意此处media_id需通过素材管理->新增素材来得到）
     * @param $media_id
     * @param bool $is_to_all
     * @param int $tag_id
     * @param array $touser
     * @return json
     */
    public function massSendVoice($media_id, $is_to_all = true, $tag_id = 0, $touser = []) {
        $data = [
            "filter" => [
                "is_to_all" => $is_to_all,
                "tag_id" => $tag_id
            ],
            "voice" => [
                "media_id" => $media_id
            ],
            "msgtype" => "voice"
        ];
        return $this->massSendAll($data, $touser);
    }

    /**
     * 群发图片（注意此处media_id需通过素材管理->新增素材来得到）
     * @param $media_id
     * @param bool $is_to_all
     * @param int $tag_id
     * @param array $touser
     * @return json
     */
    public function massSendImage($media_id, $is_to_all = true, $tag_id = 0, $touser = []) {
        $data = [
            "filter" => [
                "is_to_all" => $is_to_all,
                "tag_id" => $tag_id
            ],
            "image" => [
                "media_id" => $media_id
            ],
            "msgtype" => "image"
        ];
        return $this->massSendAll($data, $touser);
    }

    /**
     * 群发视频
     * 此处视频的media_id需通过POST请求到下述接口特别地得到：https://api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token=ACCESS_TOKEN
     * 素材media_id -> 群发视频media_id
     * @param $media_id
     * @param bool $is_to_all
     * @param int $tag_id
     * @param array $touser
     * @return json
     */
    public function massSendMpVideo($media_id, $is_to_all = true, $tag_id = 0, $touser = []) {
        $data = [
            "filter" => [
                "is_to_all" => $is_to_all,
                "tag_id" => $tag_id
            ],
            "mpvideo" => [
                "media_id" => $media_id
            ],
            "msgtype" => "mpvideo"
        ];
        return $this->massSendAll($data, $touser);
    }

    /**
     * 卡券消息
     * @param $card_id
     * @param bool $is_to_all
     * @param int $tag_id
     * @param array $touser
     * @return json
     */
    public function massSendWxCard($card_id, $is_to_all = true, $tag_id = 0, $touser = []) {
        $data = [
            "filter" => [
                "is_to_all" => $is_to_all,
                "tag_id" => $tag_id
            ],
            "wxcard" => [
                "card_id" => $card_id
            ],
            "msgtype" => "wxcard"
        ];
        return $this->massSendAll($data, $touser);
    }

    /**模板消息接口***********************************************************/
    /**
     * 设置所属行业
     * 每月可修改行业1次，帐号仅可使用所属行业中相关的模板
     * @param $industry_id1 公众号模板消息所属行业编号
     * @param $industry_id2 二级行业编号
     * @return boolean
     */
    public function templateSetIndustry($industry_id1, $industry_id2) {
        $url = $this->genUrl("cgi-bin/template/api_set_industry");
        return $this->http($url, ['industry_id1' => $industry_id1, 'industry_id2' => $industry_id2], 1);
    }

    /**
     * 获取设置的行业信息
     * {
     * "primary_industry":{"first_class":"运输与仓储","second_class":"快递"},
     * "secondary_industry":{"first_class":"IT科技","second_class":"互联网|电子商务"}
     * }
     * @return object
     */
    public function templateGetIndustry() {
        $url = $this->genUrl("cgi-bin/template/get_industry");
        return $this->http($url, '', 0, false);
    }

    /**
     * 获得模板ID
     * @param $template_id_short  模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     * @return string template_id
     */
    public function templateAddTemplate($template_id_short) {
        $url = $this->genUrl("cgi-bin/template/api_add_template");
        return $this->http($url, ['template_id_short' => $template_id_short], 'template_id');
    }

    /**
     * 获取模板列表
     * @return object
     */
    public function templateGetAllPT() {
        $url = $this->genUrl("cgi-bin/template/get_all_private_template");
        return $this->http($url, '', 0, false);
    }

    /**
     * 删除模板
     * @param $template_id 模板ID
     * @return boolean
     */
    public function templateDelPT($template_id) {
        $url = $this->genUrl("cgi-bin/template/del_private_template");
        return $this->http($url, ['template_id' => $template_id], 1);
    }

    /**
     * 发送模板消息
     * @param $data
     * @return string msgid 消息id
     */
    public function templateSend($data) {
        $url = $this->genUrl("cgi-bin/message/template/send");
        return $this->http($url, $data, 'msgid');
    }

    /**
     * 事件推送
     * 在模版消息发送任务完成后，微信服务器会将是否送达成功作为通知，发送到开发者中心中填写的服务器配置地址中。
     * <MsgType><![CDATA[event]]></MsgType>
     * <Event><![CDATA[TEMPLATESENDJOBFINISH]]></Event>
     * <MsgID>200163836</MsgID>
     */

    /**客服消息***********************************************************/
    /**
     * 多客服全面升级为新版客服功能
     * 多客服软件将停止服务，多客服将不再支持登录、收发用户消息等功能
     */
    /**
     * 添加客服帐号
     * 每个公众号最多添加10个客服账号
     * @param $kf_account "test1@test" 账号前缀@公众号微信号
     * @param $nickname 最长6个汉字或12个英文字符
     * @param $password 格式为密码明文的32位加密MD5值
     * @return boolean
     */
//    public function kfAdd($kf_account, $nickname, $password) {
//        $url = $this->genUrl("customservice/kfaccount/add");
//        $password = md5($password);
//        return $this->http($url, compact('kf_account', 'nickname', 'password'), 1);
//    }

    /**
     * 修改客服帐号
     * @param $kf_account "test1@test"
     * @param $nickname
     * @param $password
     * @return boolean
     */
//    public function kfUpdate($kf_account, $nickname, $password) {
//        $url = $this->genUrl("customservice/kfaccount/update");
//        $password = md5($password);
//        return $this->http($url, compact('kf_account', 'nickname', 'password'), 1);
//    }

    /**
     * 删除客服帐号
     * @param $kf_account "test1@test"
     * @param $nickname
     * @param $password
     * @return boolean
     */
//    public function kfDel($kf_account, $nickname, $password) {
//        $url = $this->genUrl("customservice/kfaccount/del");
//        $password = md5($password);
//        return $this->http($url, compact('kf_account', 'nickname', 'password'), 1);
//    }

    /**
     * 设置客服帐号的头像
     * @param $kf_account
     * @param $file
     * @return boolean
     */
//    public function kfUpLoadHeadImg($kf_account, $file) {
//        $url = $this->genUrl("customservice/kfaccount/uploadheadimg", compact("kf_account"));
//        return $this->http($url, '', 1, true, $file);
//    }

    /**
     * 获取所有客服账号
     * @return object
     */
//    public function kfGetKfList() {
//        $url = $this->genUrl("cgi-bin/customservice/getkflist");
//        return $this->http($url, '', 0, false);
//    }

    /**
     * 客服接口-发消息
     * @param $data
     * @return object
     */
//    public function kfSend($data) {
//        $url = $this->genUrl("cgi-bin/message/custom/send");
//        return $this->http($url, $data);
//    }

    /**
     * 客服输入状态
     * @param $touser 普通用户（openid）
     * @param $command "Typing"：对用户下发“正在输入"状态 "CancelTyping"：取消对用户的”正在输入"状态
     * @return boolean
     */
//    public function kfTyping($touser, $command) {
//        $url = $this->genUrl("cgi-bin/message/custom/typing");
//        return $this->http($url, compact('touser', 'command'), 1);
//    }

    /**新版客服功能***********************************************************/
    /**
     * 添加客服帐号
     * 65400	API不可用，即没有开通或升级到新版客服功能
     * @param $kf_account "test1@test" 账号前缀@公众号微信号 完整客服帐号，格式为：帐号前缀@公众号微信号，帐号前缀最多10个字符，必须是英文、数字字符或者下划线，后缀为公众号微信号，长度不超过30个字符
     * @param $nickname 客服昵称，最长16个字
     * @return boolean
     */
    public function kfAdd($kf_account, $nickname) {
        $url = $this->genUrl("customservice/kfaccount/add");
        return $this->http($url, compact('kf_account', 'nickname'), 1);
    }

    /**
     * 邀请绑定客服帐号
     * 新添加的客服帐号是不能直接使用的，只有客服人员用微信号绑定了客服账号后，方可登录Web客服进行操作。
     * 此接口发起一个绑定邀请到客服人员微信号，客服人员需要在微信客户端上用该微信号确认后帐号才可用。
     * 尚未绑定微信号的帐号可以进行绑定邀请操作，邀请未失效时不能对该帐号进行再次绑定微信号邀请。
     * @param $kf_account 完整客服帐号，格式为：帐号前缀@公众号微信号
     * @param $invite_wxe 接收绑定邀请的客服微信号
     * @return boolean
     */
    public function kfInviteWorker($kf_account, $invite_wxe) {
        $url = $this->genUrl("customservice/kfaccount/inviteworker");
        return $this->http($url, compact('kf_account', 'invite_wx'), 1);
    }

    /**
     * 设置客服信息
     * @param $kf_account "test1@test" 账号前缀@公众号微信号
     * @param $nickname 客服昵称，最长16个字
     * @return boolean
     */
    public function kfUpdate($kf_account, $nickname) {
        $url = $this->genUrl("customservice/kfaccount/update");
        return $this->http($url, compact('kf_account', 'nickname'), 1);
    }

    /**
     * 删除客服帐号
     * @param $kf_account "test1@test"
     * @return boolean
     */
    public function kfDel($kf_account) {
        $url = $this->genUrl("customservice/kfaccount/del");
        return $this->http($url, compact('kf_account'), 1);
    }

    /**
     * 设置客服帐号的头像
     * @param $kf_account
     * @param $file
     * @return boolean
     */
    public function kfUpLoadHeadImg($kf_account, $file) {
        $url = $this->genUrl("customservice/kfaccount/uploadheadimg", compact("kf_account"));
        return $this->http($url, '', 1, true, $file);
    }

    /**
     * 获取所有客服账号
     * @return object
     */
    public function kfGetKfList() {
        $url = $this->genUrl("cgi-bin/customservice/getkflist");
        return $this->http($url, '', 0, false);
    }

    /**
     * 获取在线客服
     * @return object
     */
    public function kfGetOnlineKfList() {
        $url = $this->genUrl("cgi-bin/customservice/getonlinekflist");
        return $this->http($url, '', 0, false);
    }

    /**
     * 创建会话
     * 此接口在客服和用户之间创建一个会话，如果该客服和用户会话已存在，则直接返回0。指定的客服帐号必须已经绑定微信号且在线。
     * @param $kf_account
     * @param $openid 粉丝的openid
     * @return boolean
     */
    public function kfSessionCreate($kf_account, $openid) {
        $url = $this->genUrl("customservice/kfsession/create");
        return $this->http($url, compact('kf_account', 'openid'));
    }

    /**
     * 关闭会话
     * @param $kf_account
     * @param $openid 粉丝的openid
     * @return boolean
     */
    public function kfSessionClose($kf_account, $openid) {
        $url = $this->genUrl("customservice/kfsession/close");
        return $this->http($url, compact('kf_account', 'openid'));
    }

    /**
     * 获取客户会话状态
     * 此接口获取一个客户的会话，如果不存在，则kf_account为空。
     * @param $openid 粉丝的openid
     * @return object  {      "createtime": 123456789,      "kf_account": "test1@test"   }
     */
    public function kfSessionGet($openid) {
        $url = $this->genUrl("/customservice/kfsession/getsession", "openid=$openid");
        return $this->http($url);
    }

    /**
     * 获取客服会话列表
     * 此接口获取一个客户的会话，如果不存在，则kf_account为空。
     * @param $kf_account
     * @return object { "sessionlist"   : [ { "createtime"   : 123456789, "openid"   :  "OPENID" } ] }
     */
    public function kfSessionGetList($kf_account) {
        $url = $this->genUrl("customservice/kfsession/getsessionlist", "kf_account=$kf_account");
        return $this->http($url);
    }

    /**
     * 获取未接入会话列表
     * 未接入会话列表，最多返回100条数据，按照来访顺序
     * @return object
     */
    public function kfSessionGetWaitCase() {
        $url = $this->genUrl("customservice/kfsession/getwaitcase");
        return $this->http($url);
    }

    /**
     * 获取聊天记录
     * 此接口返回的聊天记录中，对于图片、语音、视频，分别展示成文本格式的[image]、[voice]、[video]。
     * 对于较可能包含重要信息的图片消息，后续将提供图片拉取URL，近期将上线。
     * @param $starttime 起始时间，unix时间戳
     * @param $endtime 结束时间，unix时间戳，每次查询时段不能超过24小时
     * @param $msgid 消息id顺序从小到大，从1开始
     * @param $number 每次获取条数，最多10000条
     * @return object
     */
    public function kfSessionGetMsgList($starttime, $endtime, $msgid = 1, $number = 20) {
        $url = $this->genUrl("customservice/msgrecord/getmsglist");
        return $this->http($url, compact('starttime', 'endtime', 'msgid', 'number'));
    }

    /**
     * 将消息转发到客服
     * 如果公众号处于开发模式，普通微信用户向公众号发消息时，微信服务器会先将消息POST到开发者填写的url上，
     * 如果希望将消息转发到客服系统，则需要开发者在响应包中返回MsgType为transfer_customer_service的消息，
     * 微信服务器收到响应后会把当次发送的消息转发至客服系统。
     * 您也可以在返回transfer_customer_service消息时，在XML中附上TransInfo信息指定分配给某个客服帐号。
     * <MsgType><![CDATA[transfer_customer_service]]></MsgType>
     * <TransInfo>
     *      <KfAccount><![CDATA[test1@test]]></KfAccount>
     * </TransInfo>
     */

    /**用户管理***********************************************************/
    /**
     * 创建标签
     * 一个公众号，最多可以创建100个标签。
     * @param $name 标签名（30个字符以内）
     * @return string 标签id，由微信分配
     */
    public function tagsCreate($name) {
        $url = $this->genUrl("cgi-bin/tags/create");
        return $this->http($url, ['tag' => ['name' => $name]])->tag->id;
    }

    /**
     * 获取公众号已创建的标签
     * @return object
     */
    public function tagsGet() {
        $url = $this->genUrl("cgi-bin/tags/get");
        return $this->http($url, '', 0, false);
    }
    
    /**
     * 编辑标签
     * @param $id 标签id
     * @param $name 标签名
     * @return boolean
     */
    public function tagsUpdate($id, $name) {
        $url = $this->genUrl("cgi-bin/tags/update");
        return $this->http($url, ['tag' => ['id' => $id, 'name' => $name]], 1);
    }

    /**
     * 删除标签
     * 当某个标签下的粉丝超过10w时，后台不可直接删除标签此时，开发者可以对该标签下的openid列表，
     * 先进行取消标签的操作，直到粉丝数不超过10w后，才可直接删除该标签。
     * @param $id 标签id
     * @return boolean
     */
    public function tagsDelete($id) {
        $url = $this->genUrl("cgi-bin/tags/delete");
        return $this->http($url, ['tag' => ['id' => $id]], 1);
    }

    /**
     * 获取标签下粉丝列表
     * @param $tagid 标签id
     * @param $next_openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @return object {"count":0}
     * {"count":1,"data":{"openid":["oul9P1fQqxD8sW4zyVAcm9W9hyo0"]},"next_openid":"oul9P1fQqxD8sW4zyVAcm9W9hyo0"}
     */
    public function tagsUserGet($tagid, $next_openid = '') {
        $url = $this->genUrl("cgi-bin/user/tag/get");
        return $this->http($url, compact("tagid", "next_openid"));
    }

    /**
     * 批量为用户打标签
     * @param $openid_list [] 粉丝列表
     * @param $tagid 标签id
     * @return boolean
     */
    public function tagsBatchTagging($openid_list, $tagid) {
        $url = $this->genUrl("cgi-bin/tags/members/batchtagging");
        return $this->http($url, ['openid_list' => $openid_list, 'tagid' => $tagid], 1);
    }

    /**
     * 批量为用户取消标签
     * @param $openid_list 粉丝列表
     * @param $tagid 标签id
     * @return object
     */
    public function tagsBatchUnTagging($openid_list, $tagid) {
        $url = $this->genUrl("cgi-bin/tags/members/batchuntagging");
        return $this->http($url, ['openid_list' => $openid_list, 'tagid' => $tagid], 1);
    }

    /**
     * 获取用户身上的标签列表
     * @param $openid openid
     * @return object {   "tagid_list":[//被置上的标签列表 134, 2   ] }
     */
    public function tagsGetIdList($openid) {
        $url = $this->genUrl("cgi-bin/tags/getidlist");
        return $this->http($url, compact("openid"));
    }

    /**
     * 设置用户备注名 开发者可以通过该接口对指定用户设置备注名，该接口暂时开放给微信认证的服务号。
     * @param $openid openid
     * @param $remark 备注名
     * @return boolean
     */
    public function tagsUpdateRemark($openid, $remark) {
        $url = $this->genUrl("cgi-bin/user/info/updateremark");
        return $this->http($url, compact("openid", "remark"), 1);
    }

    /**
     * 获取用户基本信息(UnionID机制)
     * @param $openid openid
     * @return object
     */
    public function getUserInfo($openid) {
        $url = $this->genUrl("cgi-bin/user/info", "openid=$openid&lang=zh_CN");
        return $this->http($url, '', 0, false);
    }

    /**
     * 批量获取用户基本信息
     * @param $user_list [["openid","lang"],["openid","lang"]]
     * @return object
     */
    public function getUserInfoBatch($user_list) {
        $url = $this->genUrl("cgi-bin/user/info/batchget");
        return $this->http($url, ['user_list' => $user_list], 0);
    }

    /**
     * 获取用户列表
     * 一次拉取调用最多拉取10000个关注者的OpenID
     * @param $next_openid
     * @return object { "total":2, "count":2, "data":{ "openid":["OPENID1","OPENID2"]}, "next_openid":"NEXT_OPENID" }
     */
    public function getUserGet($next_openid = '') {
        $url = $this->genUrl("cgi-bin/user/get", compact("next_openid"));
        return $this->http($url, '', 0, false);
    }

    /**
     * 获取公众号的黑名单列表
     * 每次调用最多可拉取 10000 个OpenID
     * @param $begin_openid
     * @return object {"total":0,"count":0}
     * {"total":1,"count":1,"data":{"openid":["oul9P1fQqxD8sW4zyVAcm9W9hyo0"]},"next_openid":"oul9P1fQqxD8sW4zyVAcm9W9hyo0"}
     */
    public function getBlackList($begin_openid = '') {
        $url = $this->genUrl("cgi-bin/tags/members/getblacklist");
        return $this->http($url, compact("begin_openid"));
    }

    /**
     * 拉黑用户
     * 每次调用最多可拉取 10000 个OpenID
     * @param $openid_list ["OPENID1”,” OPENID2”]
     * @return boolean
     */
    public function batchBlackList($openid_list) {
        $url = $this->genUrl("cgi-bin/tags/members/batchblacklist");
        return $this->http($url, ["openid_list" => $openid_list], 1);
    }

    /**
     * 取消拉黑用户
     * 每次调用最多可拉取 10000 个OpenID
     * @param $openid_list ["OPENID1”,” OPENID2”]
     * @return boolean
     */
    public function batchUnBlackList($openid_list) {
        $url = $this->genUrl("cgi-bin/tags/members/batchunblacklist");
        return $this->http($url, ["openid_list" => $openid_list], 1);
    }

    /**帐号管理***********************************************************/
    /**
     * 生成带参数的二维码
     * 临时二维码，是有过期时间的，最长可以设置为在二维码生成后的30天（即2592000秒）后过期，但能够生成较多数量。
     * 永久二维码，是无过期时间的，但数量较少（目前为最多10万个）。永久二维码主要用于适用于帐号绑定、用户来源统计等场景。
     * 获取带参数的二维码的过程包括两步，首先创建二维码ticket，然后凭借ticket到指定URL换取二维码。
     * @param $action_name 临时二维码 QR_SCENE | QR_STR_SCENE  永久二维码QR_LIMIT_SCENE | QR_LIMIT_STR_SCENE
     * @param $scene
     * @param $expire_seconds 最大不超过2592000（即30天），此字段如果不填，则默认有效期为30秒。
     * @return object {"ticket":"Emm==","expire_seconds":60,"url":"http://in.qq.com/q/kZ"}
     */
    public function qrcodeCreate($action_name, $scene, $expire_seconds = 30) {
        $url = $this->genUrl("cgi-bin/qrcode/create");
        $data = [
            'action_name' => $action_name,
            'action_info' => [
                'scene' => []
            ]
        ];
        if ($action_name == 'QR_SCENE' || $action_name == 'QR_LIMIT_SCENE') {
            //场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
            $data['action_info']['scene']['scene_id'] = $scene;
        } else {
            //场景值ID（字符串形式的ID），字符串类型，长度限制为1到64
            $data['action_info']['scene']['scene_str'] = $scene;
        }
        if ($action_name == 'QR_SCENE' || $action_name == 'QR_STR_SCENE') {
            $data['action_info']['expire_seconds'] = $expire_seconds;
        }
        return $this->http($url, $data);
    }

    /**
     * 获取二维码ticket后，开发者可用ticket换取二维码图片。
     * 正确情况下，http 返回码是200，是一张图片，可以直接展示或者下载。
     * 错误情况下（如ticket非法）返回HTTP错误码404。
     * @param $ticket
     * @return resource
     */
    public function showQrcode($ticket) {
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket);
        return $this->httpGet($url);
    }

    /**
     * 长链接转短链接接口
     * 生成二维码的原链接（商品、支付二维码等）太长导致扫码速度和成功率下降，
     * 将原长链接通过此接口转成短链接再生成二维码将大大提升扫码速度和成功率。
     * @param $long_url 需要转换的长链接，支持http://、https://、weixin://wxpay 格式的url
     * @return string short_url
     */
    public function shortUrl($long_url) {
        $url = $this->genUrl("cgi-bin/shorturl");
        return $this->http($url, ["action" => 'long2short', "long_url" => $long_url], 'short_url');
    }
}

