<?php

set_time_limit(0);
ini_set('memory_limit', '-1');

$arr = [
    'rygf' => 23456,
    'wer' => '诶费德里克V服￥内需放开了你大V你付款发的卡v妇女节等法律男单方面',
    're' => '!@#$%^&*()',
    'time' => time()
];
$str1 = json_encode($arr, JSON_UNESCAPED_UNICODE);
$str2 = Enande::encrypt($str1, 123456789);
var_dump($str2);
$str = Enande::decrypt($str2, 123456789);
var_dump(json_decode($str, true));


require 'words.php';
$str2 = Enande::encrypt2($words, 123456789);
$str = Enande::decrypt2($str2, 123456789);
var_dump($str == $words);


class Enande {
    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt($sourceString, $password) {
        $sourceString = base64_encode($sourceString);
        $p = str_split($password);
        $n = count($p);
        $c = str_split($sourceString);
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return base64_encode(implode('', $c));
    }

    /**
     *
     * @param sourceString
     * @param password
     * @return 明文
     */
    public static function decrypt($sourceString, $password) {
        $sourceString = base64_decode($sourceString);
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/./u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) - mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return base64_decode(implode('', $c));
    }
    
    
    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt2($sourceString, $password) {
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/./u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return implode('', $c);
    }

    /**
     *
     * @param sourceString
     * @param password
     * @return 明文
     */
    public static function decrypt2($sourceString, $password) {
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/./u", $sourceString, $arr2);       
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            $mima = mb_ord($c[$i]) - mb_ord($p[$i%$n]);
            $c[$i] = mb_chr($mima);
        }
        return implode('', $c);
    }
}


//package jiami;
//
//import java.nio.charset.StandardCharsets;
//import java.util.Base64;
//
///**
// * java加解密
// * @author Administrator
// *
// */
//public class Enande {
//    /**
//     * @param sourceString
//     * @param password
//     * @return 密文
//     */
//    public static String encrypt(String sourceString, String password) {
//    	String source = Base64.getEncoder().encodeToString(sourceString.getBytes(StandardCharsets.UTF_8));
//        char[] p = password.toCharArray(); // 字符串转字符数组
//        int n = p.length; // 密码长度
//        char[] c = source.toCharArray(); 
//        int m = c.length; // 字符串长度
//        for (int k = 0; k < m; k++) {
//            int mima = c[k] + p[k % n]; // 加密
//            c[k] = (char) mima;
//        }
//        return Base64.getEncoder().encodeToString(new String(c).getBytes(StandardCharsets.UTF_8));
//    }
//
//    /**
//     *
//     * @param sourceString
//     * @param password
//     * @return 明文
//     */
//    public static String decrypt(String sourceString, String password) {
//    	String source = new String(Base64.getDecoder().decode(sourceString), StandardCharsets.UTF_8);
//        char[] p = password.toCharArray(); // 字符串转字符数组
//        int n = p.length; // 密码长度
//        char[] c = source.toCharArray();
//        int m = c.length; // 字符串长度
//        for (int k = 0; k < m; k++) {
//            int mima = c[k] - p[k % n]; // 解密
//            c[k] = (char) mima;
//        }
//        return new String(Base64.getDecoder().decode(new String(c)), StandardCharsets.UTF_8);
//    }
//}