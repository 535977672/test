<?php
/**
 * 事件推送逻辑处理
 */

require_once "jssdk.php";
class Push {

    private $data = '';
    private $key = '';
    private $code = '';
    private $errMsg = [];
    private $jssdk = '';

    public function __construct($data) {
        $this->data = $data;
        if (isset($this->data->EventKey)) {
            $this->key = $this->data->EventKey;
            $pos = strripos($this->data->EventKey,'_');
            if ($pos) {
                $this->code = substr($this->data->EventKey, strripos($this->data->EventKey,'_')+1);
            }
        }
        $this->jssdk = new JSSDK();
    }

    private function setMsg($code, $msg) {
        $this->errMsg[] = ['code' => $code, 'msg' => $msg];
    }

    public function getMsg() {
        return $this->errMsg;
    }

    //事件推送
    //点击菜单拉取消息时的事件推送
    public function pushClick() {
        $str = '';
        switch ($this->code) {
            case '1001':
                $str = $this->sendText();
                bresk;
            default:
                bresk;
        }
        return $str;
    }

    //点击菜单跳转链接时的事件推送
    public function pushView() {
        return '';
    }

    //扫码推事件的事件推送
    public function pushScancodePush() {
        return '';
    }

    //扫码推事件且弹出“消息接收中”提示框的事件推送
    public function pushScancodeWaitmsg() {
        return '';
    }

    //弹出系统拍照发图的事件推送
    public function pushPicSysphoto() {
        return '';
    }

    //弹出拍照或者相册发图的事件推送
    public function pushPicPhotoOrAlbum() {
        return '';
    }

    //弹出微信相册发图器的事件推送
    public function pushPicWeixin() {
        return '';
    }

    //弹出地理位置选择器的事件推送
    public function pushLocationSelect() {
        return '';
    }

    //点击菜单跳转小程序
    public function pushViewMiniprogram() {
        return '';
    }

    //关注/取消关注事件
    //扫描带参数二维码事件 未关注
    //二维码事件有EventKey qrscene_1001
    public function pushSubscribe() {
        return $this->sendText('关注');
    }

    //关注/取消关注事件
    public function pushUnsubscribe() {
        return $this->sendText('取消关注');
    }

    //扫描带参数二维码事件 已关注
    public function pushScan() {
        return $this->sendText('扫描带参数二维码事件');
    }

    //上报地理位置事件
    public function pushLocation() {
        return $this->sendText('上报地理位置事件');
    }

    //事件推送群发结果
    public function pushMasssendjobfinish() {
        $this->jssdk->setLog('事件推送群发结果' . PHP_EOL . json_encode($this->data));
    }

    //模版消息事件推送
    public function pushTemplatesendjobfinish() {
        //$status = $this->data->Status; //success | user block  | system failed
        $this->jssdk->setLog('模版消息事件推送' . PHP_EOL . json_encode($this->data));
    }



    //普通消息
    //文本消息
    public function normalText() {
        return $this->sendText('文本消息: ' . $this->data->Content);
    }

    //图片消息
    public function normalImage() {
        return $this->sendImage($this->data->MediaId);
    }

    //语音消息
    public function normalVoice() {
        return $this->sendVoice($this->data->MediaId);
    }

    //视频消息
    public function normalVideo() {
        return $this->sendText('视频消息: ' . $this->data->ThumbMediaId);
    }

    //小视频消息
    public function normalShortvideo() {
        return $this->sendText('视频消息: ' . $this->data->ThumbMediaId);
    }

    //地理位置消息
    public function normalLocation() {
        return $this->sendText('地理位置: ' . $this->data->Label  . "\n维度: " . $this->data->Location_X . "\n经度: " . $this->data->Location_Y);
    }

    //链接消息
    public function normalLink() {
        return $this->sendText('链接消息: ' . $this->data->Title);
    }


    //回复实例
    //回复文本消息
    public function sendText($content = '') {
        $content = $content?$content:'我又回来了-' . time();
        return $this->jssdk->sendText($this->data->ToUserName, $this->data->FromUserName, $content);
    }

    //回复图片消息
    public function sendImage($media_id) {
        return $this->jssdk->sendImage($this->data->ToUserName, $this->data->FromUserName, $media_id);
    }

    //回复语音消息
    public function sendVoice($media_id) {
        return $this->jssdk->sendVoice($this->data->ToUserName, $this->data->FromUserName, $media_id);
    }

    //回复视频消息
    public function sendVideo($media_id) {
        return $this->jssdk->sendVideo($this->data->ToUserName, $this->data->FromUserName, $media_id);
    }

    //回复音乐消息
    public function sendMusic($thumb_media_id) {
        return $this->jssdk->sendMusic($this->data->ToUserName, $this->data->FromUserName, $thumb_media_id);
    }

    //回复图文消息
    public function sendNews() {
        $title = '';
        $description = '';
        $pic_url = '';
        $url = '';
        return $this->jssdk->sendNews($this->data->ToUserName, $this->data->FromUserName, $title, $description, $pic_url, $url);
    }

}