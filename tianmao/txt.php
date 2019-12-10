<?php

$fileName = 'C:/Users/Administrator/Desktop/mtxt.php';

$dir = 'C:/Users/Administrator/Desktop/11/*.php';

$myfilea = fopen($fileName, "a");
foreach (glob($dir) as $readName) {
    $myfiler = fopen($readName, "r");
    $str = fread($myfiler,filesize($readName));
    fwrite($myfilea, $str);
    fclose($myfiler);
}
fclose($myfilea);










