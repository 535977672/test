<?php
$u = isset($_GET['u'])?$_GET['u']:'';
if(!$u) {header("Location:/");exit;}
$file = glob("../img/travel/" . $u . '/img_300_*');
$file = json_encode($file);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <title>优甜缘</title>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <style>
            body
                {
                    margin:0;
                    background:#fff;
                }
            a{text-decoration:none;}
            .nav{display:flex;margin: 10px;font-size: larger}
            .nav div{flex:1; text-align: center;}
            .nav .btn{width: 100%; height: 40px; line-height: 40px; padding: 5px 10px; border-radius: 5px; background: linear-gradient(to bottom right, #F44336,#9C27B0,#2196F3,#009688,#8BC34A,#FFEB3B,#FF5722);}
            #wrap
                {
                    width:100%;
                    height:300px;
                    overflow: hidden;
                    position: relative;
                    margin:100px auto;
                    -webkit-perspective:3000px;
                    -moz-perspective:3000px;
                    -ms-transform:perspective(3000px);
                    -ms-perspective:3000px;
                }
            #head
                {
                    width:100%;
                    height:100%;
                    position: absolute;
                    -webkit-transform-style: preserve-3d;
                    -webkit-animation:donghua 35s linear 0s infinite;
                    -moz-transform-style: preserve-3d;
                    -moz-animation:donghua 35s linear 0s infinite;
                    -ms-transform-style: preserve-3d;
                    -ms-animation:donghua 35s linear 0s infinite;
                }
            #head div
                {	
                    position: absolute;
                    top:0;
                    left:0;
                    width:200px;
                    height:300px;
                    text-align: center;
                    line-height:100px;
                }
            #head div:nth-child(1)
                {
                    -webkit-transform:rotateY(0deg) translateZ(400px);
                    -moz-transform:rotateY(0deg) translateZ(400px);
                    -ms-transform:rotateY(0deg) translateZ(400px);
                    
                }
            #head div:nth-child(2)
                {
                    -webkit-transform:rotateY(36deg) translateZ(500px);
                    -moz-transform:rotateY(36deg) translateZ(500px);
                    -ms-transform:rotateY(36deg) translateZ(500px);
                    
                }

            #head div:nth-child(3)
                {
                    -webkit-transform:rotateY(72deg) translateZ(400px);
                    -moz-transform:rotateY(72deg) translateZ(400px);
                    -ms-transform:rotateY(72deg) translateZ(400px);
                    
                }

            #head div:nth-child(4)
                {
                    -webkit-transform:rotateY(108deg) translateZ(500px);
                    -moz-transform:rotateY(108deg) translateZ(500px);
                    -ms-transform:rotateY(108deg) translateZ(500px);
                    
                }
            #head div:nth-child(5)
                {
                    -webkit-transform:rotateY(144deg) translateZ(400px);
                    -moz-transform:rotateY(144deg) translateZ(400px);
                    -ms-transform:rotateY(144deg) translateZ(400px);
                    
                }
            #head div:nth-child(6)
                {
                    -webkit-transform:rotateY(180deg) translateZ(500px);
                    -moz-transform:rotateY(180deg) translateZ(500px);
                    -ms-transform:rotateY(180deg) translateZ(500px);
                    
                }
            #head div:nth-child(7)
                {
                    -webkit-transform:rotateY(216deg) translateZ(400px);
                    -moz-transform:rotateY(216deg) translateZ(400px);
                    -ms-transform:rotateY(216deg) translateZ(400px);
                    
                }
            #head div:nth-child(8)
                {
                    -webkit-transform:rotateY(252deg) translateZ(500px);
                    -moz-transform:rotateY(252deg) translateZ(500px);
                    -ms-transform:rotateY(252deg) translateZ(500px);
                    
                }
            #head div:nth-child(9)
                {
                    -webkit-transform:rotateY(288deg) translateZ(400px);
                    -moz-transform:rotateY(288deg) translateZ(400px);
                    -ms-transform:rotateY(288deg) translateZ(400px);
                    
                }
            #head div:nth-child(10)
                {
                    -webkit-transform:rotateY(324deg) translateZ(500px);
                    -moz-transform:rotateY(324deg) translateZ(500px);
                    -ms-transform:rotateY(324deg) translateZ(500px);
                    
                }
            @-webkit-keyframes donghua{
                0%{transform:rotateX(5deg) rotateY(360deg)}
                50%{transform:rotateX(-5deg) rotateY(180deg)}
                100%{transform:rotateX(5deg) rotateY(0deg)}
            }
            @-moz-keyframes donghua{
                0%{transform:rotateY(10deg) rotateY(0deg)}
                50%{transform:rotateY(-10deg) rotateY(180deg)}
                100%{transform:rotateY(10deg) rotateY(360deg)}
            }
            @-ms-keyframes donghua{
                0%{transform:rotateY(10deg) rotateY(0deg)}
                50%{transform:rotateY(-10deg) rotateY(180deg)}
                100%{transform:rotateY(10deg) rotateY(360deg)}
            }

        </style>
    </head>
    <body>
        <div class="nav"><div><span class="btn"><a href="/?u=<?php echo $u; ?>">solo</a></span></div><div></div><div></div></div>
        <div id="wrap">
            <div id="head"></div>
        </div>
        <script>
            var obj = $('#head');
            var files = $.parseJSON('<?php echo $file ?>');
            var len = files.length;
            var num = 10;
            var start = num;
            var time = 35;
            var over = 1;
            addimg(gethtml(num));
            if(num<len) setTimeout(intervals, 3000);
            
            function gethtml(n){
                var html = '';
                if(len>0){
                    $.each(files, function(i, v){
                        if(n>0 && n<=i) return false;
                        html += '<div style="background:url('+v+') no-repeat;background-size:100%"></div>';
                    });
                }
                return html;
            }
            function intervals(){
                setInterval(overs,1000*time/num);
            }
            function overs(){console.log(11);
                if(over>num) over = 1;
                if(start>=len) start = 0;
                $('#head div:nth-child('+over+')').css('background-image', 'url(\''+files[start]+'\')');
                over++;start++;
            }
            
            function addimg(html){
               obj.append(html);
            };
        </script>
    </body>
</html>
