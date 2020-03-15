<?php
namespace osssdk;

use OSS\OssClient;
use OSS\Core\OssException;

class Test{
    private $accessKeyID = '';
    private $accessKeySecret = '';
    private $bucket = '';
    private $endpoint = '';

    function __construct()
    {
        $this->accessKeyID = env('oss.accesskeyid');
        $this->accessKeySecret = env('oss.accesskeysecret');
        $this->bucket = env('oss.bucket');
        $this->endpoint = env('oss.endpoint');
    }

    public function createOssClient(){
        try {
            $ossClient = new OssClient($this->accessKeyID, $this->accessKeySecret, $this->endpoint);
        } catch (OssException $e) {
            echo 'err0<br/>';
            print $e->getMessage();
        }
        return $ossClient;
    }

    public function uploads($object, $content)
    {
        try {
            $ossClient = $this->createOssClient();
            $info = $ossClient->uploadFile($this->bucket, $object, $content);

            //var_dump($info);

            $ossUrl = $info['oss-request-url'];
            // 如果图片的协议是http，则转换成https
            if (substr($ossUrl, 0, 4) == 'http') {
                $ossUrl = substr_replace($ossUrl, 'https', 0, 4);
            }
            $data = [
                'file_url' => $ossUrl,
                'file_name' => basename($ossUrl)
            ];
            var_dump($data);
            return $data;
        } catch (OssException $e) {
            echo 'err2123<br/>';
            print $e->getMessage();
        }
    }

    public function uploaddel($object){
        try {
            $ossClient = $this->createOssClient();
            $re = $ossClient->deleteObject($this->bucket, $object);
            var_dump($re);
        } catch (OssException $e) {
            echo 'err3<br/>';
            print $e->getMessage();
        }
    }

    public function tt(){

        $this->createOssClient();



    }


}