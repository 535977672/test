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
        </style>
    </head>
    <body>

        <div id="main" class="chart">开发中...</div>
        <script>
            var u = window.location.hash.substr(1);
            console.log(u);
        </script>
    </body>
</html>