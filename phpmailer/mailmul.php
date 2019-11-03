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
$smtp = $obj->selectSmtp();
shuffle($smtp);
$cursmtp = array_pop($smtp);
$n = 200;
for($i = 0; $i < $n; $i++){
    $user = $obj->selectData($cursmtp['limit']);
    //var_dump(implode(' ', $user));exit;
    if($user){
        array_push($user, 'amiefclub@163.com');
        $m = phpmail($subject, $message, $user, $cursmtp);
        if(is_array($m) && !empty($m)){
           $user = $obj->delData($m);
        }else if($m === false){
            if($smtp) $cursmtp = array_pop($smtp);
            else exit;
        }
        sleep(rand(60, 180));
    }
}

function phpmail($subject, $message, $user, $cursmtp){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $cursmtp['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cursmtp['name'];
        $mail->Password   = $cursmtp['pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $cursmtp['port'];
        
        $mail->CharSet    = PHPMailer::CHARSET_UTF8; 

        $mail->setFrom($cursmtp['name']);
        $mail->addAddress('347802118@qq.com');
        foreach($user as $u){
            $mail->addBCC($u);
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        file_put_contents('./log.txt', 'suc ' . substr($cursmtp['name'], 2, 7).PHP_EOL, FILE_APPEND);
        return true;
    } catch (Exception $e) {
        $msg = htmlspecialchars($e->getMessage());
        preg_match_all('/ ([0-9a-zA-Z_]+@[0-9a-zA-Z]{1,5}.com)/', $msg, $m);
        if($m[1]){
            file_put_contents('./log.txt', 'suc ' . substr($cursmtp['name'], 2, 7).PHP_EOL, FILE_APPEND);
            return $m[1];
        }else{
            file_put_contents('./log.txt', 'err ' . substr($cursmtp['name'], 2, 7).PHP_EOL, FILE_APPEND);
            $msg = "Message could not be sent. Mailer Error: " . $msg;
            $pos = intval(file_get_contents('./m/pos_bak.ini'));
            file_put_contents('./m/pos.ini', $pos);
            file_put_contents('./log.txt', $pos . $msg.PHP_EOL, FILE_APPEND);
            return false;
        }
    }
}