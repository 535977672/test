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
        <link rel="stylesheet" href="/plugs/pubuliu/css/component.css">
        <style>
            a{text-decoration:none;}
            .nav{display:flex;margin: 10px;font-size: larger}
            .nav div{flex:1; text-align: center;}
            .nav .btn{width: 100%; height: 40px; line-height: 40px; padding: 5px 10px; border-radius: 5px; background: linear-gradient(to bottom right, #F44336,#9C27B0,#2196F3,#009688,#8BC34A,#FFEB3B,#FF5722);}
            
        </style>
    </head>
    <body>
        <div class="nav"><div><span class="btn"><a href="/bmap.html">map</a></span></div><div></div><div></div></div>

        <div style="margin-top: 5px; padding: 5px;">
            <ul class="grid" id="grid"></ul>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="/plugs/pubuliu/js/modernizr.custom.js"></script>
        <script src="/plugs/pubuliu/js/masonry.pkgd.min.js"></script>
        <script src="/plugs/pubuliu/js/imagesloaded.js"></script>
        <script src="/plugs/pubuliu/js/classie.js"></script>
        <script src="/plugs/pubuliu/js/AnimOnScroll.js"></script>
        <script>
            $('#grid').addClass('effect-'+(Math.ceil(Math.random()*7)+1));
            
            var obj = $('#grid');
            var files = $.parseJSON('<?php echo $file ?>');
            var len = files.length;
            var num = 10;
            var page = 1;
            var loading = true;
            var wh2 = $(window).height()*2;
            addimg(gethtml(num));
            page++;
            
            $(window).scroll(function() {
                if ((wh2 + $(window).scrollTop()) >= $(document).height()) {
                    if(loading) return false;
                    loading = true;
                    addimg(gethtml(num));
                    if(len > page*num) loading = false;
                    page++;
                }
            });
            loading = false;
            
            function gethtml(n){
                var html = '';
                if(len < page*num){
                    n = page*num-len;
                }else{
                    n = num;
                }
                for(var i = 0; i < n; i++){
                    var f = (page-1)*num+i;
                    html += '<li><img src="'+files[f]+'" alt="" data-preview-src="" data-f="'+f+'"></li>';
                }
                return html;
            }
            
            function addimg(html){
                obj.append(html);
                animOnScrollLoad('grid');
            };
            
            function animOnScrollLoad(id){
                if($('#'+id+' li').length < 1) return;
                new AnimOnScroll(document.getElementById(id), {
                    minDuration : 0.4,
                    maxDuration : 0.7,
                    viewportFactor : 0.2
                });
            }
        </script>
    </body>
</html>