<?php
//mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] ) : bool
//$to user@example.com, anotheruser@example.com
//$subject 主题 本项不能包含任何换行符
//$message所要发送的消息  行之间必须以一个 LF（\n）分隔。每行不能超过 70 个字符  wordwrap($message, 70);
//如果在一行开头发现一个句号，则会被删掉。要避免此问题，将单个句号替换成两个句号
//str_replace("\n.", "\n..", $text)

//php.ini
//SMTP = smtp.qq.com
//smtp_port = 465
//sendmail_from = 2@qq.com //win 如果不指定，就必须在程序中指定
//sendmail_path = D:\sendmail\sendmail.exe -t//unix

//php mail()函数在windows不能用，需要安装sendmail，假如是用的XAMPP，则已经下载好
//https://www.glob.com.au/sendmail/sendmail.zip
//修改sendmail.ini
//sendmail.ini中加入用户名和密码
//smtp_server= smtp.qq.com
//smtp_port= 465  //POP3服务器（端口995）	SMTP服务器（端口465或587）
//auth_username=2@qq.com
//auth_password=xiiusofhnvwxcafe //授权码
//force_sender=2@qq.com

//win配置SMTP服务
//打开telnet服务
//邮箱开启pop3/smtp和IMAP/SMTP服务
//打开cmd，输入：telnet smtp.qq.com 25 ,连接邮件服务器
//输入helo qq.com，向服务器表明身份
//输入登录 auth login
//输入邮箱的base64编码 NTM1OTc3NjcyQHFxLmNvbQ==
//输入开启IMAP/SMPT时授权码的base64编码 eGlpdXNvZmhudnd4Y2FmZg==

//ini_set('SMTP', 'smtp.qq.com');
//ini_set('smtp_port', 465);
//ini_set('sendmail_from', '');
//ini_set('sendmail_path', 'D:\sendmail\sendmail.exe -t');

//文本
$subject = '文本';
$message = '文本1234567890~！@#￥%……&*（）QWERTYUIOPASDF'
    . 'GHJKLZXCVBNMqwrtyuiopasdfghjklzxcvbnm{}：“《》？【】'
    . '】；‘，。、 asa     fdd';
send($subject, $message);

//html
//样式尽量不要被邮箱默认样式覆盖
$subject = 'html';
$message = '<!DOCTYPE HTML>'
    . '<html>'
    . '<head>'
    . '<title>html</title>'
    . '</head>'
    . '<body>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a href="https://www.baidu.com/index.php">'
    . '<img src="//img.alicdn.com/imgextra/i4/97235892/O1CN01yNl9qG1tOaAv2hx6p_!!0-saturn_solar.jpg_220x220.jpg_.webp">'
    . '</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="width:70px; height: 30px; line-height: 30px; color:red; border:1px solid #ccc; background: #fff;border-radius: 1px;" href="https://www.baidu.com/index.php">红色按钮</a>'
    . '</div>'
    . '</body>'
    . '</html>';
send($subject, $message, true);


function send($subject, $message, $html = false){
    $to = '535977672@qq.com';
    
    $from = "535977672@qq.com";   // 邮件发送者
    $headers = "From:" . $from. "\r\n";       // 头部信息设置
    //$headers .= 'Cc: amiefclub@163.com' . "\r\n";//抄送 能看到其他接收人
    $headers .= 'Bcc: amiefclub@163.com' . "\r\n";//暗抄送
    //$headers .= "Content-Transfer-Encoding: 8bit\r\n";

    if($html){
        // 当发送 HTML 电子邮件时，请始终设置 content-type
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    }
    
    $message = wordwrap($message, 70);
    $message = str_replace("\n.", "\n..", $message);

    mail($to, $subject, $message, $headers);
}