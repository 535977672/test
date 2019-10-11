<?php

require 'src/PHPMailer.php';
require 'src/SMTP.php';


set_time_limit(0);
ini_set('memory_limit', '-1');


$subject = '优甜缘';

$message = '<!DOCTYPE HTML>'
    . '<html>'
    . '<head>'
    . '<title>优甜缘</title>'
    . '</head>'
    . '<body>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a href="http://cr.amief.club">'
    . '<img style="max-width:400px;" src="//img.alicdn.com/imgextra/i4/97235892/O1CN01yNl9qG1tOaAv2hx6p_!!0-saturn_solar.jpg_220x220.jpg_.webp">'
    . '</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="height: 30px; line-height: 30px; color: gray;text-decoration:none;" href="http://cr.amief.club">官网: cr.amief.club</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="height: 30px; line-height: 30px; color: gray;text-decoration:none;" href="http://cr.amief.club">微信</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<img style="width:150px;" src="http://cr.amief.club/static/img/service.jpg">'
    . '</div>'
    . '</body>'
    . '</html>';

$user = [];

phpmail($subject, $message, $user, true);

function phpmail($subject, $message, $user = [], $html = false){
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.163.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'amiefclub';                     // SMTP username
        $mail->Password   = 'xiiusofhnvwxcaf1';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 25;                                    // TCP port to connect to
        
        $mail->CharSet    = PHPMailer::CHARSET_UTF8; 

        //Recipients
        $mail->setFrom('amiefclub@163.com');
        $mail->addAddress('amiefclub@163.com');//自己收
        $mail->addBCC('535977672@qq.com');
        foreach($user as $u){
            $mail->addBCC($u);
        }
        //附件
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML($html);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: " . htmlspecialchars($e->getMessage());
    }
}