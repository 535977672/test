<?php
/* 
 * curl
 * PHP 支持 Daniel Stenberg 创建的 libcurl 库，能够连接通讯各种服务器、使用各种协议。
 * libcurl 目前支持的协议有 http、https、ftp、gopher、telnet、dict、file、ldap。 
 * libcurl 同时支持 HTTPS 证书、HTTP POST、HTTP PUT、 FTP 上传(也能通过 PHP 的 FTP 扩展完成)、HTTP 基于表单的上传、代理、cookies、用户名+密码的认证。
 * 
 * 初始化连接句柄；
 * 设置CURL选项；
 * 执行并获取结果；
 * 释放VURL连接句柄。
 */
test();
//doCurlGetRequest('http://127.0.0.1/php/curl_1.php', array('erwewr' => '8003423354'));
//doCurlPostRequest('http://127.0.0.1/php/curl_1.php', array('edfdss'=>3432423,'fdfkdf'=>'dfdndn发你的是DFSDN30423'));
//doCurlPostFileRequest('http://127.0.0.1/php/curl_1.php', dirname(__FILE__).'/ziyuan/1.jpg', array('edfdss'=>3432423,'fdfkdf'=>'dfdndn发你的是DFSDN30423'));
function test(){
    //1
    $ch = curl_init();
    //2
    //curl_setopt_array($ch, array('CURLOPT_URL' => 'https://www.baidu.com/'));
    curl_setopt($ch, CURLOPT_URL, 'https://www.baidu.com/');//需要获取的 URL 地址，也可以在curl_init() 初始化会话的时候。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_HEADER, FALSE);//启用时会将头文件的信息作为数据流输出。
    
    //设置https 支持
    date_default_timezone_get('PRC');   //使用cookies时，必须先设置时区
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  //终止从服务端验证
    //3 curl_exec 执行 cURL 会话 抓取 URL 并把它传递给浏览器
    //成功时返回 TRUE， 或者在失败时返回 FALSE。 
    //然而，如果 设置了 CURLOPT_RETURNTRANSFER 选项，函数执行成功时会返回执行的结果，失败时返回 FALSE 。
    $output = curl_exec($ch);
    if($output === false){
        $err = curl_error($ch);
        var_dump($err);
    }
    //4
    curl_close($ch);
}
/**
 * 封闭curl的调用接口，get的请求方式。
 */
function doCurlGetRequest($url,$requestString = '',$timeout = 5){
    if(empty($url)|| empty($timeout)){
       return false;
    }
    if(is_array($requestString)){
        $requestString = http_build_query($requestString);
    }
    if($requestString){
        $url = $url.'?'.$requestString;//拼装GET请求和数据部分
    }
    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    //curl_setopt($con, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);//允许 cURL 函数执行的最长秒数。
    $output = curl_exec($con);
    curl_close($con);
    return $output;
}
/**
 * 封装 curl 的调用接口，post的请求方式
 */
function doCurlPostRequest($url,$requestString,$timeout = 5){
    if(empty($url) || empty($timeout) || empty($requestString)){
       return false;
    }
    if(is_array($requestString)){
        //$requestString = http_build_query($requestString);
    }
    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
    curl_setopt($con, CURLOPT_POST,true);//在CURLOPT_POSTFIELDS前设置 TRUE 时会发送 POST 请求，类型为：application/x-www-form-urlencoded，是 HTML 表单提交时最常见的一种。
    //全部数据使用HTTP协议中的 "POST" 操作来发送。 
    //要发送文件，在文件名前面加上@前缀并使用完整路径。 
    //文件类型可在文件名后以 ';type=mimetype' 的格式指定。 
    //这个参数可以是 urlencoded 后的字符串，类似'para1=val1&para2=val2&...'，
    //也可以使用一个以字段名为键值，字段数据为值的数组。 
    //如果value是一个数组，Content-Type头将会被设置成multipart/form-data。 
    //从 PHP 5.2.0 开始，使用 @ 前缀传递文件时，value 必须是个数组。 
    //从 PHP 5.5.0 开始, @ 前缀已被废弃，文件可通过 CURLFile 发送。 
    //设置 CURLOPT_SAFE_UPLOAD 为 TRUE 可禁用 @ 前缀发送文件，以增加安全性。
    //CURLOPT_SAFE_UPLOAD PHP 5.5.0 中添加，默认值 FALSE。 PHP 5.6.0 改默认值为 TRUE。. PHP 7 删除了此选项， 必须使用 CURLFile interface 来上传文件
    curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
    
    $output = curl_exec($con);
    curl_close($con);
    return $output; 
}
/**
 * 封装 curl file 的调用接口，post的请求方式
 */
function doCurlPostFileRequest($url,$file,$request = array(), $timeout = 20){
    if(empty($url) || empty($file)){
       return false;
    }
    $con = curl_init((string)$url);
    $cfile = new CURLFile($file, 'image/jpeg', 'test_name');
    $request['fileData'] = $cfile;
    
    curl_setopt($con, CURLOPT_POST,true);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
    
    curl_setopt($con, CURLOPT_POSTFIELDS, $request);
    
    $output = curl_exec($con);
    curl_close($con);
    return $output;
}