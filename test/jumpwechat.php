<?php
if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
    $we = 1;
} else {
    $we = 2;
}

header("Content-Type:application/download");
header("Content-Disposition:attachment;filename=xiaolu_3.0.1.apk");


var_dump($we);
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <a href="http://neeson.ixiaolu.com/3.0.1/xiaolu_3.0.1_guanwang/xiaolu_3.0.1.apk" id="JdownApp">点击下载APP</a>
        <div class="wxtip" id="JweixinTip">
            <span class="wxtip-icon"></span>
            <p class="wxtip-txt">点击右上角<br/>选择在浏览器中打开<?php echo $we;?></p>
        </div>
        
        
        
        <script type="text/javascript">

            function weixinTip(ele){
                var ua = navigator.userAgent;
                var isWeixin = !!/MicroMessenger/i.test(ua);
                if(isWeixin){
                    ele.onclick=function(e){
                        
                    }
                    
                }
            }
            var btn1 = document.getElementById('JdownApp');//下载一
            //weixinTip(btn1);

        </script>
    </body>
</html>
