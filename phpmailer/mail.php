<?php

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'm/Ct.php';


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
    . '<a href="http://cr.amief.club?rpm=m">'
    . '<img style="max-width:400px;" src="//img.alicdn.com/imgextra/i4/97235892/O1CN01yNl9qG1tOaAv2hx6p_!!0-saturn_solar.jpg_220x220.jpg_.webp">'
    . '</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="height: 30px; line-height: 30px; color: gray;text-decoration:none;" href="http://cr.amief.club?rpm=m">官网: cr.amief.club</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="height: 30px; line-height: 30px; color: gray;text-decoration:none;" href="http://cr.amief.club?rpm=m">微信</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<img style="width:150px;" src="http://cr.amief.club/static/img/service.jpg">'
    . '</div>'
    . '</body>'
    . '</html>';

$message = '<!DOCTYPE HTML>'
    . '<html>'
    . '<head>'
    . '<title>优甜缘</title>'
    . '</head>'
    . '<body>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a href="http://cr.amief.club?rpm=m">'
    . '<img style="max-width:400px;" src="//img.alicdn.com/imgextra/i4/97235892/O1CN01yNl9qG1tOaAv2hx6p_!!0-saturn_solar.jpg_220x220.jpg_.webp">'
    . '</a>'
    . '</div>'
    . '</body>'
    . '</html>';


$obj = new Ct();
$user = $obj->selectData(4,10);
//var_dump(implode(' ', $user));exit;
$m = phpmail($subject, $message, $user, true);
if(is_array($m)){
   $user = $obj->delData($m);
}

function phpmail($subject, $message, $user = [], $html = false){
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.qq.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = '1802146854';                     // SMTP username
        $mail->Password   = 'xixneernxtbwegjj';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 25;                                    // TCP port to connect to
        
        $mail->CharSet    = PHPMailer::CHARSET_UTF8; 

        //Recipients
        $mail->setFrom('1802146854@qq.com');
        $mail->addAddress('347802118@qq.com');//自己收
        foreach($user as $u){
            $mail->addBCC($u);
        }
        $mail->addBCC('amiefclub@163.com');
        //附件
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML($html);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
        return true;
    } catch (Exception $e) {
        $msg = $e->getMessage();
        preg_match_all('/ ([0-9a-zA-Z_]+@[0-9a-zA-Z]{1,5}.com)/', $msg, $m);
        if($m[1]){
            return $m[1];
        }else{
            echo "Message could not be sent. Mailer Error: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }
}