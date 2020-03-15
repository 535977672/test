<?php
declare (strict_types = 1);

namespace app\admin\controller;
use think\facade\View;
use osssdk\Test as tests;

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
}
