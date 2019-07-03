<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$obj = new Elasticsearch();
$obj->indexMore(1,1);

class Elasticsearch{
    public $_index = 'my_test11';
    public $_type = 'my_type';
    
    public function __construct()
    {
        if(strtoupper(substr(PHP_OS,0,3))!=='WIN'){
            $this->is_linux = true;
        }else{
            $this->is_linux = false;
        }
    }
    
    //批量（bulk）索引文档 
    //action 批量操作的行为
    //create	当文档不存在时创建之。
    //index	    创建新文档或替换已有文档。
    //update	局部更新文档。
    //delete	删除一个文档。
    public function indexMore($n = 0, $num = 0) {
        for($i = 0; $i < $n; $i++){
            sleep(mt_rand(1,20));
            if($this->is_linux){
                $pid = pcntl_fork();
                if (!$pid) {
                    $this->index($num);
                }
            }else{
                 $this->index($num);
            }
        }
    }
    
    public function index($n = 0) {
        $params['body'] = [];
        
        for ($i = 0; $i <= $n; $i++) {
            $id = $this->getRandStr(20,3);
            $params['body'][] = [
                'index' => [
                    '_index' => $this->_index,
                    '_type' => $this->_type,
                    //'_id' => $id
                ]
            ];
            $params['body'][] = [
                'pid' => $id,
                'n' => 123,
                ];
        }
        $requestString = json_encode($params);
        $url = '127.0.0.1:9200/_bulk';
        echo $requestString;
        $re = $this->doCurlPostRequest($url, $requestString);
        var_dump($re);
    }

    public function getRandStr($length = 10, $type = 1){
        if ($type == 1) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST0123456789';
        } else if ($type == 2) {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        } else if ($type == 3) {
            $chars = '0123456789';
        }
        $len = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $len - 1)];
        }
        return $str;
    }
    
    /**
     * 封装 curl 的调用接口，post的请求方式
     */
    function doCurlPostRequest($url,$requestString,$timeout = 30){
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
        curl_setopt($con, CURLOPT_POST,true);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);

        $output = curl_exec($con);
        curl_close($con);
        return $output; 
    }
}
