<?php

class Ct
{
    public $host = '127.0.0.1';
    public $port = '3306';
    public $db = 'test';
    public $root = 'root';
    public $pwd = 'root';
    public $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', 
        PDO::ATTR_PERSISTENT => true,
        ];

    public $table = 'mail_send';
    public $win;
    public $start = 0;
    public $conn = null;
    
    public static $mail = [];
    
    public function __construct(){
        $this->win = strtoupper(substr(PHP_OS,0,3))==='WIN';
        $this->conn = @new PDO("mysql:host=$this->host:$this->port;dbname=$this->db", $this->root, $this->pwd, $this->options);
        if(!$this->start){
            $stmt = $this->conn->prepare("select * from $this->table order by id desc limit 1");
            $stmt->execute(['id' => 4]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            $this->start = $data['mail'];
        }
    }
    
    public function index()
    {
        if(!$this->win){
            //n=5 5+4+3+2+1
            for($i = 0; $i < 1; $i++){
                $pid = pcntl_fork();
                if (!$pid) {
                    $this->insertData(2,3);
                }
            }
        }else{
            for($i = 0; $i < 1; $i++){
                $this->insertData(2000,1000);
            }
        }
    }
    
    public function getRandStr(){
        return ++$this->start;
    }
    
    public function insertData($n = 1, $jn = 1){
        for($i = 0; $i < $n; $i++){
            $sql = 'INSERT INTO '.$this->table.' (`mail`) VALUES ';
            for($j = 0; $j < $jn; $j++){
                $sql .= "('" . 
                    $this->getRandStr(10) . "') , ";
            }
            $sql = substr($sql, 0, -3);
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            //echo $i.'-'.date('H:i:s').PHP_EOL;
        }
    }
    
    public function selectData($page, $limit){
        $start = ($page-1)*$limit;
        $stmt = $this->conn->prepare("select * from $this->table order by id asc limit $start,$limit");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(!self::$mail){
            $stmt = $this->conn->prepare("select mail from mail order by id asc");
            $stmt->execute();
            $tpm = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($tpm as $v){
                self::$mail[] = $v['mail'];
            }
        }
        $re = [];
        foreach($data as $v){
            $m = $v['mail'].'@qq.com';
            if(!in_array($m, self::$mail)){
                $re[] = $m;
            }
        }
        return $re;
    }
}