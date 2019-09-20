<?php

set_time_limit(0);
ini_set('memory_limit', '-1');

//$arr = [
//    'rygf' => 23456,
//    'wer' => "百度 地\nggg\t31!@#$%^&*()！@#￥%……&*（）》《",
//    're' => '!@#$%^&*()',
//    'time' => time()
//];
//$str1 = json_encode($arr, JSON_UNESCAPED_UNICODE);
//$str2 = Enande::encrypt2("百度", "123456789");
//var_dump($str2);
//$str = Enande::decrypt($str2, 123456789);
//var_dump(json_decode($str, true));


//$str1 = json_encode($arr, JSON_UNESCAPED_UNICODE);
//$str2 = Enande::encrypt2($str1, 123456789);
//var_dump($str2);
//$str = Enande::decrypt2($str2, 123456789);
//var_dump(json_decode($str, true));

//require 'words.php';
//$str2 = Enande::encrypt($words, 123456789);
//$str = Enande::decrypt($str2, 123456789);
//var_dump($str == $words);

class Enande {
    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt($sourceString, $password) {
        return self::crypt($sourceString, $password);
    }

    /**
     *
     * @param sourceString
     * @param password
     * @return 明文
     */
    public static function decrypt($sourceString, $password) {
        return self::crypt($sourceString, $password, 1);
    }
    
    protected static function crypt($sourceString, $password, $t = 0){
        $p = str_split($password);
        $n = count($p);
        preg_match_all("/[\s\S]/u", $sourceString, $arr2);
        $c = $arr2[0];
        $m = count($c);
        for($i = 0; $i < $m; $i++){
            if ($t) {
                $mima = mb_ord($c[$i]) - mb_ord($p[$i%$n]);
            } else {
                $mima = mb_ord($c[$i]) + mb_ord($p[$i%$n]);
            }
            $c[$i] = mb_chr($mima);
        }
        return implode('', $c);
    }


    /**
     * @param sourceString
     * @param password
     * @return 密文
     */
    public static function encrypt2($sourceString, $password) {
        return base64_encode(preg_replace('/\s/', '',self::crypt($sourceString, $password)));
    }
}

