<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use osssdk\Test as tests;



use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Test
{

    public function index()
    {
        $oss = new tests;

    }



    public function pic()
    {
        return View::fetch('pic');
    }


    public function image()
    {
        var_dump($file = request()->file('image'));

        // 文件名
        $fileName = $_FILES['image']['name'];
        // 临时文件位置
        $tmpFile = $_FILES['image']['tmp_name'];

        $oss = new tests;
        $dir = 'dsfd/fsd/3023/231312121ww2324.jpg';
        $oss->uploads($dir, $tmpFile);

    }




    public function images()
    {
        $files = request()->file('image');
        $oss = new tests;

        $i = 0;
        foreach($files as $k => $file){

            // 临时文件位置
            $tmpFile = $file->getPathname();

            //var_dump($tmpFile);

            $dir = 'dsfd/fsd/3023/231312121ww2324'.$i.'.jpg';
            $i++;
            $oss->uploads($dir, $tmpFile);
        }

    }






    public function del()
    {

        $oss = new tests;
        $re = $oss->uploaddel('dsfd/fsd/3023/231312121ww2324.jpg');
        var_dump($re);
    }




    public function sms()
    {

        $sign = '优甜缘';
        $phoneNumbers = '18325048987';
        $templateCode = 'SMS_185847032';
        $regionId = 'cn-hangzhou';

        $code = '1234';

        AlibabaCloud::accessKeyClient(env('oss.accesskeyid'), env('oss.accesskeysecret'))
            ->regionId($regionId)
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => $regionId,
                        'PhoneNumbers' => $phoneNumbers,
                        'SignName' => $sign,
                        'TemplateCode' => $templateCode,
                        'TemplateParam' => '{"code":"'. $code .'"}'
                    ],
                ])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
//            {
//                "Message": "模板变量缺少对应参数值",
//                "RequestId": "BAED6837-8E4A-4056-9E98-F12639DC5773",
//                "Code": "isv.TEMPLATE_MISSING_PARAMETERS"
//            }
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}
