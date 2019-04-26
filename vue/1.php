<?php
header('Content-type:text/json; charset=utf-8');
if($_POST){
    echo json_encode($_POST);exit;
}

if($_GET){
    echo json_encode($_GET);exit;
}

echo '{qw:12,ee:231111111}';exit;