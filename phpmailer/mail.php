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
    . '<a href="http://cr.amief.club/?rpm=m">'
    . '<img style="max-width:350px;" src="https://cbu01.alicdn.com/img/ibank/2019/232/075/11049570232_1505824514.jpg" alt="http://cr.amief.club/?rpm=m">'
    . '</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a href="http://cr.amief.club/?rpm=m">'
    . '<img style="max-width:350px;" src="https://cbu01.alicdn.com/img/ibank/2016/893/496/3414694398_831957848.jpg" alt="http://cr.amief.club/?rpm=m">'
    . '</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="height: 30px; line-height: 30px; color: gray;text-decoration:none;" href="http://cr.amief.club/?rpm=m">官网: cr.amief.club</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<a style="height: 30px; line-height: 30px; color: gray;text-decoration:none;" href="http://cr.amief.club/?rpm=m">微信</a>'
    . '</div>'
    . '<div style="text-align: center; margin: 10px;">'
    . '<img style="width:150px;" src="http://cr.amief.club/static/img/service.jpg" alt="http://cr.amief.club/?rpm=m">'
    . '</div>'
    . '</body>'
    . '</html>';


$obj = new Ct();
$n = 200;
for($i = 0; $i < $n; $i++){
    $user = $obj->selectData(20);
    //var_dump(implode(' ', $user));exit;
    if($user){
        $m = phpmail($subject, $message, $user, true);
        if(is_array($m)){
           $user = $obj->delData($m);
        }
        sleep(rand(60, 180));
    }
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
        //echo 'Message has been sent';
        return true;
    } catch (Exception $e) {
        $msg = $e->getMessage();
        preg_match_all('/ ([0-9a-zA-Z_]+@[0-9a-zA-Z]{1,5}.com)/', $msg, $m);
        if($m[1]){
            return $m[1];
        }else{
            //echo "Message could not be sent. Mailer Error: " . htmlspecialchars($e->getMessage());
            $msg = "Message could not be sent. Mailer Error: " . htmlspecialchars($e->getMessage());
            $pos = intval(file_get_contents('./m/pos_bak.ini'));
            file_put_contents('./m/pos.ini', $pos);
            file_put_contents('./log.txt', $pos . $msg.PHP_EOL, FILE_APPEND);
            exit;
            //return false;
        }
    }
}