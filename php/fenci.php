<?php

date_default_timezone_set('Asia/Shanghai');

$f = new FenCi();
$str = '公请，人人都，太突然他一人';
$re = $f->spStr($str);
var_dump($re);

$re = $f->getCode($re);
var_dump($re);



/**
 * 分词
 */
class FenCi {
    /**
     * UTF-8 中文二元分词
     */
    public function spStr($str)
    {
        $cstr = array();

        $search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "{1}quot;", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】",);

        $str = str_replace($search, " ", $str);
        preg_match_all("/[a-zA-Z]+/", $str, $estr);
        preg_match_all("/[0-9]+/", $str, $nstr);

        $str = preg_replace("/[0-9a-zA-Z]+/", " ", $str);
        $str = preg_replace("/\s{2,}/", " ", $str);

        $str = explode(" ", trim($str));

        foreach ($str as $s) {
            $l = strlen($s);

            $bf = null;
            for ($i= 0; $i< $l; $i=$i+3) {
                $ns1 = $s[$i].$s[$i+1].$s[$i+2];
                if (isset($s[$i+3])) {
                    $ns2 = $s[$i+3].$s[$i+4].$s[$i+5];
                    if (preg_match("/[\x80-\xff]{3}/",$ns2)) $cstr[] = $ns1.$ns2;
                } else if ($i == 0) {
                    $cstr[] = $ns1;
                }
            }
        }

        $estr = isset($estr[0])?$estr[0]:array();
        $nstr = isset($nstr[0])?$nstr[0]:array();

        return array_merge($nstr,$estr,$cstr);
    }

    public function getCode($data)
    {
        foreach ($data as $s) {
            $s = iconv('UTF-8','GB2312',$s);
            $code[] = $this->gbCode($s);
        }
        $code = implode(" ", $code);
        return $code;
    }

    private function gbCode($str)
    {
        $return = null;
        if (!preg_match("/^[\x80-\xff]{2,}$/", $str)) return $str;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i = $i + 2) {
            $return .= sprintf("%02d%02d", ord($str[$i]) - 160, ord($str[$i + 1]) - 160);
        }
        return $return;
    }
}